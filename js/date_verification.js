let checkin = document.getElementById('checkin_input');
let checkout = document.getElementById('checkout_input');

checkin.addEventListener("change", handleDates.bind(this, 'in'));
checkout.addEventListener("change", handleDates.bind(this, 'out'));

/**
 * Handles date change correcting the values in order to avoid invalid inputs. This is the behaviour of the function:
 * 
 * - If the value of a date is changed to be less than the current date, then the value is changed to be the current date.
 * - If the checkin value is changed to be larger than the checkout value, than the value of checkout is changed to be equal to checkin
 * - If the checkout value is changed to be less than the checkin value, than the checkin value is changed to be equal to checkout
 * 
 * @param {String} changed which date changed
 */
function handleDates(changed) {

    let checkin = document.getElementById('checkin_input');
    let checkout = document.getElementById('checkout_input');

    // Get the current date
    let today = new Date().toJSON().slice(0, 10)

    // Check if the check in date is less than the current date
    if(today > checkin.value){
        checkin.value = today;
    }
    
    // Change the values according to the described rules
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

