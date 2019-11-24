'use strict'

let api_key = "AIzaSyD0_ruPYuzA1ntPaautYqVqtjOT96oNLSE";
let latitudeBar = document.getElementById("latitude_bar");
let longitudeBar = document.getElementById("longitude_bar");

/* Variable used to store reference to return from setTimeout */
let address_timeout;

function encodeForAjax(data) {
    return Object.keys(data).map(function(k){
      return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&')
  }

function updateAddressInfo(event) {
   let response = JSON.parse(event.target.responseText);

   // TODO deal with many results found

   switch(response.status) {
     case "OK":
        latitudeBar.value = response.results[0].geometry.location.lat;
        longitudeBar.value = response.results[0].geometry.location.lng;
        break;
    
      case "ZERO_RESULTS":
        console.log("No results found");
        latitudeBar.value = "";
        longitudeBar.value = "";
        break;
    
      case "INVALID_REQUEST":
        console.log("Invalid request");
        latitudeBar.value = "";
        longitudeBar.value = "";
        break;

      default:
        latitudeBar.value = "";
        longitudeBar.value = "";   
   }
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

function handleAddressChange(event) {

  clearTimeout(address_timeout);

  let address = event.target.value;
  if (address.length == 0) return;
  address_timeout = setTimeout(() => getAddressInfo(address), 2000);
}

let addressBar = document.getElementById("address_bar");
addressBar.addEventListener("input", handleAddressChange);