<?php
    include_once('../includes/config.php');
    include_once('../templates/common/header.php');
    include_once('../templates/common/footer.php');
    include_once('../templates/map/includes.php');
    include_once('../templates/houses.php');

    if (! isset($_SESSION['username']))
        die("User not logged in!");

    draw_header('search_results', array('add_place.js'));
    add_map_includes();
    draw_add_house();
    draw_footer();
?>
