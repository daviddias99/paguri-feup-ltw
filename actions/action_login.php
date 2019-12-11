<?php
    include_once('../includes/config.php');
    include_once('../database/user_queries.php');

    $username = $_POST['username'];
    $password = $_POST['password'];

    if (validLogin($username, $password)) {
        $_SESSION['messages'][] = array('type' => 'success', 'content' => 'Logged in successfully!');
        $_SESSION['username'] = $username;
    } else {
        $_SESSION['messages'][] = array('type' => 'error', 'content' => 'Login failed!');
    }

    header('Location: ../pages/front_page.php');
?>