<?php
    include_once('../includes/config.php');
    include_once('../templates/common/header.php');
    include_once('../templates/common/footer.php');
    include_once('../templates/user.php');
    include_once('../database/user_queries.php');

    if (!isset($_GET['id']))
        die(header('Location: front_page.php'));

    if (!isset($_SESSION['username']))
        die(header('Location: not_found_page.php?message='.urlencode("You must be logged in to see users profiles.")));

    $id = $_GET['id'];
    $user = getUserInfoById($id);

    draw_header('user_profile', null);
    draw_user($user);
    draw_footer();
?>
