<?php
include_once('../database/residence_queries.php');

    $residenceID = $_POST['id'];
    $image = $_FILES['image']['tmp_name'];

    $supportedFormats = array(IMAGETYPE_JPEG => '.jpg', IMAGETYPE_PNG => '.png');

    $imgType = exif_imagetype($image);
    $extension = $supportedFormats[$imgType];

    if ($extension == null)
        die();


    $photoID = addResidencePhoto($residenceID, sha1_file($image) . $extension);


    // Generate filenames for original, small and medium files
    $filename = "../images/properties/originals/$photoID";

    // Move the uploaded file to its final destination
    move_uploaded_file($image, $filename);

