<?php

    include_once("../database/map_queries.php");

    $info = getAllResidencesMapInfo();

    echo json_encode($info);

?>