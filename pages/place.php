<?php
    include_once('../includes/config.php');
    include_once('../templates/common/header.php');
    include_once('../templates/common/footer.php');

    if (! isset($_SESSION['username']))
        draw_header('search_results', $_SESSION['username']);
    else
        draw_header('search_results', null);



    draw_footer();
?>
