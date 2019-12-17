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
const map_clusters_img_path = "../js/google_maps/map_clusters/m";

const start_position = {
    lat: 41.177964, 
    lng: -8.597730
};

let iconBase = '../js/google_maps/map_icons/';   
let icons = {
    house : {
        icon: iconBase + 'house.png'
    },
    apartment : {
        icon: iconBase + 'apartment.png'
    },
    tag : {
        icon: iconBase + 'tag.png'
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
            //fetchMarkersFromDB();

            map.addListener('drag', handleAddressChangePan);
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

function addMarkers(residences) {
    if (map == null) {
        console.log("map is null");
        return;
    }

    let newMarkers = residences.map(function(residence) {

        /* marker info */
        const marker_info = {
            residenceID: residence.residenceID,
            title: residence.title,
            pricePerDay: parseInt(residence.pricePerDay),
            totalPrice: parseInt(residence.pricePerDay * reservationNumberOfDays()),
            address: residence.address,
            city: residence.city,
            country: residence.country,
            position: {
                lat: parseFloat(residence.latitude),
                lng: parseFloat(residence.longitude)
            },
            type: residence.typeStr,
            rating: residence.rating
        }

        const width_base = 40;
        const width_per_char = 8;
        const priceString = marker_info.pricePerDay.toString();
        const marker_width = width_base + width_per_char * priceString.length;

        const normalIcon = {
            url: icons.tag.icon,
            scaledSize: new google.maps.Size(marker_width, 30)
        };

        const normalLabel = {
            text: '€' + priceString,
            fontWeight: "500"
        };

        let newMarker = new google.maps.Marker({
            position: marker_info.position,
            title: marker_info.address,
            label: normalLabel,
            icon: normalIcon
        });

        newMarker.addListener('mouseover', function() {
            const hoverIcon = {
                url: icons.tag.icon,
                scaledSize: new google.maps.Size(marker_width*1.1, 30*1.1)
            };

            const hoverLabel = {
                text: '€' + priceString,
                fontSize: "16px",
                fontWeight: "500"
            };
            newMarker.setIcon(hoverIcon);
            newMarker.setLabel(hoverLabel);
        });

        newMarker.addListener('mouseout', function() {
            newMarker.setIcon(normalIcon);
            newMarker.setLabel(normalLabel);
        });

        addInfoWindow(newMarker, marker_info);
        newMarker.inCluster = true;

        return newMarker;
    });

    markers.push(...newMarkers);

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
            const starsHTML = isNaN(markerInfo.rating) ? "" : markerInfo.rating/2 + ' <i class="fas fa-star"></i>';
            infoWindowContent = `
                <a class="search_results_info_window" href="../pages/view_house.php?id=` + markerInfo.residenceID + `">
                    <img src="` + "https://picsum.photos/250/150" + `">
                    <h3>` + htmlEntities(markerInfo.type) + `</h3>
                    <h2>` + htmlEntities(markerInfo.title) + `</h2>
                    <h3>€` + htmlEntities(markerInfo.pricePerDay) + ` per night!</h3>
                    <p>` + starsHTML + `</p>
                    <p>€` + htmlEntities(markerInfo.totalPrice) + ` total</p>
                </div>`;
            break;

        case "add_house":
            infoWindowContent = `
                <div class="add_house_info_window">
                    <h4>` + htmlEntities(markerInfo.title) + `</h6>
                    <p>` + htmlEntities(markerInfo.type) + `</p>
                    <p>99€</p>
                    <p>` + htmlEntities(markerInfo.city) + `</p>
                    <p>` + htmlEntities(markerInfo.country) + `</p>
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

function currentMapLocation() {
    if (map == null) return null;
    return map.getCenter();
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
    if (map_clusterer) {
        map_clusterer.removeMarkers(markers, true);
    }
    markers = [];
}