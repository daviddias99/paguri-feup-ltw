<?php
    include_once('../includes/config.php');
    include_once('../database/user_queries.php');

    $username = $_POST['username'];
    $email = $_POST['email'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $password = $_POST['password'];


    $newUserID = createUser($username, $email, $firstName, $lastName, $password);
    if ($newUserID == FALSE)
        header('Location: ../pages/register.php');
    else {
        $_SESSION['username'] = $username;
        $_SESSION['userID'] = $newUserID;
        header('Location: ../pages/front_page.php');
    }
?>