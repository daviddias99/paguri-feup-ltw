<?php

include_once('../database/residence_queries.php');
include_once('../database/reservation_queries.php');


function getAvailabilitiesDates($availabilities){

    $result = [];

    foreach($availabilities as $availability){

        $newRow = [];
        $newRow['startDate'] = strtotime($availability['startDate']);
        $newRow['endDate'] = strtotime($availability['endDate']);

        array_push($result,$newRow);
    }

    sort($result );

    return $result ;

}

function getReservationsDates($reservations){

    $result = [];

    foreach($reservations as $reservation){

        $newRow = [];
        $newRow['startDate'] = strtotime($reservation['startDate']);
        $newRow['endDate'] = strtotime($reservation['endDate']);

        array_push($result,$newRow);
    }

    sort($result );

    return $result ;


}

function translate($array){

    $result = [];

    foreach($array as $i){

        $newRow = [];
        $newRow['startDate'] = strftime('%Y-%m-%d',$i['startDate']);
        $newRow['endDate'] = strftime('%Y-%m-%d',$i['endDate']);

        array_push($result,$newRow);
    }


    return $result ;

    
}

function addOneDay($time){
    return strtotime(strftime('%Y-%m-%d',$time). ' +1 day');
}

function removeOneDay($time){
    return strtotime(strftime('%Y-%m-%d',$time). ' -1 day');
}

function getAvailabilities($id){

    $residenceID = $id;
    $availabilities = getResidenceAvailabilities($residenceID);
    $reservations = getResidenceReservations($residenceID);

    $availabilitiesDates = getAvailabilitiesDates($availabilities);
    $reservationsDates = getReservationsDates($reservations);

    $result = [];

    foreach($availabilitiesDates as $availability){

        $lastEndTime = $availability['startDate'];

        foreach($reservationsDates as $reservation){

            if($reservation['startDate'] > $availability['endDate']){
                break;
            }

            if($reservation['startDate'] > $lastEndTime){

                $newRow = [];

                if($lastEndTime !=  $availability['startDate']){

                    $newRow['startDate'] = addOneDay($lastEndTime);
                }
                else {
                    $newRow['startDate'] = $lastEndTime;
                    
                }
                $newRow['endDate'] = removeOneDay($reservation['startDate']);

                $lastEndTime = $reservation['endDate'];

                array_push($result,$newRow);
            }

        }

        if($lastEndTime != $availability['endDate']){
            $newRow = [];
            $newRow['startDate'] = addOneDay($lastEndTime);
            $newRow['endDate'] =  $availability['endDate'];
            array_push($result,$newRow);
        }
        
    }

    return translate($result);

}

?>