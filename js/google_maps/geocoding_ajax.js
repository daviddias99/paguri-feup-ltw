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
    const best_result = response.results[0];
    switch(response.status) {
      case "OK":
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
        return;
    
      case "INVALID_REQUEST":
        console.log("Invalid request");
        return;

      default:
        return;
    }    

    if (path.search("search_results.php") != -1) {
      filterResidencesInRadius(coords, search_radius);
      filterMarkersInRadius(coords, search_radius);      
    }
    else if (path.search("add_house.php") != -1) {

      const title = document.getElementById("title").value || "New residence";
      const addressInfo = parseAddressInfo(best_result);
      
      const markerInfo = {
        title: title,
        position: coords,
        city: addressInfo.city,
        country: addressInfo.country
      }

      clearMarkers();
      addMarker(coords, title);
      
      // fill forms with city, country, lat, lng
      document.getElementById("latitude").value = markerInfo.position.lat;
      document.getElementById("longitude").value = markerInfo.position.lng;
      document.getElementById("city").value = markerInfo.city;
      document.getElementById("country").value = markerInfo.country;

    }
    moveMap(coords);    
    setMapZoom(18);

}

function parseAddressInfo(geocoding_info) {
  let addressInfo = {
    city: "",
    country: ""
  };

  geocoding_info.address_components.forEach(function (address_component) {
    if (address_component.types.find(type => type === "locality"))
    addressInfo.city = address_component.long_name;

    if (address_component.types.find(type => type === "country"))
    addressInfo.country = address_component.long_name;
    
  });

  return addressInfo;
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

function handleAddressChangeClick(event) {

  let address = document.getElementById('location').value;  
  buildResultsHeaderHTML(document.getElementById("results_header"),address);

  if (address.length == 0) return;
  getAddressInfo(address);
}

function handleAddressChangeInput(event) {
  clearTimeout(address_timeout);

  const address = event.target.value;
  if (address.length == 0) return;
  address_timeout = setTimeout(() => getAddressInfo(address), 1000);
}

const path = window.location.pathname;
if (path.search("search_results.php") != -1) {
  handleAddressChange();
  document.getElementById("filter_button").addEventListener("click", handleAddressChangeClick);
  document.getElementById("search_button").addEventListener("click", handleAddressChangeClick);
}
else if (path.search("add_house.php") != -1) {
    document.getElementById("location").addEventListener("input", handleAddressChangeInput);
}