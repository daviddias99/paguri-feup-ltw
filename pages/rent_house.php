<?php
include_once('../includes/config.php');
include_once('../templates/common/header.php');
include_once('../templates/common/footer.php');
include_once('../templates/map/includes.php');
include_once('../templates/residence_summary/includes.php');
include_once('../templates/date_verification/includes.php');
include_once('../templates/availability_verification/includes.php');
include_once('../templates/filters/includes.php');
include_once('../templates/rent_house_page_elements.php');
include_once('../templates/residence_availabilities.php');
include_once('../database/residence_queries.php');

$residenceID = $_GET['residenceID'];
$residence = getResidenceInfo($residenceID);

// Redirect the user if the residence does not exist
if ($residence == FALSE) {
    
    header('Location: not_found_page.php?message='.urlencode("The residence you're looking for does not exist. Spooky!"));
}

// A user must be logged in to rent a house
if (!isset($_SESSION['username'])) {

    header('Location: not_found_page.php?message='.urlencode("You must be logged in to rent a residence."));
}

$owner = getUserInfoById($residence['owner']);

// A user can't rent his own residence
if($owner['username'] == $_SESSION['username']){
    header('Location: not_found_page.php?message='.urlencode("You can't rent you're own residence..."));
}
$availabilities = getResidenceAvailabilities($residenceID);

// Draw the page header
draw_header('rent_house', NULL);

// Add the needed includes (drawing summary, date verifications...)
add_residence_summary_includes();
add_date_verification_includes();
add_availability_verification_includes();
draw_main($residence);
draw_footer();
?>

