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
    </head>

    <body class=<?=$body_class?>>
        <header id="site_header">
            <a href="front_page.php" id="logo">
                <img src="../resources/logo_temp.png" height="50" width="50">
                <p>paguri</p>
            </a>
            <nav>
                <ul>
                    <?php if ($username == NULL) { ?>
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
                    <?php } else { ?>
                        <li>
                            <a id="my-places" href="../pages/owner_houses.php">
                                My places
                            </a>
                        </li>
                        <li>
                            <a id="username" href="../pages/profile.php">
                                <?= $username ?>
                            </a>
                        </li>
                        <li>
                            <a href="../actions/action_logout.php">
                                Log out
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </nav>
        </header>
    <?php } ?>
