<?php
    include_once('../includes/config.php');
    include_once('../database/residence_queries.php');

    if(!isset($_SESSION['userID']) or !isset($_POST['imageID'])) die();

    $imageID = $_POST['imageID'];
    $residenceInfo = getResidenceOfPhoto($imageID);

    if ($residenceInfo['owner'] != $_SESSION['userID']) die();

    $path = getResidencePhotoPath($imageID);

    removeResidencePhoto($imageID);

    unlink("../images/properties/originals/$path");
    unlink("../images/properties/big/$path");
    unlink("../images/properties/medium/$path");
    unlink("../images/properties/small/$path");