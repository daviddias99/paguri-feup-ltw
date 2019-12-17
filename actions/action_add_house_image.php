<?php
    include_once('../includes/config.php');
    include_once('../database/residence_queries.php');

    if(!isset($_SESSION['userID'])) die();
    if(!isset($_POST['id'])) die();
    if(!isset($_FILES['image']['tmp_name'])) die();

    $residenceID = $_POST['id'];
    $residenceInfo = getResidenceInfo($residenceID);

    // must be the owner
    if ($_SESSION['userID'] != $residenceInfo['owner']) die();

    $image = $_FILES['image']['tmp_name'];

    $supportedFormats = array(IMAGETYPE_JPEG => '.jpg', IMAGETYPE_PNG => '.png');

    $imgType = exif_imagetype($image);

    if (! isset($suppertedFormats[$imgType]))
        die();

    $extension = $supportedFormats[$imgType];


    // Generate filenames for original, small and medium files
    $photoID = addResidencePhoto($residenceID, sha1_file($image) . $extension);
    $originalPath = "../images/properties/originals/$photoID";
    $smallPath = "../images/properties/small/$photoID";
    $mediumPath = "../images/properties/medium/$photoID";
    $bigPath = "../images/properties/big/$photoID";

    // Move the uploaded file to its final destination
    move_uploaded_file($image, $originalPath);

    // Create an image representation of the original image
    switch ($imgType) {
        case IMAGETYPE_JPEG:
            $original = imagecreatefromjpeg($originalPath);
            break;
        case IMAGETYPE_PNG:
            $original = imagecreatefrompng($originalPath);
            break;
    }


    $small = resizeImage($original, 250, 150);
    $medium = resizeImage($original, 300, 180);
    $big = resizeImage($original, 900, 534);


    switch ($imgType) {
        case IMAGETYPE_JPEG:
            imagejpeg($small, $smallPath, 100);
            imagejpeg($medium, $mediumPath, 100);
            imagejpeg($big, $bigPath, 100);
            break;
        case IMAGETYPE_PNG:
            imagepng($small, $smallPath);
            imagepng($medium, $mediumPath);
            imagepng($big, $bigPath);
            break;
    }



    function resizeImage($original, $dstWidth, $dstHeight) {

        $width = imagesx($original);     // width of the original image
        $height = imagesy($original);    // height of the original image

        $widthRatio = $width / $dstWidth;
        $heightRatio = $height / $dstHeight;
        $ratio = $heightRatio > $widthRatio ? $widthRatio : $heightRatio;

        $dstX = 0;
        $dstY = 0;

        $srcWidth = $width * $ratio / $widthRatio;
        $srcHeight = $height * $ratio / $heightRatio;

        $srcX = $heightRatio >= $widthRatio ? 0 : ($width - $srcWidth) / 2;
        $srcY = $widthRatio >= $heightRatio ? 0 : ($height - $srcHeight) / 2;


        $new = imagecreatetruecolor($dstWidth, $dstHeight);
        imagecopyresampled($new, $original, $dstX, $dstY, $srcX, $srcY, $dstWidth, $dstHeight, $srcWidth, $srcHeight);

        return $new;
    }
?>