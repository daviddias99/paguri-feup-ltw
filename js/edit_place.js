'use strict'

function encodeForAjax(data) {
    return Object.keys(data).map(function (k) {
        return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&')
}

document.getElementById("submit_button").onclick = function (event) {
    event.preventDefault();

    const id = document.getElementById("place_id").value;
    const owner = document.getElementById("user_id").value;
    const title = document.getElementById("title_input").value;
    const type = document.getElementById("type").value;
    const description = document.getElementById("description_input").value;
    const capacity = document.getElementById("capacity").value;
    const numBeds = document.getElementById("num_beds").value;
    const numBedrooms = document.getElementById("num_bedrooms").value;
    const numBathrooms = document.getElementById("num_bathrooms").value;
    const location = document.getElementById("location").value;
    const city = document.getElementById("city").value;
    const country = document.getElementById("country").value;
    const latitude = document.getElementById("latitude").value;
    const longitude = document.getElementById("longitude").value;
    const pricePerDay = document.getElementById("price").value;
    const commodities = document.getElementById("commodities").value;


    const request = new XMLHttpRequest();

    console.log(encodeForAjax({
        id: id,
        owner: owner,
        title: title,
        description: description,
        pricePerDay: pricePerDay,
        capacity: capacity,
        nBathrooms: numBathrooms,
        nBeds: numBeds,
        nBedrooms: numBedrooms,
        type: type,
        address: location,
        city: city,
        country: country,
        latitude: latitude,
        longitude: longitude,
        pricePerDay: pricePerDay,
        commodities: commodities
    }))

    request.open("put", "../api/residence.php?" + encodeForAjax({
        id: id,
        owner: owner,
        title: title,
        description: description,
        pricePerDay: pricePerDay,
        capacity: capacity,
        nBathrooms: numBathrooms,
        nBeds: numBeds,
        nBedrooms: numBedrooms,
        type: type,
        address: location,
        city: city,
        country: country,
        latitude: latitude,
        longitude: longitude,
        pricePerDay: pricePerDay,
        commodities: commodities
    }), true);
    request.setRequestHeader('Accept', 'application/json');

    request.send();
};