<?php
    //session_set_cookie_params(0, '/', '.fe.up.pt', true, true);
    session_start();
    if (!isset($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
?>