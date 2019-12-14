'use strict'

class FilterState {

    constructor(location, dateFrom, dateTo, priceFrom, priceTo, ratingFrom, ratingTo, type, nBeds, capacity, commodities) {

        this.location = location;
        this.dateFrom = dateFrom;
        this.dateTo = dateTo;
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

        if (this.location != "") urlStr += "location=" + this.location + "&";
        if (this.dateFrom != "") urlStr += "checkin=" + this.dateFrom + "&";
        if (this.dateTo != "") urlStr += "checkout=" + this.dateTo + "&";
        if (this.priceFrom != "") urlStr += "min_price=" + this.priceFrom + "&";
        if (this.priceTo != "") urlStr += "max_price=" + this.priceTo + "&";
        if (this.ratingFrom != "") urlStr += "min_rating=" + this.ratingFrom + "&";
        if (this.ratingTo != "") urlStr += "max_rating=" + this.ratingTo + "&";
        if (this.type != "") urlStr += "type=" + this.type + "&";
        if (this.nBeds != "") urlStr += "min_beds=" + this.nBeds + "&";
        if (this.capacity != "") urlStr += "guest_count=" + this.capacity + "&";

        if (Object.keys(this.commodities).length > 0) {
            let first = true;
            for (const commodity in this.commodities) {
                if (this.commodities[commodity] == true) {
                    if (first) {
                        urlStr += "commodities=";
                        first = false;
                    }
                    urlStr += commodity + ","
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
    const nBeds = document.getElementById("nBeds").value
    const capacity = document.getElementById("capacity").value
    const dateFrom = document.getElementById("check_in").value
    const dateTo = document.getElementById("check_out").value
    const type = document.getElementById("housing_type").value
    const priceFrom = document.getElementById("minPrice").value
    const priceTo = document.getElementById("maxPrice").value
    const ratingFrom = document.getElementById("minRating").value
    const ratingTo = document.getElementById("maxRating").value
    const commodities = document.getElementsByName("commodity")
    let commoditiesObj = {};

    for (let i = 0; i < commodities.length; i++) {
        let commodity = commodities[i];
        commoditiesObj[commodity.value] = commodity.checked;
    }

    return new FilterState(location, dateFrom, dateTo, priceFrom, priceTo, ratingFrom, ratingTo, type, nBeds, capacity, commoditiesObj)
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


function buildResidenceHTML(property){

    let descriptionTrimmed =property['description'].length > 180 ? property['description'].substr(0, 180) + "..." : property['description'];
    let priceSimple = simplifyPrice(property['pricePerDay']);

    let resultHTML = "";

    if (property['rating'] == null)
        property['rating'] = '-- ';

    resultHTML = 
        '<a href="../pages/view_house.php?id=' + property['residenceID'] + '">' +
        '<section class="result">' +    
        '<section class="image">' +
        '<img src="../resources/house_image_test.jpeg">' +
        '</section>' +  
        '<section class="info">' + 
        '<h1 class="info_title">' + property['title'] + '</h1>' +
        '<h2 class="info_type_and_location">' + property['typeStr'] + ' &#8226 ' + property['address']  + '</h2>' +
        '<p class="info_description">'  + descriptionTrimmed + '</p>' +
        '<p class="info_ppd">' + priceSimple +'</p>' +
        '<p class="info_score">'+ property['rating']+'</p>' +
        '<p class="info_capacity">' + property['capacity']+'</p>' +
        '<p class="info_bedrooms"> '+ property['nBedrooms']+' </p>' +
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

function buildResultsHeaderHTML(results_header, address){

    let h1 = document.createElement("h1");
    if (address.length > 0)
        h1.innerHTML = "Showing places near '" + address + "'" ;
    else
        h1.innerHTML = "Showing available places";
    results_header.replaceChild(h1,results_header.firstElementChild);

}

function buildResultCountHeader(results_header,count){

    let h2 = document.createElement("h2");
    h2.innerHTML = count + " results found (Wow!)" ;
  
    results_header.replaceChild(h2,results_header.firstElementChild.nextSibling);
  
    return results_header;
  
  }

function updateSearchResults(event) {

    let results_section = document.getElementById("results");
    results_section.innerHTML = "";

    // Array that contains the properties that match the filters
    let response = JSON.parse(event.target.responseText);

    buildResultCountHeader(document.getElementById("results_header"),response.length);
    results_section.innerHTML =  buildResidenceListHTML(response);
}


function filterUpdateHandler(coords, radius) {

    const filterState = getCurrentFilterState();
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