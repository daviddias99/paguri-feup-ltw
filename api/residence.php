<?php

    // TODO authentication with sessions
    // implement delete and put?
    // how to get info from PUT and DELETE?

    include_once('./response_status.php');

    $request_method = $_SERVER['REQUEST_METHOD'];
    $accept_header = $_SERVER['HTTP_ACCEPT'];

    $response = array();

    if ($accept_header != 'application/json') {
        api_error(ResponseStatus::BAD_REQUEST, 'Accept header must be application/json.');
    }

    include_once("../database/residence_queries.php");
    include_once("../database/user_queries.php");

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

        check_residence_values($_POST);
        
        if(!userExistsById($_POST['owner']))
            api_error(ResponseStatus::BAD_REQUEST, 'Given user does not exist.');
        
        if(!getResidenceTypeWithID($_POST['type']))
            api_error(ResponseStatus::BAD_REQUEST, 'Given residence type does not exist.');
        
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

        //parse_str(file_get_contents("php://input"), $_DELETE);
        //print_r($_DELETE);

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

    function check_residence_values($values) {  
        
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

        if(!array_keys_exist($values, $needed_keys)) {
            api_error(ResponseStatus::BAD_REQUEST, 'Missing values in request body.');
        }

        $numeric_keys = [
            'pricePerDay',
            'capacity',
            'nBedrooms',
            'nBathrooms',
            'nBeds',
            'latitude',
            'longitude'
        ];

        foreach($numeric_keys as $num_key) {
            if (!is_numeric($values[$num_key]))
                api_error(ResponseStatus::BAD_REQUEST, "$num_key must be a numeric value.");
        }
    }

?>