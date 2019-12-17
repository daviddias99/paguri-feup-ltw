<?php
include_once('../includes/config.php');
include_once('../templates/common/header.php');
include_once('../templates/common/footer.php');
include_once('../templates/houses.php');

include_once('../database/residence_queries.php');
include_once('../database/user_queries.php');
include_once('../templates/review_writing_elements.php');


$reservationID = $_GET['id'];

$reservation = getReservationWithID($reservationID);
$user = getUserInfoById($reservation['customer']);
$userLoggedIn = (isset($_SESSION['username']) and $_SESSION['username'] == $user['username']);

if (!$userLoggedIn) {
    header('Location: not_found_page.php?message=' . urlencode("You can't access this page."));
    exit;
}

$residence = getResidenceInfo($reservation['lodge']);

draw_header('write_review', NULL);
draw_review_form($residence,$reservation);
draw_footer();

?>

