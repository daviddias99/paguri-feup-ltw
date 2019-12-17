<?php

    include_once('../templates/residence_availabilities.php');

    $residenceID = json_decode($_GET['residence_id'], true);
    $availabilities = getAvailabilities($residenceID);
    echo json_encode($availabilities);

?>