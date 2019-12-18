<?php

    include_once('../templates/residence_availabilities.php');

    if(!isset($_GET['residence_id'])) {
        die();
    }

    $residenceID = json_decode($_GET['residence_id'], true);
    $availabilities = getAvailabilities($residenceID);
    echo json_encode($availabilities);

?>