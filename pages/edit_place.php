<?php
    include_once('../includes/config.php');
    include_once('../templates/common/header.php');
    include_once('../templates/common/footer.php');
    include_once('../templates/houses.php');
    include_once('../database/user_queries.php');
    include_once('../database/residence_queries.php');

    if (! isset($_SESSION['username']))
        header('Location: not_found_page.php?message='.urlencode("You must be logged in to edit a residence."));

    if (!isset($_POST['id']))
        header('Location: front_page.php');

    $placeID = $_GET['id'];
    $username = $_SESSION['username'];


    if (! userHasResidence($username, $placeID))
        header('Location: not_found_page.php?message='.urlencode("You can only edit your own places."));

    $place = getResidenceInfo($placeID);

    draw_header('user_profile', array('edit_place.js'));
    draw_edit_place($place);
    draw_footer();
?>
