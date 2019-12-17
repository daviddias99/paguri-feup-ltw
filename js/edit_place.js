'use strict'

function encodeForAjax(data) {
    return Object.keys(data).map(function (k) {
        return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&')
}

function htmlEntities(str) {
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

const images = {};
const removedImages = [];
let lastImageID = 0;

document.getElementById("submit_button").onclick = function (event) {
    event.preventDefault();

    const id = document.getElementById("place_id").value;
    const owner = document.getElementById("user_id").value;
    const title = document.getElementById("title_input").value;
    const type = document.getElementById("type").value;
    const description = document.getElementById("description_input").value;
    const capacity = document.getElementById("capacity").value;
    const numBeds = document.getElementById("num_beds").value;
    const numBedrooms = document.getElementById("num_bedrooms").value;
    const numBathrooms = document.getElementById("num_bathrooms").value;
    const location = document.getElementById("location").value;
    const city = document.getElementById("city").value;
    const country = document.getElementById("country").value;
    const latitude = document.getElementById("latitude").value;
    const longitude = document.getElementById("longitude").value;
    const pricePerDay = document.getElementById("price").value;
    const commodities = document.getElementById("commodities").value;


    const request = new XMLHttpRequest();

    request.open("put", "../api/residence.php?" + encodeForAjax({
        id: id,
        owner: owner,
        title: title,
        description: description,
        pricePerDay: pricePerDay,
        capacity: capacity,
        nBathrooms: numBathrooms,
        nBeds: numBeds,
        nBedrooms: numBedrooms,
        type: type,
        address: location,
        city: city,
        country: country,
        latitude: latitude,
        longitude: longitude,
        pricePerDay: pricePerDay,
        commodities: commodities
    }), true);
    request.setRequestHeader('Accept', 'application/json');

    request.send();

    Object.keys(images).forEach(key => {
        send(id, images[key]);
    });

    removedImages.forEach(image => {
        remove(image);
    });

    const redirectForm = document.createElement('form');
    redirectForm.method = 'post';
    redirectForm.action = '../pages/edit_place.php';

    const idInput = document.createElement('input');
    idInput.type = 'hidden';
    idInput.name = 'id';
    idInput.value = htmlEntities(id);

    redirectForm.appendChild(idInput);
    document.body.appendChild(redirectForm);

    redirectForm.submit();
};

function send(id, image) {
    let formData = new FormData();
    const request = new XMLHttpRequest();

    formData.set('image', image);
    formData.set('id', id);

    request.open("POST", '../actions/action_add_house_image.php');
    request.send(formData);
}

function remove(imageID) {
    const request = new XMLHttpRequest();

    request.open("POST", "../actions/action_remove_house_image.php", true);
    request.setRequestHeader('Accept', 'application/json');
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
    request.send(encodeForAjax({imageID: imageID }));
}




const card = document.getElementById("edit_place");



document.querySelector(".choose_photo").onchange = function (event) {

    for (let i = 0; i < event.target.files.length; i++) {
        const reader = new FileReader();

        images[++lastImageID] = event.target.files[i];

        reader.onload = function () {

            const section = document.createElement('section');
            section.setAttribute('class', 'new_image_preview');
            section.setAttribute('id', lastImageID);

            const preview = document.createElement('img');

            const del = document.createElement('span');
            del.setAttribute('class', 'remove_image fas fa-trash-alt');
            del.onclick = removeImage;

            section.appendChild(preview);
            section.appendChild(del);
            card.appendChild(section);


            const img = document.createElement('img');

            img.onload = function () {

                const dstHeight = 256;
                const dstWidth = 256;
                const dstX = 0;
                const dstY = 0;

                const square = Math.min(this.height, this.width);
                const srcHeight = square;
                const srcWidth = square;
                const srcX = this.width > square ? (this.width - square) / 2 : 0;
                const srcY = this.height > square ? (this.height - square) / 2 : 0;

                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');

                canvas.width = dstHeight;
                canvas.height = dstWidth;

                ctx.drawImage(this, srcX, srcY, srcWidth, srcHeight, dstX, dstY, dstWidth, dstHeight);

                preview.src = canvas.toDataURL();
            }

            img.src = this.result;
        }

        reader.readAsDataURL(event.target.files[i]);
    }
}

document.querySelectorAll(".image_preview .remove_image").forEach(element => {
    element.onclick = (event) => {
        event.target.parentNode.remove();
        removedImages.push(event.target.parentNode.id);
        console.log("nao esta a remover");
    }
});

function removeImage(event) {
    event.target.parentNode.remove();
    delete images[event.target.parentNode.id];
}