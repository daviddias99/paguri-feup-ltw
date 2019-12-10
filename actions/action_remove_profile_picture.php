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



    // Generate filenames for original, small and medium files
    $originalFileName = "images/profile/originals/$id.jpg";
    $smallFileName = "images/profile/thumbnails_small/$id.jpg";
    $mediumFileName = "images/profile/thumbnails_medium/$id.jpg";

    updateUserInfo($username, $email, $firstName, $lastName, $bio);

    if ($password === $pwConfirmation)
        updateUserPassword($username, $password);

    header('Location: ' . $_SERVER['HTTP_REFERER']);
?>