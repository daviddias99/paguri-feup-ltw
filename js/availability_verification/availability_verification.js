let checkinS = document.getElementById('checkin_input');
let checkoutS = document.getElementById('checkout_input');

checkinS.addEventListener("change", handleDateChange);
checkoutS.addEventListener("change", handleDateChange);


function priceDisplayHTML(checkin,checkout) {
    let result = "";
    let pricePerDay = document.getElementById('pricePerDay').value;

    const oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
    const firstDate = new Date(checkin);
    const secondDate = new Date(checkout);

    const diffDays = Math.round(Math.abs((firstDate - secondDate) / oneDay)) + 1;

    result +=
        '<h3> Total price: </h3>' +
        '<p>' + diffDays + ' days x €' + pricePerDay + ' = €' + diffDays * pricePerDay + '</p>';

        return result;
}

function availabilityErrorDisplayHTML() {
    let result = "";
   
    result +=
        '<h4> We\' sorry but this residence you\'re looking for is not available at the desired time.</h4>';

        return result;
}

function handleAvailabilities() {


    let checkin = document.getElementById('checkin_input').value;
    let checkout = document.getElementById('checkout_input').value;
    let validDates = false;

    let availabilities = JSON.parse(this.responseText);

    for (let i = 0; i < availabilities.length; i++) {

        let availability = availabilities[i];


        if (checkin >= availability['startDate']
            && checkout <= availability['endDate']
        ) {

            validDates = true;
            break;
        }

    }

    if (validDates) {
        let display_price = document.getElementById('display_price');
        display_price.innerHTML = priceDisplayHTML(checkin,checkout);
    }
    else {
        let display_price = document.getElementById('display_price');
        display_price.innerHTML = availabilityErrorDisplayHTML();
    }


}


function handleDateChange() {

    let request = new XMLHttpRequest();

    let id = document.getElementById('residenceID').value;

    request.onload = handleAvailabilities;
    request.open("get", "../ajax/residence_availabilites_fetch.php?residence_id=" + id);
    request.send();
}