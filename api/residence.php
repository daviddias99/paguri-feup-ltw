<?php

    // TODO authentication with sessions

    include_once('../includes/config.php');
    include_once('./response_status.php');

    $request_method = $_SERVER['REQUEST_METHOD'];
    $accept_header = $_SERVER['HTTP_ACCEPT'];

    if ($accept_header != 'application/json') {
        api_error(ResponseStatus::BAD_REQUEST, 'Accept header must be application/json.');
    }

    if (!isset($_SESSION['userID'])) {
        api_error(ResponseStatus::UNAUTHORIZED, 'You must authenticate itself to access this resource.');
    }

    include_once("../database/residence_queries.php");
    include_once("../database/user_queries.php");

    $response = array();

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

        echo json_encode($response, JSON_NUMERIC_CHECK);
    }

    else if ($request_method == 'POST') {

        check_residence_values($_POST);

        $lastInsertId = createResidence($_POST);
        if ($lastInsertId == FALSE) {
            api_error(ResponseStatus::INTERNAL_SERVER_ERROR, 'There was an error while inserting the new residence.');
        }

        // remove url parameters
        $question_mark_pos = strpos($_SERVER["REQUEST_URI"], "?");
        $request_uri = $question_mark_pos == FALSE ? $_SERVER["REQUEST_URI"] : substr($_SERVER["REQUEST_URI"], 0, $question_mark_pos);

        http_response_code(ResponseStatus::CREATED);
        $actual_link = "http://$_SERVER[HTTP_HOST]$request_uri";
        header("Location: $actual_link?id=$lastInsertId");
    }

    else if ($request_method == 'PUT') {

        if(!array_key_exists('id', $_GET)) {
            api_error(ResponseStatus::METHOD_NOT_ALLOWED, 'Residence ID must be specified.');
        }

        if (getResidenceInfo($_GET['id']) == FALSE) {
            api_error(ResponseStatus::NOT_FOUND, 'Did not find residence with given id.');
        }

        check_residence_values($_GET);

        $updatedRes = updateResidence($_GET);
        if ($updatedRes == FALSE) {
            api_error(ResponseStatus::INTERNAL_SERVER_ERROR, 'There was an error while updating the residence.');
        }

        http_response_code(ResponseStatus::NO_CONTENT);
    }

    else if ($request_method == 'DELETE') {

        if(!array_key_exists('id', $_GET)) {
            api_error(ResponseStatus::METHOD_NOT_ALLOWED, 'Residence ID must be specified.');
        }
        
        $residenceToDelete = getResidenceInfo($_GET['id']);
        if ($residenceToDelete == FALSE) {
            api_error(ResponseStatus::NOT_FOUND, 'Did not find residence with given id.');
        }

        if ($_SESSION['userID'] != $residenceToDelete['owner'])
            api_error(ResponseStatus::FORBIDDEN, 'You must be the owner of the residence to delete it.');

        $deletedRes = deleteResidence($_GET['id']);
        if ($deletedRes == FALSE) {
            api_error(ResponseStatus::INTERNAL_SERVER_ERROR, 'There was an error while deleting the residence.');
        }

        http_response_code(ResponseStatus::NO_CONTENT);
    }

    else {
        $response['error'] = 'Method not implemented.';
        http_response_code(ResponseStatus::NOT_IMPLEMENTED);
    }


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
            'longitude',
            'commodities'
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

        $commodities = json_decode($values['commodities']);
        if (! (is_array($commodities))) {
            api_error(ResponseStatus::BAD_REQUEST, "commodities must be an array of numeric values.");
        } else {
            foreach ($commodities as $commodity) {
                if (! is_numeric($commodity)) {
                    api_error(ResponseStatus::BAD_REQUEST, "commodities must be an array of numeric values.");
                }
            }
        }

        if(!userExistsById($values['owner']))
            api_error(ResponseStatus::BAD_REQUEST, 'Given user does not exist.');

        if(!getResidenceTypeWithID($values['type']))
            api_error(ResponseStatus::BAD_REQUEST, 'Given residence type does not exist.');
    
        if($_SESSION['userID'] != $values['owner'])
            api_error(ResponseStatus::FORBIDDEN, 'You must be the owner of the provided residence.');
    }

?>