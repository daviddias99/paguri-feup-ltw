
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

    property['rating'] = (property['rating'] == null ? '--' : (property['rating']/2).toFixed(2));

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
        '<h2 class="info_type_and_location">' + property['type'] + ' &#8226 ' + property['address']  + '</h2>' +
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


function updateResidenceSummaryDisplay(){

    let result_section = document.getElementById("residence_summary");

    let response = JSON.parse(this.responseText);
    result_section.innerHTML += buildResidenceHTML(response);

}


function drawResidenceSummary(id){

    let request = new XMLHttpRequest();
    request.onload = updateResidenceSummaryDisplay;
    request.open("get", "../ajax/residence_info_fetch.php?residence_id=" + id);
    request.send();
}

drawResidenceSummary(document.getElementById('residenceID').value);