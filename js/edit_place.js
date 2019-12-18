'use strict'

function encodeForAjax(data) {
    return Object.keys(data).map(function (k) {
        return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&')
}

const images = {};
const removedImages = [];
let lastImageID = 0;
let numSent = 0;

document.getElementById("submit_button").onclick = function (event) {

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
    const csrf = document.getElementById("csrf").value;

    if (!id || !owner || !title || !location || !capacity ||
        !numBeds || !numBedrooms || !numBathrooms || !city || !country ||
        !pricePerDay || !latitude || !longitude) return;

    event.preventDefault();

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
        commodities: commodities,
        csrf: csrf
    }), true);
    request.setRequestHeader('Accept', 'application/json');

    request.send();

    Object.keys(images).forEach(key => {
        send(id, images[key]);
    });

    removedImages.forEach(image => {
        remove(image);
    });


    function onLoad() {
        numSent++;
        if (numSent == Object.keys(images).length + removedImages.length) {
            window.location.href = "../pages/view_house.php?id=" + id;
        }
    }

    function send(residence, image) {
        let formData = new FormData();
        const request = new XMLHttpRequest();

        formData.set('image', image);
        formData.set('id', residence);

        request.open("POST", '../actions/action_add_house_image.php');
        request.send(formData);

        request.onload = onLoad;
    }

    function remove(imageID) {
        const request = new XMLHttpRequest();

        request.open("POST", "../actions/action_remove_house_image.php", true);
        request.setRequestHeader('Accept', 'application/json');
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
        request.send(encodeForAjax({ imageID: imageID }));

        request.onload = onLoad;
    }
};




const card = document.getElementById("edit_place");
const imageSection = document.getElementById("edit_place_images");


document.querySelector(".choose_photo").onchange = function (event) {

    for (let i = 0; i < event.target.files.length; i++) {
        const reader = new FileReader();

        const currentID = ++lastImageID;

        if (event.target.files[i].size > 1073741824 || ! (/^image\/[jpeg|png]/g.test(event.target.files[i].type)))
             break;

        images[currentID] = event.target.files[i];

        reader.onload = function () {

            const img = document.createElement('img');

            img.onload = function () {
                const width = this.width;
                const height = this.height;

                const dstHeight = 180;
                const dstWidth = 300;
                const dstX = 0;
                const dstY = 0;

                const widthRatio = width / dstWidth;
                const heightRatio = height / dstHeight;
                const ratio = heightRatio > widthRatio ? widthRatio : heightRatio;

                const srcWidth = width * ratio / widthRatio;
                const srcHeight = height * ratio / heightRatio;

                const srcX = heightRatio >= widthRatio ? 0 : (width - srcWidth) / 2;
                const srcY = widthRatio >= heightRatio ? 0 : (height - srcHeight) / 2;

                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');

                canvas.width = dstWidth;
                canvas.height = dstHeight;

                ctx.drawImage(this, srcX, srcY, srcWidth, srcHeight, dstX, dstY, dstWidth, dstHeight);

                const section = document.createElement('section');
                section.setAttribute('class', 'new_image_preview');
                section.setAttribute('id', currentID);

                const preview = document.createElement('img');

                const del = document.createElement('div');
                del.setAttribute('class', 'remove_image fas fa-trash-alt');
                del.onclick = removeImage;

                section.appendChild(preview);
                section.appendChild(del);
                imageSection.insertBefore(section, document.querySelector("#edit_place_images .choose_photo"));

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
    }
});

function removeImage(event) {
    event.target.parentNode.remove();
    delete images[event.target.parentNode.id];
}