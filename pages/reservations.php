<?php
    include_once('../includes/config.php');
    include_once('../templates/common/header.php');
    include_once('../templates/common/footer.php');
    include_once('../templates/houses.php');

    if (! isset($_SESSION['username']))
        die("user not logged in");

    draw_header('user_profile', null);
    draw_list_reservations($username);
    draw_footer();
?>
