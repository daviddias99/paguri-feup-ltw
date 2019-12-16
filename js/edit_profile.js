'use strict'

document.querySelector(".choose_photo").onchange = function (event) {
    const reader = new FileReader();

    reader.onload = function () {
        const preview = document.getElementById('photo_preview');

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

        img.src = reader.result;
    }

    document.getElementById("remove_photo_input").checked = false;
    reader.readAsDataURL(event.target.files[0]);
}

document.getElementById("remove_photo").onclick = function (event) {
    document.getElementById("photo_preview").src = "../images/users/thumbnails_medium/default.jpg";
    document.getElementById("choose_photo_input").value = null;
    document.getElementById("remove_photo_input").checked = true;
}