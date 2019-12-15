<?php

include_once('../database/residence_queries.php');
include_once('../database/reservation_queries.php');

function residenceHasCommodity($residenceCommodities, $commodityName)
{
    foreach ($residenceCommodities as $row) {

        if ($row['name'] == $commodityName) {
            return true;
        }
    }

    return false;
}

function residenceHasCommodities($residence, $filterCommodities)
{
    $residenceCommodities = getResidenceCommodities($residence['residenceID']);

    foreach ($filterCommodities as $commodityName => $commodityValue) {

        if (!$commodityValue)
            continue;

        if (residenceHasCommodity($residenceCommodities, $commodityName))
            continue;
        else
            return false;
    }

    return true;
}

function filterResidencesByCommodities($filter_commodities, $residences)
{

    $resultResidences = [];

    for ($i = 0; $i < count($residences); $i++) {

        if (residenceHasCommodities($residences[$i], $filter_commodities)) {

            array_push($resultResidences, $residences[$i]);
        }
    }

    return $resultResidences;
}


function distanceBetweenPoints($coords1, $coords2) {

    $EARTH_RADIUS = 6378; // km
    
    $ang1 = toRadians($coords1['lat']);
    $ang2 = toRadians($coords2['lat']);
    $latDiff = toRadians($coords2['lat']-$coords1['lat']);
    $lngDiff = toRadians($coords2['lng']-$coords1['lng']);

    $a = sin($latDiff/2) * sin($latDiff/2) +
            cos($ang1) * cos($ang2) *
            sin($lngDiff/2) * sin($lngDiff/2);
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));

    return $EARTH_RADIUS * $c;
}

function toRadians($num) {
    return $num * (M_PI / 180);
}

function isResidenceInLocation($residence, $location_data){

    return distanceBetweenPoints(['lat' => $residence['latitude'],'lng' => $residence[ 'longitude']],$location_data['coords']) <= $location_data['radius'];
}

function filterResidencesByLocation($location_data, $residences)
{
    $resultResidences = [];
    for ($i = 0; $i < count($residences); $i++) {

        if (isResidenceInLocation($residences[$i], $location_data)) {

            array_push($resultResidences, $residences[$i]);
        }
    }
    return $resultResidences;
}

// verifies if [$date_start, $date_end] is between [$comp_start, $comp_end]
function dateBetween($date_start, $date_end, $comp_start, $comp_end) {
    if ($date_start === "" && $date_end === "") return TRUE;

    if ($date_start === "") return $date_end >= $comp_start && $date_end <= $comp_end;
    else if ($date_end === "") return $date_start >= $comp_start && $date_start <= $comp_end;
    else return $date_start >= $comp_start && $date_end <= $comp_end;
}

function dateOverlaps($date_start, $date_end, $comp_start, $comp_end) {
    if ($date_start === "" && $date_end === "") return FALSE;

    if ($date_start === "") return $date_end >= $comp_start && $date_end <= $comp_end;
    else if ($date_end === "") return $date_start >= $comp_start && $date_start <= $comp_end;
    else return !($date_end < $comp_start || $date_start > $comp_end);
}

function filterResidencesByAvailability($checkin, $checkout, $residences)
{
    if ($checkin === "" && $checkout === "") return $residences;
    if ($checkin !== "" && $checkout !== "" && $checkin > $checkout) return [];

    $resultResidences = [];
    for ($i = 0; $i < count($residences); $i++) {

        $residence = $residences[$i];

        // find corresponding availability period
        $available_periods = getResidenceAvailabilities($residence['residenceID']);
        $valid_period = FALSE;
        foreach($available_periods as $period) {
            if (dateBetween($checkin, $checkout, $period['startDate'], $period['endDate'])) {
                $valid_period = $period;
                break;
            }
        }
        if(!$valid_period) continue;

        // check reservations inside valid_period
        $reservations = getResidenceReservationsBetween($residence['residenceID'], $valid_period['startDate'], $valid_period['endDate']);
        $reservations_overlap = FALSE;
        foreach($reservations as $reservation) {
            if (dateOverlaps($checkin, $checkout, $reservation['startDate'], $reservation['endDate'])) {
                $reservations_overlap = TRUE;
                break;
            }
        }

        if(!$reservations_overlap)
            array_push($resultResidences, $residence);

    }
    return $resultResidences;
}

$filter_data = json_decode($_GET['filter_data'], true);
$location_data = json_decode($_GET['location_data'], true);

$residences = getResidencesWith(
    $filter_data['capacity'],
    $filter_data['nBeds'],
    $filter_data['type'],
    $filter_data['priceFrom'],
    $filter_data['priceTo'],
    $filter_data['ratingFrom'],
    $filter_data['ratingTo']
);

$residences = filterResidencesByCommodities($filter_data['commodities'], $residences);
$residences = filterResidencesByLocation($location_data, $residences);
$residences = filterResidencesByAvailability($filter_data['checkin'], $filter_data['checkout'], $residences);

echo json_encode($residences);