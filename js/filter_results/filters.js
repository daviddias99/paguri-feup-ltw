'use strict'

function htmlEntities(str) {
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

let prev_filter_state = null;
let prev_residences_response = null;

class FilterState {

    constructor(location, checkin, checkout, priceFrom, priceTo, ratingFrom, ratingTo, type, nBeds, capacity, commodities) {

        this.location = location;
        this.checkin = checkin;
        this.checkout = checkout;
        this.priceFrom = priceFrom;
        this.priceTo = priceTo;
        this.ratingFrom = ratingFrom;
        this.ratingTo = ratingTo;
        this.type = type;
        this.nBeds = nBeds;
        this.capacity = capacity;
        this.commodities = commodities;
    }

    urlString() {
        let urlStr = "?";

        if (this.location != "") urlStr += "location=" + encodeURIComponent(this.location) + "&";
        if (this.checkin != "") urlStr += "checkin=" + encodeURIComponent(this.checkin) + "&";
        if (this.checkout != "") urlStr += "checkout=" + encodeURIComponent(this.checkout) + "&";
        if (this.priceFrom != "") urlStr += "min_price=" + encodeURIComponent(this.priceFrom )+ "&";
        if (this.priceTo != "") urlStr += "max_price=" + encodeURIComponent(this.priceTo) + "&";
        if (this.ratingFrom != "") urlStr += "min_rating=" + encodeURIComponent(this.ratingFrom) + "&";
        if (this.ratingTo != "") urlStr += "max_rating=" + encodeURIComponent(this.ratingTo) + "&";
        if (this.type != "") urlStr += "type=" + encodeURIComponent(this.type) + "&";
        if (this.nBeds != "") urlStr += "min_beds=" + encodeURIComponent(this.nBeds) + "&";
        if (this.capacity != "") urlStr += "guest_count=" + encodeURIComponent(this.capacity) + "&";

        if (Object.keys(this.commodities).length > 0) {
            let first = true;
            for (const commodity in this.commodities) {
                if (this.commodities[commodity] == true) {
                    if (first) {
                        urlStr += "commodities=";
                        first = false;
                    }
                    urlStr += encodeURIComponent(commodity) + ","
                }
            }
            // remove trailing comma
            urlStr = urlStr.substr(0, urlStr.length-1);
            urlStr += "&";
        }

        // remove trailing & or ?
        urlStr = urlStr.substr(0, urlStr.length-1);

        return urlStr;
    }

}

function getCurrentFilterState() {

    const location = document.getElementById("location").value;
    const nBeds = document.getElementById("nBeds").value;
    const capacity = document.getElementById("capacity").value;
    const checkin = document.getElementById("check_in").value;
    const checkout = document.getElementById("check_out").value;
    const type = document.getElementById("housing_type").value;
    const priceFrom = document.getElementById("minPrice").value;
    const priceTo = document.getElementById("maxPrice").value;
    const ratingFrom = document.getElementById("minRating").value;
    const ratingTo = document.getElementById("maxRating").value;
    const commodities = document.getElementsByName("commodity");
    let commoditiesObj = {};

    for (let i = 0; i < commodities.length; i++) {
        let commodity = commodities[i];
        commoditiesObj[commodity.value] = commodity.checked;
    }

    return new FilterState(location, checkin, checkout, priceFrom, priceTo, ratingFrom, ratingTo, type, nBeds, capacity, commoditiesObj)
}

function simplifyPrice(price) {

    if ((price / 1000000000).toFixed(2) >= 1)
        return (price / 1000000000).toFixed(2) + 'B';
    else if ((price / 1000000).toFixed(3) >= 1)
        return (price / 1000000).toFixed(3) + 'M';
    else if ((price / 1000).toFixed(3) >= 1)
        return (price / 1000).toFixed(3) + 'K';
    else
        return price;
}

function reservationNumberOfDays() {
    const checkin = document.getElementById("check_in").value
    const checkout = document.getElementById("check_out").value
    let nDays = 1;
    if (checkin != "" && checkout != "")
        nDays = Math.ceil(Math.abs(new Date(checkout.replace(/-/g, '/')) - new Date(checkin.replace(/-/g, '/'))) / (1000 * 60 * 60 * 24));
    return nDays;
}


function buildResidenceHTML(property){

    const descriptionTrimmed =property['description'].length > 180 ? property['description'].substr(0, 180) + "..." : property['description'];
    const ppdSimple = simplifyPrice(property['pricePerDay']);
    const totalPriceSimple = simplifyPrice(property['pricePerDay'] * reservationNumberOfDays());

    let resultHTML = "";

    property['rating'] = (property['rating'] == null ? '--' : (property['rating']/2).toFixed(2));

    let photoPath = property['photoPaths'].length == 0 ? "../resources/medium-none.jpg" : "../images/properties/medium/" + property['photoPaths'][0];

    if (property['rating'] == null)
        property['rating'] = '-- ';
    
    let typeStr = property['typeStr'];
    if (typeStr.length > 0)
        typeStr = typeStr.charAt(0).toUpperCase() + typeStr.slice(1);

    resultHTML = 
        '<a href="../pages/view_house.php?id=' + property['residenceID'] + '">' +
            '<section class="result">' +    
                '<section class="image">' +
                    '<img src="'+ photoPath+ '">' +
                '</section>' +  
                '<section class="info">' + 
                    '<h1 class="info_title">' + htmlEntities(property['title']) + '</h1>' +
                    '<h2 class="info_type_and_location">' + htmlEntities(typeStr) + ' &#8226 ' + property['address']  + '</h2>' +
                    '<p class="info_description">'  + htmlEntities(descriptionTrimmed) + '</p>' +
                    '<p class="total_price">' + htmlEntities(totalPriceSimple) + '</p>' +
                    '<p class="info_ppd">' + htmlEntities(ppdSimple) +'</p>' +
                    '<p class="info_score">'+ htmlEntities(property['rating']) +'</p>' +
                    '<p class="info_capacity">' + htmlEntities(property['capacity']) +'</p>' +
                    '<p class="info_bedrooms"> '+ htmlEntities(property['nBedrooms']) +' </p>' +
                '</section>' +
            '</section>' +
        '</a>'    

    return resultHTML;
}


function buildResidenceListHTML(properties){

    let innerHTML = "";

    for(let i = 0; i < properties.length;i++)
        innerHTML += buildResidenceHTML(properties[i]);

    return innerHTML;
}

function buildResultsHeaderHTML(results_header){

    const address = document.getElementById('location').value;  
    let h1 = document.createElement("h1");
    if (address.length > 0)
        h1.innerHTML = "Showing places near '" + htmlEntities(address) + "'" ;
    else
        h1.innerHTML = "Showing available places";
    results_header.replaceChild(h1,results_header.firstElementChild);

}

function buildResultCountHeader(results_header, count){

    let h2 = document.createElement("h2");
    h2.innerHTML = count + " results found" + (count > 0 ? " (Wow!)" : "") ;
  
    results_header.replaceChild(h2,results_header.firstElementChild.nextSibling);
  
    return results_header;
  
}

function updateSearchResults(event) {

    // Array that contains the properties that match the filters
    let response = JSON.parse(event.target.responseText);

    // same residences as before, no need to update
    if (JSON.stringify(response) === JSON.stringify(prev_residences_response)) {
        return;
    }

    prev_residences_response = JSON.parse(JSON.stringify(response));

    // update residences section
    let results_section = document.getElementById("results");
    results_section.innerHTML = buildResidenceListHTML(response);
    buildResultCountHeader(document.getElementById("results_header"),response.length);
    
    // update markers
    clearMarkers();
    addMarkers(response);
}


function filterUpdateHandler(coords, radius) {

    const filterState = getCurrentFilterState();
    
    // state did not change
    if (JSON.stringify(prev_filter_state) === JSON.stringify(filterState)) {
        return;
    }

    prev_filter_state = filterState;

    const location = {coords: coords, radius: radius};

    const encodedFilterData = encodeURIComponent(JSON.stringify(filterState));
    const encodedLocationData = encodeURIComponent(JSON.stringify(location));

    let request = new XMLHttpRequest();
    request.onload = updateSearchResults;
    request.open("get", "../ajax/residence_search.php?filter_data=" + encodedFilterData + '&location_data=' + encodedLocationData);
    request.send();

}

function updateURLFilters() {
    window.history.pushState(null, '', window.location.pathname + getCurrentFilterState().urlString());
}

document.getElementById("filter_button").addEventListener("click", updateURLFilters);