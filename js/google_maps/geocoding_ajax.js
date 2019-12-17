'use strict'

// check if valid map page
getCurrentMapPage();

let api_key = "AIzaSyD0_ruPYuzA1ntPaautYqVqtjOT96oNLSE";
let search_radius = 2; //km

/* Variable used to store reference to return from setTimeout */
let address_timeout;
let center_changed_timeout;

function encodeForAjax(data) {
  return Object.keys(data).map(function(k){
    return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
  }).join('&')
}

function htmlEntities(str) {
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

function updateAddressInfo(event) {
    let response = JSON.parse(event.target.responseText);
    if(!checkMapsAPIResponse(response)) return;

    const best_result = response.results[0];
    const coords = {
      lat: best_result.geometry.location.lat,
      lng: best_result.geometry.location.lng
    }

    const current_page = getCurrentMapPage();
    switch(current_page) {
      case "search_results":
          updateURLFilters();
          filterUpdateHandler(coords, search_radius);
          buildResultsHeaderHTML(document.getElementById("results_header"));
          break;

      case "add_house":

          // parse info
          const title = document.getElementById("title_input").value || "New residence";
          const addressInfo = parseAddressInfo(best_result);

          const house_type_select = document.getElementById("house_type_input");

          const markerInfo = {
            title: title,
            position: coords,
            city: addressInfo.city,
            country: addressInfo.country,
            type: house_type_select.options[house_type_select.selectedIndex].value
          }

          // add marker
          clearMarkers();
          addMarker(coords, markerInfo);

          // update inputs values
          document.getElementById("latitude").value = htmlEntities(markerInfo.position.lat);
          document.getElementById("longitude").value = htmlEntities(markerInfo.position.lng);
          document.getElementById("city").value = htmlEntities(markerInfo.city || addressInfo.admin_area_level_1);
          document.getElementById("country").value = htmlEntities(markerInfo.country);
          break;
      default:
          throw new Error("Unknown current page.");
    }

    moveMap(coords);
    setMapZoom(16);
}

function updateInputsValues(event, latLng) {
    let response = JSON.parse(event.target.responseText);
    if(!checkMapsAPIResponse(response)) return;

    const best_result = response.results[0];
    const address = best_result.formatted_address;

    const current_page = getCurrentMapPage();
    switch(current_page) {
      case "search_results":

          document.getElementById("location").value = htmlEntities(address);
          updateURLFilters();
          buildResultsHeaderHTML(document.getElementById("results_header"));
          filterUpdateHandler(latLng, search_radius);
          break;

      case "add_house":

          const title = document.getElementById("title_input").value || "New residence";
          const addressInfo = parseAddressInfo(best_result);

          const house_type_select = document.getElementById("house_type_input");

          const markerInfo = {
            title: title,
            city: addressInfo.city,
            country: addressInfo.country,
            type: house_type_select.options[house_type_select.selectedIndex].value
          }

          addMarker(latLng, markerInfo);

          document.getElementById("latitude").value = latLng.lat();
          document.getElementById("longitude").value = latLng.lng();
          document.getElementById("city").value = htmlEntities(markerInfo.city || addressInfo.admin_area_level_1);
          document.getElementById("country").value = htmlEntities(markerInfo.country);
          document.getElementById("location").value = htmlEntities(address);


          moveMap(latLng);
          break;
      default:
          throw new Error("Unknown current page.");
    }
}

function checkMapsAPIResponse(response) {
  switch(response.status) {
    case "OK":
      if(response.results[0] == null) {
        return false;
      }
      break;

    case "ZERO_RESULTS":
      console.log("No results found");
      return false;

    case "INVALID_REQUEST":
      console.log("Invalid request");
      return false;

    default:
      return false;
  }
  return true;
}

function parseAddressInfo(geocoding_info) {
  let addressInfo = {
    city: "Unknown",
    country: "Unknown",
    admin_area_level_1: "Unknown"
  };

  geocoding_info.address_components.forEach(function (address_component) {
    if (address_component.types.find(type => type === "locality"))
      addressInfo.city = address_component.long_name;

    if (address_component.types.find(type => type === "country"))
      addressInfo.country = address_component.long_name;

    if (address_component.types.find(type => type === "administrative_area_level_1"))
      addressInfo.admin_area_level_1 = address_component.long_name;

  });

  return addressInfo;
}

function getAddressInfo(address) {
  let options = {
      key : api_key,
      address : address
  };
  let requestUrl = "https://maps.googleapis.com/maps/api/geocode/json?" + encodeForAjax(options);

  let request = new XMLHttpRequest();
  request.onload = updateAddressInfo;
  request.open("get", requestUrl);
  request.send();
}

function reverseGeocoding(latLng) {
  let options = {
    key : api_key,
    latlng : latLng.lat() + "," + latLng.lng()
  };

  let requestUrl = "https://maps.googleapis.com/maps/api/geocode/json?" + encodeForAjax(options);

  let request = new XMLHttpRequest();
  request.onload = event => updateInputsValues(event, latLng);
  request.open("get", requestUrl);
  request.send();
}

function handleAddressChangeClick(event) {

  if (current_page == "search_results") {
    buildResultsHeaderHTML(document.getElementById("results_header"));
  }

  const address = document.getElementById('location').value;
  if (address.length == 0) return;

  getAddressInfo(address);
}

function handleAddressChangeInput(event) {
  clearTimeout(address_timeout);

  const address = event.target.value;
  if (address.length == 0) return;
  address_timeout = setTimeout(() => getAddressInfo(address), 1000);
}

function handleAddressChangePan(event) {
  clearTimeout(center_changed_timeout);

  center_changed_timeout = setTimeout(function() {
    const coords = currentMapLocation();
    reverseGeocoding(coords);
  }, 500);
}

// install event listeners
const current_page = getCurrentMapPage();
switch(current_page) {
  case "search_results":
      setTimeout(handleAddressChangeClick, 500);
      document.getElementById("filter_button").addEventListener("click", handleAddressChangeClick);
      document.getElementById("location").addEventListener("input", handleAddressChangeInput);
      break;

  case "add_house":
      document.getElementById("location").addEventListener("input", handleAddressChangeInput);
      break;
  default:
      throw new Error("Unknown current page.");
}