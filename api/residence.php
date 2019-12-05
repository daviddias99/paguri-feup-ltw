<?php

    include_once('./response_status.php');

    $request_method = $_SERVER['REQUEST_METHOD'];
    $accept_header = $_SERVER['HTTP_ACCEPT'];

    $response = array('status' => ResponseStatus::OK);

    if ($accept_header != 'application/json') {
        $response['status'] = ResponseStatus::BAD_REQUEST;
        echo json_encode($response);
        die();
    }

    include_once("../database/residence_queries.php");

    if($request_method == 'GET') {
        
        if(array_key_exists('id', $_GET)) {
            $info = getResidenceInfo($_GET['id']);
            
            if ($info == FALSE) 
                $response['status'] = ResponseStatus::NOT_FOUND;
            else
                $response['residence'] = $info;
        }
        else {
            $response['residences'] = getAllResidences();
        }

        echo json_encode($response);
    }

    else if ($request_method == 'POST') {
        echo 'post';
    }



?>