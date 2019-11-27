'use strict'

let map;
let map_clusterer;
let markers = [];
let info_windows = [];
let map_clusters_img_path = "../google_maps_testing/map_clusters/m";

let start_position = {
    lat: -34.397, 
    lng: 150.644
};

let iconBase = '../google_maps_testing/map_icons/';   
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

        return newMarker;
    });

    moveMap(markers[0]);
    setMapZoom(18);
    initMapClusterer();
}

function toggleBounce() {
    let marker = this;
    if (marker.getAnimation() !== null) {
        marker.setAnimation(null);
    } else {
        marker.setAnimation(google.maps.Animation.BOUNCE);
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
    
    let ang1 = coords1.lat.toRadians();
    let ang2 = coords2.lat.toRadians();
    let latDiff = (coords2.lat-coords1.lat).toRadians();
    let lngDiff = (coords2.lng-coords1.lng).toRadians();

    let a = Math.sin(latDiff/2) * Math.sin(latDiff/2) +
            Math.cos(ang1) * Math.cos(ang2) *
            Math.sin(lngDiff/2) * Math.sin(lngDiff/2);
    let c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));

    return R * c;
}

