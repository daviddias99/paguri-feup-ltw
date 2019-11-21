<?php function draw_header($body_class)
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
            <a href="index.php" id="logo">
                <img src="resources/logo_tmp.png" height="50" width="50">
                <p>paguri</p>
            </a>
            <section id="signup">
                <a href="pages/login.php">
                    Log in
                </a>
                <a href="pages/register.php">
                    Register
                </a>
            </section>
        </header>
    <?php } ?>
