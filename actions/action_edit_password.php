<?php
include_once('../includes/config.php');
include_once('../database/user_queries.php');

$username = $_POST['username'];

if ((!isset($_SESSION['username']) or $username != $_SESSION['username']))
    die();

$password = $_POST['password'];
$pwConfirmation = $_POST['pwConfirmation'];

if (strlen($password) >= 6 and $password === $pwConfirmation)
    updateUserPassword($username, $password);

header('Location: ' . $_SERVER['HTTP_REFERER']);
