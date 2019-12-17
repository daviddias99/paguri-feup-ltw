let checkinS = document.getElementById('checkin_input');
let checkoutS = document.getElementById('checkout_input');

checkinS.addEventListener("change", handleDateChange);
checkoutS.addEventListener("change", handleDateChange);


/**
 * Display the price of the rental. (number of days and total price)
 * 
 * @param {Date} checkin    date of the checkin
 * @param {Date} checkout   date of the checkout
 */
function priceDisplayHTML(checkin,checkout) {
    let result = "";
    let pricePerDay = document.getElementById('pricePerDay').value;

    // Calculate how many days are between the two dates
    const oneDay = 24 * 60 * 60 * 1000; 
    const firstDate = new Date(checkin);
    const secondDate = new Date(checkout);
    const diffDays = Math.round(Math.abs((firstDate - secondDate) / oneDay)) + 1;

    // Build HTML
    result +=
        '<h3> Total price: </h3>' +
        '<p>' + diffDays + ' days x €' + pricePerDay + ' = €' + diffDays * pricePerDay + '</p>';

        return result;
}

/**
 * Build an error message tobe displayed to the user when the dates are not valid
 */
function availabilityErrorDisplayHTML() {
    let result = "";
   
    result +=
        '<h4> We\' sorry but this residence you\'re looking for is not available at the desired time.</h4>';

        return result;
}

/**
 * On date change, verify if the given dates correspond to a period that is available for rental. If so, display the total price and enable the rent now button. Otherwise
 * display an error message and disable the ren now button.
 */
function handleAvailabilities() {

    let checkin = document.getElementById('checkin_input').value;
    let checkout = document.getElementById('checkout_input').value;
    let validDates = false;
    let availabilities = JSON.parse(this.responseText);

    // Check if the dates input by the user are within any availability
    for (let i = 0; i < availabilities.length; i++) {

        let availability = availabilities[i];

        if (checkin >= availability['startDate']
            && checkout <= availability['endDate']
        ) {

            validDates = true;
            break;
        }

    }


    let display_price = document.getElementById('display_price');
    let button = document.getElementById('rent_submit_button');

    // Draw the correct message(either price or error message) and enable/disable the "Rent Now" button
    if (validDates) {

        display_price.innerHTML = priceDisplayHTML(checkin,checkout);
        
        button.className = "button enabled";
        button.disabled= false;
    }
    else {
        display_price.innerHTML = availabilityErrorDisplayHTML();
    
        button.className = "button disabled";
        button.disabled= true;
    }


}

/**
 * Handle the date change event. Fetches the residence availabilities.
 */
function handleDateChange() {

    let request = new XMLHttpRequest();

    let id = document.getElementById('residenceID').value;

    request.onload = handleAvailabilities;
    request.open("get", "../ajax/residence_availabilites_fetch.php?residence_id=" + id);
    request.send();
}