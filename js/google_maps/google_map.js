'use strict'

// check if valid map page
getCurrentMapPage();

function getCurrentMapPage() {
    const path = window.location.pathname;
    if (path.search("search_results.php") != -1) {
        return "search_results";
    }
    else if (path.search("add_house.php") != -1) {
        return "add_house";
    }
    
    throw new Error("Page does not require a map!"); 
}

import('../filter_results/filters.js');

let map;
let map_clusterer;
let markers = [];
let info_windows = [];
let map_clusters_img_path = "../js/google_maps/map_clusters/m";

let start_position = {
    lat: 41.177964, 
    lng: -8.597730
};

let iconBase = '../js/google_maps/map_icons/';   
let icons = {
    'house' : {
        icon: iconBase + 'house.png'
    },
    'apartment' : {
        icon: iconBase + 'apartment.png'
    }
}
  

function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        center: start_position,
        zoom: 8
    });

    // different behaviour depending on current page
    const current_page = getCurrentMapPage();
    switch(current_page) {
        case "search_results":
            fetchMarkersFromDB();
            break;

        case "add_house":
            map.addListener('click', function(e) {
                clearMarkers();
                reverseGeocoding(e.latLng);
            });
            break;
    }
}

function fetchMarkersFromDB() {
    let request = new XMLHttpRequest();
    request.onload = addMarkers;
    request.open("get", "../ajax/residences_markers.php");
    request.send();
}

function addMarker(location, markerInfo) {
    let newMarker = new google.maps.Marker({
        position: location,
        map: map,
        title: markerInfo.title,
        animation: google.maps.Animation.DROP
    });
    newMarker.addListener('click', toggleBounce.bind(newMarker));
    addInfoWindow(newMarker, markerInfo);

    markers.push(newMarker);
}

function addMarkers(event) {
    if (map == null) {
        console.log("map is null");
        return;
    }
    let residences = JSON.parse(event.target.responseText);

    markers = residences.map(function(residence) {

        /* residence info */
        let address = residence.address;
        let city = residence.city;
        let country = residence.country;
        let position = {lat: parseFloat(residence.latitude), lng: parseFloat(residence.longitude)};
        let type = residence.type;

        /*let title = 'Titulo';
        let position = residence;
        let type = 'house';*/


        let newMarker = new google.maps.Marker({
            position: position,
            map: map,
            title: address,
            icon: icons[type].icon,
            animation: google.maps.Animation.DROP
        });
        newMarker.addListener('click', toggleBounce.bind(newMarker));

        addInfoWindow(newMarker);
        newMarker.inCluster = true;

        return newMarker;
    });

    initMapClusterer();
}

function toggleBounce() {
    let marker = this;
    if (marker.getAnimation() !== null) {
        marker.setAnimation(null);
    } else {
        marker.setAnimation(google.maps.Animation.BOUNCE);

        setTimeout(function() {
            marker.setAnimation(null);
        }, 2000);
    }
}

function addInfoWindow(marker, markerInfo) {

    let infoWindowContent;
    const current_page = getCurrentMapPage();
    switch(current_page) {
        case "search_results":
            infoWindowContent = `
                <div class="marker_info_window">
                    <p class="type">Tipo</p>
                    <h6>Titulo</h6>
                    <p>Cidade</p>
                    <p>Pais</p>
                </div>`;
            break;

        case "add_house":
            infoWindowContent = `
                <div class="add_house_info_window">
                    <h4>` + markerInfo.title + `</h6>
                    <p>` + markerInfo.type + `</p>
                    <p>99€</p>
                    <p>` + markerInfo.city + `</p>
                    <p>` + markerInfo.country + `</p>
                </div>`;
            break;
        default:
            throw new Error("Unknown current page.");
    }

    let infoWindow = new google.maps.InfoWindow({
        content: infoWindowContent
    });

    marker.addListener('click', function() {
        infoWindow.open(map, marker);
    });

    info_windows.push(infoWindow);
}

function initMapClusterer() {
    if (map == null) return;

    map_clusterer = new MarkerClusterer(map, markers, {imagePath: map_clusters_img_path});
}

function moveMap(position) {
    if (map == null) return;
    map.panTo(position);
}

function setMapZoom(zoom) {
    if(map == null) return;
    map.setZoom(zoom);
}

function distanceBetweenPoints(coords1, coords2) {
    const EARTH_RADIUS = 6378; // km
    
    let ang1 = toRadians(coords1.lat);
    let ang2 = toRadians(coords2.lat);
    let latDiff = toRadians(coords2.lat-coords1.lat);
    let lngDiff = toRadians(coords2.lng-coords1.lng);

    let a = Math.sin(latDiff/2) * Math.sin(latDiff/2) +
            Math.cos(ang1) * Math.cos(ang2) *
            Math.sin(lngDiff/2) * Math.sin(lngDiff/2);
    let c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));

    return EARTH_RADIUS * c;
}

function toRadians(num) {
    return num * (Math.PI / 180);
}

function filterMarkersInRadius(origin, radius) {

    markers.forEach(function(marker) {
        if (origin == null) {
            disableMarker(marker);
            return;
        }
        let marker_coords = marker.getPosition();
        let dist = distanceBetweenPoints(origin, {lat:marker_coords.lat(), lng: marker_coords.lng()});
        if (dist > radius)
            disableMarker(marker);
        else
            enableMarker(marker);
    });
}

function filterResidencesInRadius(origin,radius){

    filterUpdateHandler(origin,radius);
}

function enableMarker(marker) {
    if(!marker.inCluster) {
        marker.inCluster = true;
        map_clusterer.addMarker(marker);
    }
}

function disableMarker(marker) {
    if (marker.inCluster) {
        marker.inCluster = false;
        map_clusterer.removeMarker(marker);
    }
}

function clearMarkers() {
    for(let i = 0; i < markers.length; i++)
        markers[i].setMap(null);
    markers = [];
}