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

function getCurentFilterState() {

    let nBeds = document.getElementById("nBeds").value
    let capacity = document.getElementById("capacity").value
    let dateFrom = document.getElementById("check_in").value
    let dateTo = document.getElementById("check_out").value
    let type = document.getElementById("housing_type").value
    let priceFrom = document.getElementById("minPrice").value
    let priceTo = document.getElementById("maxPrice").value
    let ratingFrom = document.getElementById("minPrice").value
    let ratingTo = document.getElementById("maxPrice").value
    let commodities = document.getElementsByName("commodity")
    let commoditiesObj = {};

    for (let i = 0; i < commodities.length; i++) {
        let commodity = commodities[i];
        commoditiesObj[commodity.value] = commodity.checked;
    }

    return new FilterState(dateFrom, dateTo, priceFrom, priceTo, ratingFrom, ratingTo, type, nBeds, capacity, commoditiesObj)
}


function updateSearchResults() {

    let response = JSON.parse(this.responseText);

}


function filterUpdateHandler() {

    var filterState = getCurentFilterState();

    let request = new XMLHttpRequest();
    let encodedData = encodeURIComponent(JSON.stringify(filterState));

    request.onload = updateSearchResults;
    request.open("get", "../ajax/residence_search.php?filter_data=" + encodedData);
    request.send();

}


document.getElementById("filter_button").addEventListener("click", filterUpdateHandler);