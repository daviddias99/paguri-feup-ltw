<?php
    include_once('../includes/config.php');
    include_once('../templates/common/header.php');
    include_once('../templates/common/footer.php');
    include_once('../templates/user.php');
    include_once('../database/user_queries.php');

    if (! isset($_SESSION['username']))
        die(header('Location: not_found_page.php?message='.urlencode("You must be logged in to edit your profile.")));

    $username = $_SESSION['username'];
    $user = getUserInfo($username);

    draw_header('user_profile', array('edit_profile.js'));
    draw_edit_profile($user);
    draw_footer();
?>
