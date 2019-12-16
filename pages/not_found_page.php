<?php
    include_once('../includes/config.php');
    include_once('../templates/common/header.php');
    include_once('../templates/common/footer.php');
    include_once('../templates/page_not_found_elements.php');

    draw_header('search_results',NULL);

    if(isset($_GET['message'])){
        draw_not_found($_GET['message']);
    }
    else{   
        draw_not_found(null);
    }

    draw_footer();
?>


