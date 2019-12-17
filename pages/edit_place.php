<?php
    include_once('../includes/config.php');
    include_once('../templates/common/header.php');
    include_once('../templates/common/footer.php');
    include_once('../templates/houses.php');
    include_once('../database/user_queries.php');
    include_once('../database/residence_queries.php');

    if (! isset($_SESSION['username']))
        die("User not logged in!");

    $placeID = $_GET['id'];
    $username = $_SESSION['username'];


    if (! userHasResidence($username, $placeID))
        die("Not your place.");

    $place = getResidenceInfo($placeID);

    draw_header('user_profile', array('edit_place.js'));
    draw_edit_place($place);
    draw_footer();
?>
