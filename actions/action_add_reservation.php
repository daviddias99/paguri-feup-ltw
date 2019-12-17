<?php
    include_once('../includes/config.php');
    include_once('../database/user_queries.php');
    include_once('../database/residence_queries.php');
    include_once('../templates/residence_availabilities.php');

    // Ensure all the needed variables are given
    if(!isset($_SESSION['userID']) || 
    !isset( $_GET['residenceID']) || 
    !isset($_GET['checkin_date']) || 
    !isset($_GET['checkout_date']) 
    ){
        echo 'Something went wrong';
        header('Location: ../pages/front_page.php');
        exit;
    }

    $residenceID = $_GET['residenceID'];    
    $checkin_date =  $_GET['checkin_date'];   
    $checkout_date =  $_GET['checkout_date'];   
    $residence = getResidenceInfo($residenceID);
    $userID = $_SESSION['userID'];

    // Ensure that the renter is not the owner
    if($residence['owner'] == $userID){
        header('Location: ../pages/front_page.php');
        exit;
    }

    // Check if rent dates respect availabilities
    if(!datesRespectAvailabilities(getAvailabilities($residenceID),$checkin_date,$checkout_date)){
        header('Location: ../pages/front_page.php');
        exit;
    }

    $reservationObj = [];
    $reservationObj['lodge'] = $residenceID;
    $reservationObj['customer'] = $userID;
    $reservationObj['startDate'] = $checkin_date;
    $reservationObj['endDate'] = $checkout_date;

    // Add the reservation to database
    if(addReservation($reservationObj) == FALSE){
        header('Location: ../pages/front_page.php');
        exit;
    }

    // Return to house page
    header('Location: ../pages/view_house.php?id='.$residenceID);
?>