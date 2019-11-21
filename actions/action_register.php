<?php
    include_once('../includes/config.php');
    include_once('../database/user_queries.php');

    $username = $_POST['username'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $password = $_POST['password'];

    if (createUser($username, $username, $firstName, $lastName, $password, $password))
        header('Location: ../pages/front_page.php');
    else
        header('Location: ../pages/register.php');
?>