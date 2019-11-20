<?php 
    include_once('../database/user_queries.php');

    session_start();

    $username = $_POST['username'];
    $password = $_POST['password'];

    $_SESSION['username'] = $username;
?>