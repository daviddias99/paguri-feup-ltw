<?php

include_once('../database/residence_queries.php');

function valueExists($residenceCommodities, $value)
{
    foreach ($residenceCommodities as $row) {

        if ($row['name'] == $value) {return true; }
    }

    return false;
}

function residenceHasCommodities($residence, $filterCommodities)
{

    $residenceCommodities = getResidenceCommodities($residence['residenceID']);

    print_r($residenceCommodities);

    foreach ($filterCommodities as $key => $value) {

        if(!$value)
            continue;

        if(valueExists($residenceCommodities,$key))
            continue;
        else
            return false;
    }
    
    return true;
}

$filter_data = json_decode($_GET['filter_data'], true);

print_r($filter_data);

$residences = getResidencesWith(
    $filter_data['capacity'],
    $filter_data['nBeds'],
    $filter_data['type'],
    $filter_data['priceFrom'],
    $filter_data['priceTo'],
    $filter_data['ratingFrom'],
    $filter_data['ratingTo']
);

$resultResidences = [];

for ($i = 0; $i < count($residences); $i++) {

    if (residenceHasCommodities($residences[$i], $filter_data['commodities'])) {

        array_push($resultResidences, $residences[$i]);
    }
}

echo count($resultResidences);

// echo json_encode($resultResidences);
