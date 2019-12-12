<?php
include_once('../includes/config.php');
include_once('../database/user_queries.php');

$username = $_POST['username'];
$newUsername = $_POST['newUsername'];
$email = $_POST['email'];
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$bio = $_POST['bio'];

updateUserInfo($username, $newUsername, $email, $firstName, $lastName, $bio);

$_SESSION['username'] = $newUsername;

header('Location: ' . $_SERVER['HTTP_REFERER']);

if ($_FILES['image']['tmp_name'] != 'unchanged') {
    if ($_FILES['image']['tmp_name'] != null) {
        saveProfilePhoto($_FILES['image']['tmp_name']);
    } else {
        $oldPhotoID = updateProfilePicture($username, 'default.jpg');
        if ($oldPhotoID !== 'default.jpg') {
            unlink("../images/users/originals/$oldPhotoID");
            unlink("../images/users/thumbnails_small/$oldPhotoID");
            unlink("../images/users/thumbnails_medium/$oldPhotoID");
        }
    }
}

function saveProfilePhoto($tmpPath)
{
    $supportedFormats = array(IMAGETYPE_JPEG => '.jpg', IMAGETYPE_PNG => '.png');

    $imgType = exif_imagetype($tmpPath);
    $extension = $supportedFormats[$imgType];

    if ($extension == null)
        die();

    $username = $_POST['username'];

    $photoID = sha1_file($tmpPath) . $extension;
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
    move_uploaded_file($tmpPath, $originalFileName);


    // Create an image representation of the original image
    switch ($imgType) {
        case IMAGETYPE_JPEG:
            $original = imagecreatefromjpeg($originalFileName);
            break;
        case IMAGETYPE_PNG:
            $original = imagecreatefrompng($originalFileName);
            break;
    }

    $width = imagesx($original);     // width of the original image
    $height = imagesy($original);    // height of the original image
    $square = min($width, $height);  // size length of the maximum square

    // Create and save a small square thumbnail
    $small = imagecreatetruecolor(40, 40);
    imagecopyresized($small, $original, 0, 0, ($width > $square) ? ($width - $square) / 2 : 0, ($height > $square) ? ($height - $square) / 2 : 0, 40, 40, $square, $square);

    // Create and save a medium square thumbnail
    $medium = imagecreatetruecolor(256, 256);
    imagecopyresized($medium, $original, 0, 0, ($width > $square) ? ($width - $square) / 2 : 0, ($height > $square) ? ($height - $square) / 2 : 0, 256, 256, $square, $square);

    switch ($imgType) {
        case IMAGETYPE_JPEG:
            imagejpeg($small, $smallFileName);
            imagejpeg($medium, $mediumFileName);
            break;
        case IMAGETYPE_PNG:
            imagepng($small, $smallFileName);
            imagepng($medium, $mediumFileName);
            break;
    }
}
