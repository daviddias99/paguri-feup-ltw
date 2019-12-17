<?php
include_once('../includes/config.php');
include_once('../templates/common/header.php');
include_once('../templates/common/footer.php');
include_once('../templates/houses.php');

include_once('../database/residence_queries.php');
include_once('../database/comment_queries.php');
include_once('../database/user_queries.php');


$userID = $_GET['id'];
$user = getUserInfoById($userID);
$userLoggedIn = (isset($_SESSION['username']) and $_SESSION['username'] == $user['username']);

if (!$userLoggedIn) {
    header('Location: not_found_page.php?message=' . urlencode("You can't access this page."));
    exit;
}

draw_header('user_rentals', NULL);
draw_user_reservations($userID);
draw_footer();

?>

<?php function draw_user_reservations($userID)
{

    $user = getUserInfoById($userID);
    $reservations = getUserReservations($user['username']);

    ?>

    <section id="user_rentals">

        <h1>My rentals</h1>

        <table>

        <tr>
            <th></th>
            <th>Check-in</th>
            <th>Check-out</th>
            <th>Residence</th>
            <th>Address</th>
            <th>Total Price</th>
            <th></th>
        </tr>

        <?php foreach ($reservations as $reservation) {

            $residence = getResidenceInfo($reservation['lodge']);
            $start = strtotime($reservation['startDate']);
            $end = strtotime($reservation['endDate']);
            $datediff = $end - $start;
            $reservationDays = round($datediff / (60 * 60 * 24)) + 1;
            $comments = getReservationComments($reservation['reservationID']);


            $now = time(); // or your date as well

            $state = "";

            if($now >= $start && $now <= $end){
                $state = "&#128293";
            }
            else if($now >= $end ){
                $state = "&#9989";
            }
            else if(round(($now - $start)/(60 * 60 * 24)) <= 3){
                $state = "&#128284";
            }
            

        ?>
            <tr>
                <td><?=$state?></td>
                <td><?=substr($reservation['startDate'],0,10)?></td>
                <td><?=substr($reservation['endDate'],0,10)?></td>
                <td><a href="view_house.php?id=<?=$residence['residenceID']?>"><?=$residence['title']?></a></td>
                <td><?=$residence['address'] . ', ' . $residence['city'] . ', ' . $residence['country']?> </td>
                <td>€<?=$residence['pricePerDay'] * $reservationDays?></td>
                <td>
                    <?php if($state == "&#9989" && count($comments) == 0) { ?>
                        <form action="write_review.php" method="get" >
                            <input type="hidden" name="id" value="<?=$reservation['reservationID']?>">
                            <input type="submit" value="Review">
                        </form>
                    
                    <?php } else {?>
                        --------
                    <?php } ?>
            
                </td> 
            </tr>
            

        <?php } ?>

        </table>

    </section>

<?php } ?>