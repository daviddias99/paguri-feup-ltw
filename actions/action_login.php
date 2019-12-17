<?php
    include_once('../includes/config.php');
    include_once('../database/user_queries.php');
    
    // already logged in
    if(isset($_SESSION['userID'])) {
        header('Location: ../pages/front_page.php');
    }

    // missing info
    if(!isset($_POST['username']) || !isset($_POST['password'])) {
        header('Location: ../pages/front_page.php');
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    if (validLogin($username, $password)) {
        $_SESSION['messages'][] = array('type' => 'success', 'content' => 'Logged in successfully!');
        $_SESSION['username'] = $username;
        $_SESSION['userID'] = getUserIDByUsername($username);
    } else {
        $_SESSION['messages'][] = array('type' => 'error', 'content' => 'Login failed!');
    }

    header('Location: ../pages/front_page.php');
?>