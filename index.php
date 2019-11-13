<?php

    include_once('templates/common/header.php');
    include_once('templates/common/footer.php');
    include_once('database/user_queries.php');
    include_once('database/residence_queries.php');
    include_once('database/map_queries.php');


    print_r(getResidenceMapInfo(1));

?>
