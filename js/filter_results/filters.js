class FilterState {

    constructor(dateFrom,dateTo, priceFrom,priceTo,ratingFrom, ratingTo,type,nBeds,capacity,commodities){

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

function getCurentFilterState(){

    let nBeds = document.getElementById("nBeds").value
    let capacity = document.getElementById("capacity").value
    let dateFrom = document.getElementById("check_in").value
    let dateTo = document.getElementById("check_out").value
    let type = document.getElementById("housing_type").value
    let priceFrom = document.getElementById("minPrice").value
    let priceTo = document.getElementById("maxPrice").value
    let ratingFrom = document.getElementById("minPrice").value
    let ratingTo = document.getElementById("maxPrice").value

    return new FilterState(dateFrom,dateTo,priceFrom,priceTo,ratingFrom,ratingTo,type,nBeds,capacity,null)
}


function filterUpdateHandler(){

    var filterState = getCurentFilterState();

    console.log(filterState);
}


document.getElementById("filter_button").addEventListener("click",filterUpdateHandler);