<?php
    include_once('../includes/config.php');
    include_once('../templates/common/header.php');
    include_once('../templates/common/footer.php');
    include_once('../templates/user.php');
    include_once('../database/user_queries.php');

    $username = $_GET['id'];
    $user = getUserInfo($username);

    draw_header('user_profile', null);
    draw_user($user);
    draw_footer();
?>
