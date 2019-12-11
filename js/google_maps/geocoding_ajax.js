'use strict'

let api_key = "AIzaSyD0_ruPYuzA1ntPaautYqVqtjOT96oNLSE";
let search_radius = 2; //km

/* Variable used to store reference to return from setTimeout */
let address_timeout;

function encodeForAjax(data) {
    return Object.keys(data).map(function(k){
      return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&')
  }

function updateAddressInfo(event) {
    let response = JSON.parse(event.target.responseText);

    // TODO deal with many results found?

    let coords = null;
    switch(response.status) {
      case "OK":
        let best_result = response.results[0];

        if(best_result == null) {
          return;
        }

        coords = {
          lat: best_result.geometry.location.lat,
          lng: best_result.geometry.location.lng
        }
        break;
    
      case "ZERO_RESULTS":
        console.log("No results found");
        break;
    
      case "INVALID_REQUEST":
        console.log("Invalid request");
        break;

      default:
    }

    filterResidencesInRadius(coords,search_radius);
    filterMarkersInRadius(coords, search_radius);
    moveMap(coords);    
    setMapZoom(18);

}

function getAddressInfo(address) {
  let options = {
      "key" : api_key,
      "address" : address
  };    
  let requestUrl = "https://maps.googleapis.com/maps/api/geocode/json?" + encodeForAjax(options);

  let request = new XMLHttpRequest();
  request.onload = updateAddressInfo;
  request.open("get", requestUrl);
  request.send();
}


function buildResultsHeaderHTML(results_header,address){

  let h1 = document.createElement("h1");
  h1.innerHTML = "Showing places near '" + address + "'" ;

  results_header.replaceChild(h1,results_header.firstElementChild);

}

function handleAddressChange(event) {

  let address = document.getElementById('location').value;  
  buildResultsHeaderHTML(document.getElementById("results_header"),address);

  if (address.length == 0) return;
  address_timeout = setTimeout(() => getAddressInfo(address), 1000);

}

handleAddressChange();
document.getElementById("filter_button").addEventListener("click", handleAddressChange);
document.getElementById("search_button").addEventListener("click", handleAddressChange);