<?php
    include_once('../includes/config.php');
    include_once('../templates/common/header.php');
    include_once('../templates/common/footer.php');
    include_once('../templates/houses.php');
    include_once('../database/reservation_queries.php');

    if (! isset($_SESSION['username']))
        die(header('Location: not_found_page.php?message='.urlencode("You must be logged in to check your reservations.")));

    $reservations = getUserReservations($_SESSION['username']);

    draw_header('user_profile', null);
    draw_reservations($reservations);
    draw_footer();
?>
