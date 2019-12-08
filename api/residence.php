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
        api_error(ResponseStatus::BAD_REQUEST, 'Accept header must be application/json.');
    }

    include_once("../database/residence_queries.php");

    if($request_method == 'GET') {
        
        if(array_key_exists('id', $_GET)) {
            $info = getResidenceInfo($_GET['id']);
            
            if ($info == FALSE) {
                api_error(ResponseStatus::NOT_FOUND, 'Did not find residence with given id.');
            }

            $response['residence'] = $info;
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
            api_error(ResponseStatus::BAD_REQUEST, 'Missing values in request body.');
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
            api_error(ResponseStatus::BAD_REQUEST, 'Residence type does not exist.');
        }

        $lastInsertId = createResidence($_POST);
        if ($lastInsertId == FALSE) {
            api_error(ResponseStatus::BAD_REQUEST, 'Error inserting new residence.');
        }

        // remove url parameters
        $request_uri = substr($_SERVER["REQUEST_URI"], 0, strpos($_SERVER["REQUEST_URI"], "?"));

        http_response_code(ResponseStatus::CREATED);
        $actual_link = "http://$_SERVER[HTTP_HOST]$request_uri"; // are theses variables controllable by the user???
        header("Location: $actual_link?id=$lastInsertId");
    }

    else if ($request_method == 'PUT') {

        if(!array_key_exists('id', $_GET)) {
            api_error(ResponseStatus::METHOD_NOT_ALLOWED, 'Residence ID must be specified.');
        }

    }

    else if ($request_method == 'DELETE') {

        if(!array_key_exists('id', $_GET)) {
            api_error(ResponseStatus::METHOD_NOT_ALLOWED, 'Residence ID must be specified.');
        }

        $res = deleteResidence($_GET['id']);
        if ($res == FALSE) {
            api_error(ResponseStatus::NOT_FOUND, 'Residence not found.');
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