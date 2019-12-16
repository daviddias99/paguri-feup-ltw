<?php
include_once('../database/residence_queries.php');

    $imageID = $_POST['imageID'];

    $path = getResidencePhotoPath($imageID);

    removeResidencePhoto($imageID);

    unlink("../images/properties/originals/$path");