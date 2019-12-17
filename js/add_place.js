'use strict'

function encodeForAjax(data) {
    return Object.keys(data).map(function (k) {
        return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&')
}

document.getElementById("submit_button").onclick = function (event) {
    event.preventDefault();

    const owner = document.getElementById("user_id").value;
    const title = document.getElementById("title_input").value;
    const type = document.getElementById("house_type_input").value;
    const description = document.getElementById("description_input").value;
    const capacity = document.getElementById("capacity_input").value;
    const numBeds = document.getElementById("num_beds_input").value;
    const numBedrooms = document.getElementById("num_bedrooms_input").value;
    const numBathrooms = document.getElementById("num_bathrooms_input").value;
    const location = document.getElementById("location").value;
    const city = document.getElementById("city").value;
    const country = document.getElementById("country").value;
    const latitude = document.getElementById("latitude").value;
    const longitude = document.getElementById("longitude").value;
    const pricePerDay = document.getElementById("price_input").value;

    const selectedCommodities = [];
    const commodityCheckboxes = document.querySelectorAll(".commodities");;
    commodityCheckboxes.forEach(element => {
        if (element.checked)
            selectedCommodities.push(element.value);
    });

    const request = new XMLHttpRequest();

    request.open("post", "../api/residence.php?", true);
    request.setRequestHeader('Accept', 'application/json');
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
    request.send(encodeForAjax({
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
            commodities: JSON.stringify(selectedCommodities)
        }));
};