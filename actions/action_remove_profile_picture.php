<?php
    include_once('../includes/config.php');
    include_once('../database/user_queries.php');

    $username = $_POST['username'];

    $oldPhotoID = updateProfilePicture($username, 'default.jpg');

    if ($oldPhotoID !== 'default.jpg') {
        unlink("../images/users/originals/$oldPhotoID");
        unlink("../images/users/thumbnails_small/$oldPhotoID");
        unlink("../images/users/thumbnails_medium/$oldPhotoID");
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
?>