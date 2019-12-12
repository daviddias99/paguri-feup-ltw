<?php


include_once('../database/residence_queries.php');


$residence = getResidenceInfo($_GET['id']);


if($residence == FALSE){

    header('Location: not_found_page.php');
}

print_r($residence);

?>