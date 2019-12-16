<?php
include_once('../database/user_queries.php');

    $image = $_FILES['image']['tmp_name'];

    $supportedFormats = array(IMAGETYPE_JPEG => '.jpg', IMAGETYPE_PNG => '.png');

    $imgType = exif_imagetype($image);
    $extension = $supportedFormats[$imgType];

    if ($extension == null)
        die();


    $photoID = sha1_file($image) . $extension;


    // Generate filenames for original, small and medium files
    $filename = "../images/properties/$photoID";

    // Move the uploaded file to its final destination
    move_uploaded_file($image, $filename);

