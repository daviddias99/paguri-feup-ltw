<?php

    include_once('templates/common/header.php');
    include_once('templates/common/footer.php');
    include_once('database/user_queries.php');

    display_header();

    createUser('insertTest4', 'test4@gmail.com', 'sal grosso', '1234');
    print_r(getAllUsers());

    display_footer();

?>
