<?php
    include_once('../includes/config.php');
    include_once('../database/comment_queries.php');
    include_once('../database/reservation_queries.php');
    include_once('../database/user_queries.php');

    // Ensure all the needed variables are given
    if(!isset($_SESSION['username']) || 
    !isset( $_GET['review_title']) || 
    !isset( $_GET['reservationID']) || 
    !isset($_GET['rating']) || 
    !isset($_GET['content'])  ||
    !isset($_GET['csrf'])
    ){

        header('Location: ../pages/not_found_page.php?message=' . urlencode("Not enough information was given."));
        exit;
    }

    $csrf = $_GET['csrf'];

    if($_SESSION['csrf'] !== $csrf) {
        die(header('Location: ../pages/front_page.php'));
    }

    $reservation = getReservationWithID($_GET['reservationID']);
    $user = getUserInfoById($reservation['customer']);
    $userLoggedIn = (isset($_SESSION['username']) and $_SESSION['username'] == $user['username']);

    if (!$userLoggedIn) {
        header('Location: ../pages/not_found_page.php?message=' . urlencode("You can't access this page."));
        exit;
    }

    $reviewTitle = $_GET['review_title']; 
    $reservationID =  $_GET['reservationID'];  
    $reviewRating = $_GET['rating'];    
    $reviewContent = $_GET['content'];
    
    if (!is_numeric($reviewRating) || $reviewRating > 10) {
        header('Location: ../pages/front_page.php');
        die();
    }

    // Add new reply to database
    addComment($reservationID,$reviewTitle,$reviewContent,$reviewRating,date("Y/m/d H:i"));
    
    header('Location: ../pages/user_reservations.php?id='.$reservation['customer']);
