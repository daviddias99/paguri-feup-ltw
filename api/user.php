<?php

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

    include_once("../database/user_queries.php");

    if($request_method == 'GET') {
        
        if(array_key_exists('id', $_GET)) {
            $info = getUserInfoById($_GET['id']);
            
            if ($info == FALSE) {
                $response['error'] = 'Did not find user with given id.';
                http_response_code(ResponseStatus::NOT_FOUND);
            }
            else {
                $response['user'] = $info;
            }
        }
        else if(array_key_exists('username', $_GET)) {
            $info = getUserInfo($_GET['username']);
            
            if ($info == FALSE) {
                $response['error'] = 'Did not find user with given username.';
                http_response_code(ResponseStatus::NOT_FOUND);
            }
            else {
                $response['user'] = $info;
            }
        }
        else {
            $response['users'] = getAllUsers();
        }
    }

    else if ($request_method == 'POST') {        
        
        // TODO password being sent in plaintext to api. IS THIS SECURE???
        $needed_keys = [
            'username', 
            'email', 
            'firstName', 
            'lastName',
            'password'
        ];

        // check if all values are present
        if(!array_keys_exist($_POST, $needed_keys)) {
            $response['error'] = 'Missing values in request body.';
            http_response_code(ResponseStatus::BAD_REQUEST);
            echo json_encode($response);
            die();
        }

        // TODO check repeated user

        $insert_op = createUserByObj($_POST);
        if ($insert_op == FALSE) {
            $response['error'] = 'Error inserting new user.';
            http_response_code(ResponseStatus::BAD_REQUEST);
        }

        http_response_code(ResponseStatus::CREATED);
    }

    else if ($request_method == 'PUT') {
        $response['error'] = 'PUT method is not allowed.';
        http_response_code(ResponseStatus::METHOD_NOT_ALLOWED);
    }

    else if ($request_method == 'DELETE') {
        $response['error'] = 'DELETE method is not allowed.';
        http_response_code(ResponseStatus::METHOD_NOT_ALLOWED);
    }

    else {
        $response['error'] = 'Method not implemented.';
        http_response_code(ResponseStatus::NOT_IMPLEMENTED);
    }

    echo json_encode($response, JSON_NUMERIC_CHECK);

?>