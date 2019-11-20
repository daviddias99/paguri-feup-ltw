<?php
    include_once('../database/db_user.php');

    $username = $_POST['username'];
    $password = $_POST['password'];
    
    createUser($username, $username, $password, $password);
?>