<?php
    include_once('../includes/config.php');
    include_once('../database/user_queries.php');

    $username = $_POST['username'];
    $newUsername = $_POST['newUsername'];
    $email = $_POST['email'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $bio = $_POST['bio'];

    updateUserInfo($username, $newUsername, $email, $firstName, $lastName, $bio);

    $_SESSION['username'] = $newUsername;

    if ($password === $pwConfirmation)
        updateUserPassword($username, $password);

    $_SESSION['messages'][] = array('type' => 'success', 'content' => 'Updated profile');

    header('Location: ../pages/profile.php');
?>