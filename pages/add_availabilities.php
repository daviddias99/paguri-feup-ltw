<?php
    include_once('../includes/config.php');
    include_once('../templates/date_verification/includes.php');
    include_once('../templates/common/header.php');
    include_once('../templates/common/footer.php');
    include_once('../templates/page_not_found_elements.php');
    include_once('../templates/page_not_found_elements.php');
    include_once('../database/residence_queries.php');
    include_once('../database/user_queries.php');

    $residenceID = $_GET['residenceID'];
    $residence = getResidenceInfo($residenceID);
    $owner = getUserInfoById($residence['owner']);
    $valid = (isset($_SESSION['username']) and $_SESSION['username'] == $owner['username']);

if (!$valid) {
    header('Location: not_found_page.php?message=' . urlencode("You can't access this page."));
    exit;
}

    draw_header('choose_availabilities',NULL);
    add_date_verification_includes();
    draw($residence);

    draw_footer();
?>

<?php function draw($residence) { ?>

    <section id="choose_availabilities">

    <h1>Add availabilities for residence:'<?=$residence['title']?>'</h1>

        <form method="get" action="../actions/action_add_availability.php">

            
            <input type="hidden" name="residenceID" id="residenceID" value="<?= htmlentities($residence['residenceID']) ?>">

            <label>
                Start:
                <input id="checkin_input" name="checkin_date" required="required" type="date" />
            </label>
            <label>
                End:
                <input id="checkout_input" name="checkout_date" required="required" type="date" />
            </label>

            <input class="button" type="Submit" value="Add" />

        </form>

    </section>

<?php } ?>
