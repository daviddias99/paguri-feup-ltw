<?php

include_once('../database/residence_queries.php');

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

function filterResidencesWithCommodities($filter_commodities, $residences)
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

function isResidenceInLocation($residence,$location_data){

    return distanceBetweenPoints(['lat' => $residence['latitude'],'lng' => $residence[ 'longitude']],$location_data['coords']) <= $location_data['radius'];
}

function filterResidencesFromLocation($location_data, $residences)
{

    $resultResidences = [];

    for ($i = 0; $i < count($residences); $i++) {

        if (isResidenceInLocation($residences[$i], $location_data)) {

            array_push($resultResidences, $residences[$i]);
        }
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

$residences = filterResidencesWithCommodities($filter_data['commodities'], $residences);
$residences = filterResidencesFromLocation($location_data, $residences);

echo json_encode($residences);
