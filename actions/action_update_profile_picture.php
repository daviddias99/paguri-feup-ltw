<?php
    include_once('../includes/config.php');
    include_once('../database/user_queries.php');

    $username = $_POST['username'];

    $photoID = "$username.jpg";
    $oldPhotoID = updateProfilePicture($username, $photoID);

    if ($oldPhotoID !== $photoID and $oldPhotoID !== 'default.jpg') {
        unlink("../images/users/originals/$oldPhotoID");
        unlink("../images/users/thumbnails_small/$oldPhotoID");
        unlink("../images/users/thumbnails_medium/$oldPhotoID");
    }

    // Generate filenames for original, small and medium files
    $originalFileName = "../images/users/originals/$photoID";
    $smallFileName = "../images/users/thumbnails_small/$photoID";
    $mediumFileName = "../images/users/thumbnails_medium/$photoID";

    // Move the uploaded file to its final destination
    move_uploaded_file($_FILES['image']['tmp_name'], $originalFileName);

    // Crete an image representation of the original image
    $original = imagecreatefromjpeg($originalFileName);

    $width = imagesx($original);     // width of the original image
    $height = imagesy($original);    // height of the original image
    $square = min($width, $height);  // size length of the maximum square

    // Create and save a small square thumbnail
    $small = imagecreatetruecolor(40, 40);
    imagecopyresized($small, $original, 0, 0, ($width>$square)?($width-$square)/2:0, ($height>$square)?($height-$square)/2:0,40, 40, $square, $square);
    imagejpeg($small, $smallFileName);

    // Create and save a medium square thumbnail
    $medium = imagecreatetruecolor(256, 256);
    imagecopyresized($medium, $original, 0, 0, ($width>$square)?($width-$square)/2:0, ($height>$square)?($height-$square)/2:0,256, 256, $square, $square);
    imagejpeg($medium, $mediumFileName);

    header('Location: ' . $_SERVER['HTTP_REFERER']);
?>