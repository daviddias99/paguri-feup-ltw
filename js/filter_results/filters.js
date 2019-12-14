'use strict'

class FilterState {

    constructor(dateFrom, dateTo, priceFrom, priceTo, ratingFrom, ratingTo, type, nBeds, capacity, commodities) {

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

}

function getCurrentFilterState() {

    let nBeds = document.getElementById("nBeds").value
    let capacity = document.getElementById("capacity").value
    let dateFrom = document.getElementById("check_in").value
    let dateTo = document.getElementById("check_out").value
    let type = document.getElementById("housing_type").value
    let priceFrom = document.getElementById("minPrice").value
    let priceTo = document.getElementById("maxPrice").value
    let ratingFrom = document.getElementById("minRating").value
    let ratingTo = document.getElementById("maxRating").value
    let commodities = document.getElementsByName("commodity")
    let commoditiesObj = {};

    for (let i = 0; i < commodities.length; i++) {
        let commodity = commodities[i];
        commoditiesObj[commodity.value] = commodity.checked;
    }

    return new FilterState(dateFrom, dateTo, priceFrom, priceTo, ratingFrom, ratingTo, type, nBeds, capacity, commoditiesObj)
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
    console.log(response);

    buildResultCountHeader(document.getElementById("results_header"),response.length);
    results_section.innerHTML =  buildResidenceListHTML(response);
}


function filterUpdateHandler(coords, radius) {

    var filterState = getCurrentFilterState();
    var location = {coords: coords, radius: radius};

    console.log(JSON.stringify(filterState));

    let encodedFilterData = encodeURIComponent(JSON.stringify(filterState));
    let encodedLocationData = encodeURIComponent(JSON.stringify(location));

    let request = new XMLHttpRequest();
    request.onload = updateSearchResults;
    request.open("get", "../ajax/residence_search.php?filter_data=" + encodedFilterData + '&location_data=' + encodedLocationData);
    request.send();

}
