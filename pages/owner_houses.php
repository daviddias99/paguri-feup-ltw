<?php
    include_once('../includes/config.php');
    include_once('../templates/common/header.php');
    include_once('../templates/common/footer.php');

    if (! isset($_SESSION['username']))
        die("User not logged in!");

    $username = $_SESSION['username'];

    draw_header('search_results', $username);
?>

    <a href="add_house.php">Add house</a>

<?php
    draw_footer();
?>
