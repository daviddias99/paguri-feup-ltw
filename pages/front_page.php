<?php
    include_once('../includes/config.php');
    include_once('../templates/date_verification/includes.php');
    include_once('../templates/common/header.php');
    include_once('../templates/common/footer.php');
    include_once('../templates/front_page_elements.php');

    draw_header('front_page', null);
    add_date_verification_includes();
    draw_search_box();
    draw_footer();
?>
