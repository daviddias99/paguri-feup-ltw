<?php
include_once('../database/residence_queries.php');

    $imageID = $_POST['imageID'];

    $path = getResidencePhotoPath($imageID);

    removeResidencePhoto($imageID);

    unlink("../images/properties/originals/$path");
    unlink("../images/properties/big/$path");
    unlink("../images/properties/medium/$path");
    unlink("../images/properties/small/$path");