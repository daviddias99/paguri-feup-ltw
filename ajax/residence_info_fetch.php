<?php

    include_once('../database/residence_queries.php');

    $residenceID = json_decode($_GET['residence_id'], true);
    $residence = getResidenceInfo($residenceID);
    echo json_encode($residence);

?>