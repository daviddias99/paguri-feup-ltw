let checkin = document.getElementById('checkin_input');
let checkout = document.getElementById('checkout_input');

checkin.addEventListener("change", handleDates.bind(this, 'in'));
checkout.addEventListener("change", handleDates.bind(this, 'out'));

function handleDates(changed) {

    let checkin = document.getElementById('checkin_input');
    let checkout = document.getElementById('checkout_input');

    let today = new Date().toJSON().slice(0, 10)

    if(today > checkin.value){
        checkin.value = today;
    }
    
    if (checkin.value > checkout.value) {

        if (changed == 'in') {

            if(today > checkin.value){
                checkin.value = today;
            }


            checkout.value = checkin.value;
        }
        else if (changed == 'out') {

            if(today > checkout.value){
                checkout.value = today;
            }

            checkin.value = checkout.value;
        }

    }


}

