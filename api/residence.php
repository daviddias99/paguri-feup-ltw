<?php

    // TODO authentication!!! api key ?
    // shouldnt db insert fail when constrainsts fail?
    // implement delete and put?
    // error field in response when succesfull?

    include_once('./response_status.php');

    $request_method = $_SERVER['REQUEST_METHOD'];
    $accept_header = $_SERVER['HTTP_ACCEPT'];

    $response = array();

    if ($accept_header != 'application/json') {
        $response['error'] = 'Accept header must be application/json.';
        http_response_code(ResponseStatus::BAD_REQUEST);
        echo json_encode($response);
        die();
    }

    include_once("../database/residence_queries.php");

    if($request_method == 'GET') {
        
        if(array_key_exists('id', $_GET)) {
            $info = getResidenceInfo($_GET['id']);
            
            if ($info == FALSE) {
                $response['error'] = 'Did not find residence with given id.';
                http_response_code(ResponseStatus::NOT_FOUND);
            }
            else {
                $response['residence'] = $info;
            }
        }
        else {
            $response['residences'] = getAllResidences();
        }
    }

    else if ($request_method == 'POST') {        
        
        $needed_keys = [
            'owner', 
            'title', 
            'description', 
            'pricePerDay',
            'capacity',
            'nBedrooms',
            'nBathrooms',
            'nBeds',
            'type',
            'address',
            'city',
            'country',
            'latitude',
            'longitude'
        ];

        // check if all values are present
        if(!array_keys_exist($_POST, $needed_keys)) {
            $response['error'] = 'Missing values in request body.';
            http_response_code(ResponseStatus::BAD_REQUEST);
            echo json_encode($response);
            die();
        }

        //TODO check given values are not empty

        // check given type is valid
        $residence_types = getResidenceTypes();
        $valid_type = FALSE;
        foreach($residence_types as $type) {
            if ($type['name'] == $_POST['type']) {
                $valid_type = TRUE;
                $_POST['type'] = $type['residenceTypeID'];
                break;
            }
        }
        if(!$valid_type){
            $response['error'] = 'Residence type does not exist.';
            http_response_code(ResponseStatus::BAD_REQUEST);
            echo json_encode($response);
            die();
        }

        $lastInsertId = createResidence($_POST);
        if ($lastInsertId == FALSE) {
            $response['error'] = 'Error inserting new residence.';
            http_response_code(ResponseStatus::BAD_REQUEST);
        }

        http_response_code(ResponseStatus::CREATED);
        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; // are theses variables controllable by the user???
        header("Location: $actual_link?id=$lastInsertId");
    }

    else if ($request_method == 'PUT') {

        if(!array_key_exists('id', $_GET)) {
            $response['error'] = 'Residence ID must be specified.';
            http_response_code(ResponseStatus::METHOD_NOT_ALLOWED);
            echo json_encode($response);
            die();
        }

    }

    else if ($request_method == 'DELETE') {

        if(!array_key_exists('id', $_GET)) {
            $response['error'] = 'Residence ID must be specified.';
            http_response_code(ResponseStatus::METHOD_NOT_ALLOWED);
            echo json_encode($response);
            die();
        }

        $res = deleteResidence($_GET['id']);
        if ($res == FALSE) {
            $response['error'] = 'Residence not found.';
            http_response_code(ResponseStatus::NOT_FOUND);
            echo json_encode($response);
            die();
        }

        $response['residence'] = $res;
        http_response_code(ResponseStatus::OK);
    }

    else {
        $response['error'] = 'Method not implemented.';
        http_response_code(ResponseStatus::NOT_IMPLEMENTED);
    }

    echo json_encode($response, JSON_NUMERIC_CHECK);

?>