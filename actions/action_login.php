<?php
    include_once('../includes/config.php');
    include_once('../database/user_queries.php');

    $username = $_POST['username'];
    $password = $_POST['password'];

    if (validLogin($username, $password))
        $_SESSION['username'] = $username;


    header('Location: ../pages/front_page.php');
?>