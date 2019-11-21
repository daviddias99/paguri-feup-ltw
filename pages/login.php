<?php
    include_once('../includes/config.php');
    include_once('../templates/common/header.php');
    include_once('../templates/common/footer.php');
    include_once('../templates/auth.php');

    if (isset($_SESSION['username']))
        draw_header('front_page', $_SESSION['username']);
    else   
        draw_header('front_page', NULL);
    draw_login();
    draw_footer();
?>