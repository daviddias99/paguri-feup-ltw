<?php
include_once('../database/user_queries.php');

function draw_header($body_class, $scripts)
{
    $loggedIn = isset($_SESSION['username']);

    if ($loggedIn) {
        $username = $_SESSION['username'];
    }
    ?>
    <!DOCTYPE html>
    <html lang="en-US">

    <head>
        <title>Paguri</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://use.fontawesome.com/releases/v5.0.1/css/all.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Lato|Nunito&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed&display=swap" rel="stylesheet">
        <link href="../css/style.css" rel="stylesheet">
        <link href="../css/responsive.css" rel="stylesheet">
        <?php if ($scripts != null) foreach ($scripts as $script) { ?>
            <script src="../js/<?= $script ?>" defer></script>
        <?php } ?>
    </head>

    <body class=<?= $body_class ?>>
        <nav class="navbar">
            <a href="front_page.php" class="logo">
                <img src="../resources/logo_temp.png" height="50" width="50">
                <p>paguri</p>
            </a>
            <?php
                if ($loggedIn) {
                    draw_logged_in_nav($username);
                } else {
                    draw_logged_out_nav();
                }
                ?>
        </nav>
    <?php
    }
    ?>

    <?php
    function draw_logged_in_nav($username)
    {
        $photo = getUserPhotoID($username);
        $photoPath = "../images/users/thumbnails_small/$photo";
        ?>
        <input type="checkbox" class="hamburger-btn" id="hamburger-btn" />
        <label class="hamburger" for="hamburger-btn"><span class="hamburger-icon"></span></label>
        <ul class="menu">
            <li>
                <a id="username" href="../pages/user.php?id=<?= $username ?>">
                    <img class="profile_pic" src=<?= $photoPath ?> alt="<?= $username ?>'s profile picture." />
                    <?= $username ?>
                </a>
            </li>
            <li>
                <a href="../pages/owner_houses.php">
                    My places
                </a>
            </li>
            <li>
                <a href="../actions/action_logout.php">
                    Log out
                </a>
            </li>
        </ul>
    <?php
    }
    ?>

    <?php
    function draw_logged_out_nav()
    {
        ?>
        <input type="checkbox" class="hamburger-btn" id="hamburger-btn" />
        <label class="hamburger" for="hamburger-btn"><span class="hamburger-icon"></span></label>
        <ul class="menu">
            <li>
                <a href="login.php">
                    Log in
                </a>
            </li>
            <li>
                <a href="register.php">
                    Register
                </a>
            </li>
        </ul>
    <?php
    }
    ?>