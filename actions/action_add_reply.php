<?php
    include_once('../includes/config.php');
    include_once('../database/comment_queries.php');
    include_once('../database/user_queries.php');

    // Ensure all the needed variables are given
    if(!isset($_SESSION['userID']) || 
        !isset($_GET['reviewID']) || 
        !isset($_GET['title']) || 
        !isset($_GET['content'])  ||
        !isset($_GET['csrf']))
    {

        header('Location: ../pages/front_page.php');
        exit;
    }

    $userID = $_SESSION['userID'];
    $reviewID = $_GET['reviewID'];    
    $title = $_GET['title'];    
    $content = $_GET['content'];  
    $csrf = $_GET['csrf'];

    if($_SESSION['csrf'] !== $csrf) {
        die(header('Location: ../pages/front_page.php'));
    }

    // Add new reply to database
    addReply($userID,$reviewID,$title,$content,date("Y/m/d H:i"));
    
    header('Location: ../pages/view_house.php?id='.$_GET['residence']);
?>