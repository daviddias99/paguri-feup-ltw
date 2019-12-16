<?php

    include_once('../includes/config.php');
    include_once('./response_status.php');

    $request_method = $_SERVER['REQUEST_METHOD'];
    $accept_header = $_SERVER['HTTP_ACCEPT'];

    $response = array();

    if ($accept_header != 'application/json') {
        api_error(ResponseStatus::BAD_REQUEST, 'Accept header must be application/json.');
    }

    include_once("../database/user_queries.php");

    if($request_method == 'GET') {
        
        if(array_key_exists('id', $_GET)) {
            $info = getUserInfoById($_GET['id']);
            
            if ($info == FALSE) {
                api_error(ResponseStatus::NOT_FOUND, 'Did not find user with given id.');
            }

            $response['user'] = $info;
        }
        else if(array_key_exists('username', $_GET)) {
            $info = getUserInfo($_GET['username']);
            
            if ($info == FALSE) {
                api_error(ResponseStatus::NOT_FOUND, 'Did not find user with given username.');
            }
            
            $response['user'] = $info;
        }
        else if(array_key_exists('email', $_GET)) {
            $info = getUserInfoByEmail($_GET['email']);
            
            if ($info == FALSE) {
                api_error(ResponseStatus::NOT_FOUND, 'Did not find user with given email.');
            }
            
            $response['user'] = $info;
        }
        else {
            $response['users'] = getAllUsers();
        }

        echo json_encode($response, JSON_NUMERIC_CHECK);
    }

    else if ($request_method == 'POST') {  
        
        check_user_values($_POST);

        $lastInsertId = createUserByObj($_POST);
        if ($lastInsertId == FALSE) {
            api_error(ResponseStatus::INTERNAL_SERVER_ERROR, 'Error while creating new user.');
        }

        // remove url parameters
        $question_mark_pos = strpos($_SERVER["REQUEST_URI"], "?");
        $request_uri = $question_mark_pos == FALSE ? $_SERVER["REQUEST_URI"] : substr($_SERVER["REQUEST_URI"], 0, $question_mark_pos);

        http_response_code(ResponseStatus::CREATED);
        $actual_link = "http://$_SERVER[HTTP_HOST]$request_uri";
        header("Location: $actual_link?id=$lastInsertId");
    }

    else {
        $response['error'] = 'Method not implemented.';
        http_response_code(ResponseStatus::NOT_IMPLEMENTED);
    }

    function check_user_values($values) {  
        
        $needed_keys = [
            'username', 
            'email', 
            'firstName', 
            'lastName',
            'password'
        ];

        if(!array_keys_exist($values, $needed_keys)) {
            api_error(ResponseStatus::BAD_REQUEST, 'Missing values in request body.');
        }

        if(userExists($values['username'], $values['email']))
            api_error(ResponseStatus::BAD_REQUEST, 'Username and/or email already in use.');
        
        if (strlen($values['password']) < 6)
            api_error(ResponseStatus::BAD_REQUEST, 'Password must have at least 6 characters.');
    }

?>