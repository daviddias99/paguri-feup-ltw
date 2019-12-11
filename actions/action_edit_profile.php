<?php
    include_once('../includes/config.php');
    include_once('../database/user_queries.php');

    $username = $_POST['username'];
    $email = $_POST['email'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $bio = $_POST['bio'];
    $password = $_POST['password'];
    $pwConfirmation = $_POST['pwConfirmation'];

    updateUserInfo($username, $email, $firstName, $lastName, $bio);

    if ($password === $pwConfirmation)
        updateUserPassword($username, $password);

    header('Location: ' . $_SERVER['HTTP_REFERER']);
?>