<?php
    include_once('../includes/config.php');
    include_once('../templates/common/header.php');
    include_once('../templates/common/footer.php');
    include_once('../templates/user.php');
    include_once('../database/user_queries.php');

    if (! isset($_SESSION['username']))
        die("User not logged in!");

    $username = $_SESSION['username'];

    draw_header('search_results', $username);

    $user = getUserInfo($username);

    draw_profile($user);

    draw_footer();
?>
