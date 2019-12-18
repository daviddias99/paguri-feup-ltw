<?php

    include_once('../database/residence_queries.php');

    

    // Ensure all the needed variables are given
    if( !isset($_GET['residenceID']) || 
        !isset($_GET['checkin_date']) || 
        !isset($_GET['checkout_date']))
    {

        header('Location: ../pages/front_page.php');
        exit;
    }

    $residenceID = $_GET['residenceID'];
    $residence = getResidenceInfo($residenceID);
    $owner = getUserInfoById($residence['owner']);
    $valid = (isset($_SESSION['username']) and $_SESSION['username'] == $owner['username']);

    if (!$valid) {
        header('Location: ../pages/front_page.php');
        exit;
    }

    $in = $_GET['checkin_date'];
    $out = $_GET['checkout_date'];

    $availabilites = getResidenceAvailabilities($residenceID);

    foreach($availabilities as $availability){

        $availabilityIn = strtotime($availability['startDate']);
        $availabilityOut = strtotime($availability['endDate']);
        $intTime = strtotime($int);
        $outTime = strtotime($out);
    
        if($intTime >= $availabilityIn && $intTime <= $availabilityOut){
            header('Location: ../pages/front_page.php');
            exit;
        }

        if($outTime >= $availabilityIn && $outTime <= $availabilityOut){
            header('Location: ../pages/front_page.php');
            exit;
        }
    }

    // Add new reply to database
    addAvailability($residenceID,$in, $out);
    
    header('Location: ../pages/user_places.php?id='.$_SESSION['id'] );
?>