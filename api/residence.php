<?php

    // TODO authentication!!! api key ?

    include_once('./response_status.php');

    $request_method = $_SERVER['REQUEST_METHOD'];
    $accept_header = $_SERVER['HTTP_ACCEPT'];

    $response = array(
        'status' => ResponseStatus::NOT_IMPLEMENTED,
        'msg' => 'Method not implemented.'
    );

    if ($accept_header != 'application/json') {
        $response['status'] = ResponseStatus::BAD_REQUEST;
        $response['msg'] = 'Accept header must be application/json.';
        echo json_encode($response);
        die();
    }

    include_once("../database/residence_queries.php");

    if($request_method == 'GET') {

        $response['status'] = ResponseStatus::OK;
        $response['msg'] = 'Successfull request.';
        
        if(array_key_exists('id', $_GET)) {
            $info = getResidenceInfo($_GET['id']);
            
            if ($info == FALSE) {
                $response['status'] = ResponseStatus::NOT_FOUND;
                $response['msg'] = 'Did not find residence with given id';
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

        $response['status'] = ResponseStatus::CREATED;
        $response['msg'] = 'Successfully created new residence.';
        
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
            $response['status'] = ResponseStatus::BAD_REQUEST;
            $response['msg'] = 'Missing values in request body.';
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
            $response['status'] = ResponseStatus::BAD_REQUEST;
            $response['msg'] = 'Invalid residence type provided.';
            echo json_encode($response);
            die();
        }

        $insert_op = createResidence($_POST);
        if ($insert_op == FALSE) {
            $response['status'] = ResponseStatus::BAD_REQUEST;
            $response['msg'] = 'Error inserting new residence.';
        }
    }

    else if ($request_method == 'PUT') {
        $response['status'] = ResponseStatus::METHOD_NOT_ALLOWED;
        $response['msg'] = 'PUT method is not allowed.';
    }

    else if ($request_method == 'DELETE') {
        $response['status'] = ResponseStatus::METHOD_NOT_ALLOWED;
        $response['msg'] = 'DELETE method is not allowed.';
    }

    echo json_encode($response, JSON_NUMERIC_CHECK);

?>