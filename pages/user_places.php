<?php
    include_once('../includes/config.php');
    include_once('../templates/common/header.php');
    include_once('../templates/common/footer.php');
    include_once('../templates/houses.php');

    draw_header('user_profile', array('user_properties.js'));

    if (!isset($_GET['id']))
        die(header('Location: front_page.php'));

    $userID = $_GET['id'];

    draw_list_user_places($userID);

    draw_footer();
?>
