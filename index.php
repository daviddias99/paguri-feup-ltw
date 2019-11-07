<?php

    include_once('templates/common/header.php');
    include_once('templates/common/footer.php');
    include_once('database/user_queries.php');
    include_once('database/residence_queries.php');

    display_header();

    /*$comments = getResidenceComments(1);
    print_r($comments);

    foreach($comments as $comment) {

        $replies = getCommentReplies($comment['commentID']);
        print_r($replies);

    }*/
    print_r(getResidenceInfo(3));

    display_footer();

?>
