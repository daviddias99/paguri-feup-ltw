<?php

    $request_method = $_SERVER['REQUEST_METHOD'];
    $accept_header = $_SERVER['HTTP_ACCEPT'];

    if ($accept_header != 'application/json') {
        echo 'Only JSON is supported.';
        die();
        // send error in api or html code 500
    }

    include_once("../database/residence_queries.php");

    if($request_method == 'GET') {
        $info = getAllResidences();

        echo json_encode($info);
        //echo json_encode(array('residences' => $info));
    }

    else if ($request_method == 'POST') {
        echo 'post';
    }

?>