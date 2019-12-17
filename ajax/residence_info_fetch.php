<?php


    include_once('../database/residence_queries.php');

    $residenceID = json_decode($_GET['residence_id'], true);
    $residence = getResidenceInfo($residenceID);
    $paths = [];
    $photos = getResidencePhotos($residence['residenceID']);
    foreach($photos as $photo){
    
        array_push($paths,$photo['filepath']);
    }
    $residence['photoPaths'] = $paths;

    echo json_encode($residence);

?>