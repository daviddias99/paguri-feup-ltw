<?php
    include_once('../includes/config.php');
    include_once('../templates/common/header.php');
    include_once('../templates/common/footer.php');
    include_once('../templates/map/includes.php');
    include_once('../templates/filters/includes.php');
    include_once('../templates/search_results_page_elements.php');
    include_once('../database/residence_queries.php');

    draw_header('search_results', NULL);
    add_map_includes();
    add_filter_includes();
    draw_main();
    draw_footer();
?>