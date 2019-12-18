<?php
    include_once('../includes/config.php');
    include_once('../database/user_queries.php');
    
    // already logged in
    if(isset($_SESSION['userID'])) {
        die(header('Location: ../pages/register.php'));
    }

    if( !isset($_POST['username']) || 
        !isset($_POST['email']) || 
        !isset($_POST['firstName']) || 
        !isset($_POST['lastName']) || 
        !isset($_POST['password']) ||
        !isset($_POST['csrf']))
    {            
        die(header('Location: ../pages/register.php'));
    }

    $username = $_POST['username'];
    $email = $_POST['email'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $password = $_POST['password'];
    $csrf = $_POST['csrf'];

    if($_SESSION['csrf'] !== $csrf) {
        die(header('Location: ../pages/register.php'));
    }

    if ( !preg_match ("/^[a-zA-Z0-9_-]+$/", $username)) {
        die(header('Location: ../pages/register.php'));
    }

    if ( !preg_match ("/^[a-zA-Z]+$/", $firstName)) {
        die(header('Location: ../pages/register.php'));
    }

    if ( !preg_match ("/^[a-zA-Z]+$/", $lastName)) {
        die(header('Location: ../pages/register.php'));
    }

    if (strlen($password) < 6 or strpos($password, ' ') !== false) {
        die(header('Location: ../pages/register.php'));
    }


    $newUserID = createUser($username, $email, $firstName, $lastName, $password);
    if ($newUserID == FALSE)
        header('Location: ../pages/register.php');
    else {
        $_SESSION['username'] = $username;
        $_SESSION['userID'] = $newUserID;
        header('Location: ../pages/front_page.php');
    }
?>