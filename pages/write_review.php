<?php
include_once('../includes/config.php');
include_once('../templates/common/header.php');
include_once('../templates/common/footer.php');
include_once('../templates/houses.php');

include_once('../database/residence_queries.php');
include_once('../database/user_queries.php');


$reservationID = $_GET['id'];

$reservation = getReservationWithID($reservationID);
$user = getUserInfoById($reservation['customer']);
$userLoggedIn = (isset($_SESSION['username']) and $_SESSION['username'] == $user['username']);

if (!$userLoggedIn) {
    header('Location: not_found_page.php?message=' . urlencode("You can't access this page."));
    exit;
}

$residence = getResidenceInfo($reservation['lodge']);

draw_header('write_review', NULL);
draw_review_form($residence,$reservation);
draw_footer();

?>

<?php function draw_review_form($residence,$reservation) 
{ ?>

    <section id="review_form">

        <h1>Writing a review for the residence '<?=$residence['title']?>'</h1>

        <form method="get" action="../actions/action_add_review.php">
        
            <input type="hidden" name="reservationID" value="<?=$reservation['reservationID']?>">
            <input type="text" placeholder="Title" name="review_title" required="required"> 
            <textarea class="comment_content" placeholder="What's on you mind?" required="required" name="content" rows="4" cols="50"></textarea>
            <input class="comment_rating"  type="number" value="" min="0" max="10" step="1" name="rating" required="required" placeholder="Rating">
            <input class="submit_reply" type="submit" value="Review">
        </form>

    </section>

<?php } ?>