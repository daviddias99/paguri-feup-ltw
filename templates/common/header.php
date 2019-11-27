<?php function draw_header($body_class, $username)
{ ?>
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
        <link href="../css/front_page.css" rel="stylesheet">
    </head>

    <body class=<?=$body_class?>>
        <header>
            <a href="front_page.php" id="logo">
<<<<<<< HEAD
                <img src="../resources/logo_tmp.png" height="50" width="50">
=======
                <img src="../resources/logo_temp.png" height="50" width="50">
>>>>>>> 2ced6dd09ef17b6ab71cd09ec5f818e4532f7590
                <p>paguri</p>
            </a>
            <section id="signup">
                <?php if ($username == NULL) { ?>
                    <a href="login.php">
                        Log in
                    </a>
                    <a href="register.php">
                        Register
                    </a>
                <?php } else { ?>
                    <a id="username" href="../pages/profile.php">
                        <?= $username ?>
                    </a>
                    <a href="../actions/action_logout.php">
                        Log out
                    </a>
                <?php } ?>
            </section>
        </header>
    <?php } ?>
