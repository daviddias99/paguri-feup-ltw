'use strict'

let map;
let map_clusterer;
let markers = [];
let info_windows = [];
let map_clusters_img_path = "../js/google_maps/map_clusters/m";

let start_position = {
    lat: -34.397, 
    lng: 150.644
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

    fetchMarkersFromDB();
}

function fetchMarkersFromDB() {
    let request = new XMLHttpRequest();
    request.onload = addMarkers;
    request.open("get", "../ajax/residences_markers.php");
    request.send();
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

    moveMap(markers[0]);
    setMapZoom(18);
    initMapClusterer();
    filterMarkersInRadius({lat: markers[0].getPosition().lat(), lng: markers[0].getPosition().lng()}, 5);
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

function addMarker(position) {
    if (map == null) return;

    markers.push(new google.maps.Marker({
        position: position,
        map: map
    }));
}

function addInfoWindow(marker) {
    let infoWindow = new google.maps.InfoWindow({
        content: 'oiiii'
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

function moveMap(marker) {
    if (map == null) return;
    map.panTo(marker.getPosition());
}

function setMapZoom(zoom) {
    if(map == null) return;
    map.setZoom(zoom);
}

function distanceBetweenPoints(coords1, coords2) {
    let R = 6371e3; // earths radius in metres
    
    let ang1 = toRadians(coords1.lat);
    let ang2 = toRadians(coords2.lat);
    let latDiff = toRadians(coords2.lat-coords1.lat);
    let lngDiff = toRadians(coords2.lng-coords1.lng);

    let a = Math.sin(latDiff/2) * Math.sin(latDiff/2) +
            Math.cos(ang1) * Math.cos(ang2) *
            Math.sin(lngDiff/2) * Math.sin(lngDiff/2);
    let c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));

    return R * c;
}

function toRadians(num) {
    return num * (Math.PI / 180);
}

function filterMarkersInRadius(origin, radius) {
    markers.forEach(function(marker) {
        let marker_coords = marker.getPosition();
        let dist = distanceBetweenPoints(origin, {lat:marker_coords.lat(), lng: marker_coords.lng()});
        if (dist > radius) {
            if (marker.inCluster) {
                marker.inCluster = false;
                map_clusterer.removeMarker(marker);
            }
        }
        else {
            if(!marker.inCluster) {
                marker.inCluster = true;
                map_clusterer.addMarker(marker);
            }
        }
    });
}