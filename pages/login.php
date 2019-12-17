<?php
    include_once('../includes/config.php');
    include_once('../templates/common/header.php');
    include_once('../templates/common/footer.php');
    include_once('../templates/auth.php');

    if (isset($_SESSION['username']))
        header('Location: front_page.php');

    draw_header('front_page', null);
    draw_login();
    draw_footer();
?>