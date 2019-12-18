<?php

    include_once('../includes/config.php');
    include_once('../database/residence_queries.php');
    include_once('../database/user_queries.php');


    

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
        error_log("OLA",4);
        header('Location: ../pages/front_page.php');
        exit;
    }

    $in = $_GET['checkin_date'];
    $out = $_GET['checkout_date'];

    $availabilities = getResidenceAvailabilities($residenceID);

    foreach($availabilities as $availability){

        $availabilityIn = strtotime($availability['startDate']);
        $availabilityOut = strtotime($availability['endDate']);

        echo "ain " . $availabilityIn  . " aout"  .$availabilityOut;
        $intTime = strtotime($in);
        $outTime = strtotime($out);
        echo "in" . $intTime  . " out"  .$outTime;
    
        if($intTime >= $availabilityIn && $intTime <= $availabilityOut){
            header('Location: ../pages/front_page.php');
            exit;
        }

        if($outTime >= $availabilityIn && $outTime <= $availabilityOut){
            header('Location: ../pages/front_page.php');
            exit;
        }
    }

    addAvailability($residenceID,$in, $out);
    
    header('Location: ../pages/user_places.php?id='.$_SESSION['userID'] );
?>