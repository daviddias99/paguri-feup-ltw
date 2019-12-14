<?php
    include_once('../includes/config.php');
    include_once('../database/comment_queries.php');
    include_once('../database/user_queries.php');

    if(!isset($_SESSION['username']) || 
    !isset( $_GET['reviewID']) || 
    !isset($_GET['title']) || 
    !isset($_GET['content']) 
    ){

        header('Location: ../pages/front_page.php');
        exit;
    }

    $reviewID = $_GET['reviewID'];    
    $title = $_GET['title'];    
    $content = $_GET['content'];  
    $userID = getUserInfo($_SESSION['username'])['userID'];
    addReply($userID,$reviewID,$title,$content,date("Y/m/d H:i"));
    
    header('Location: ../pages/view_house.php?id='.$_GET['residence']);
