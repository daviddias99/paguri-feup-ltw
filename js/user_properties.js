'use strict'

function encodeForAjax(data) {
    return Object.keys(data).map(function (k) {
        return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&')
}


if (document.querySelectorAll(".remove_reservation")) {
    const clickHandler = function(event) {
        const id = event.target.parentNode.firstChild.nextSibling.value;
        const csrf = document.getElementById("csrf").value;
        const request = new XMLHttpRequest();

        request.addEventListener("load", function(newEvent) {
            if (request.status == 204) {
                event.target.parentNode.parentNode.remove();

                const numPlaces = document.querySelectorAll(".places_list_entry").length;

                if (! numPlaces) {
                    const paragraph = document.createElement("p");
                    paragraph.setAttribute("class", "empty_message");
                    paragraph.innerHTML = "No listed places yet.";
                    document.getElementById("places_list").appendChild(paragraph);

                }

            } else {
                // TODO: erro a remover a casa
                console.log("error");
            }
        });

        request.open("delete", "../api/residence.php?" + encodeForAjax({id: id, csrf: csrf}), true);
        request.setRequestHeader('Accept', 'application/json');
        request.send();
    }

    document.querySelectorAll(".remove_reservation").forEach(element => {
        element.onclick = clickHandler;
    });
}