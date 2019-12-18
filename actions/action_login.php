<?php
    include_once('../includes/config.php');
    include_once('../database/user_queries.php');
    
    // already logged in
    if(isset($_SESSION['userID'])) {
        header('Location: ../pages/front_page.php');
        die();
    }

    // missing info
    if(!isset($_POST['username']) || 
       !isset($_POST['password']) ||
       !isset($_POST['csrf'])) {
        header('Location: ../pages/front_page.php');
        die();
    }

    $username = $_POST['username'];
    $password = $_POST['password'];
    $csrf = $_POST['csrf'];

    if($_SESSION['csrf'] !== $csrf) {
        die(header('Location: ../pages/login.php'));
    }

    if ( !preg_match ("/^[a-zA-Z0-9_-]+$/", $username)) {
        header('Location: ../pages/login.php');
        die();
    }

    if (strlen($password) < 6 or strpos($password, ' ') !== false) {
        header('Location: ../pages/login.php');
        die();
    }

    if (validLogin($username, $password)) {
        $_SESSION['username'] = $username;
        $_SESSION['userID'] = getUserIDByUsername($username);
    } else {
        $_SESSION['messages'][] = array('type' => 'error', 'content' => 'Login failed!');
    }

    header('Location: ../pages/front_page.php');
?>