<?php

    include_once('../database/residence_queries.php');

    $filter_data = json_decode($_GET['filter_data'],true);

    $residences = getResidencesWith($filter_data['capacity'],$filter_data['nBeds'],$filter_data['type'],$filter_data['priceFrom'],$filter_data['priceTo'],null,null);

?>