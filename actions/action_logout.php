<?php
    include_once('../includes/config.php');

    session_destroy();

    header('Location: ../pages/front_page.php');
?>