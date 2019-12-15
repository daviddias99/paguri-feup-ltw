<?php
    include_once('../includes/config.php');
    include_once('../templates/common/header.php');
    include_once('../templates/common/footer.php');
    include_once('../templates/houses.php');
    include_once('../database/reservation_queries.php');

    if (! isset($_SESSION['username']))
        die("user not logged in");

    $reservations = getUserReservations($_SESSION['username']);

    draw_header('user_profile', null);
    draw_reservations($reservations);
    draw_footer();
?>
