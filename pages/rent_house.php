<?php
include_once('../includes/config.php');
include_once('../templates/common/header.php');
include_once('../templates/common/footer.php');
include_once('../templates/map/includes.php');
include_once('../templates/residence_summary/includes.php');
include_once('../templates/date_verification/includes.php');
include_once('../templates/availability_verification/includes.php');
include_once('../templates/filters/includes.php');
include_once('../templates/search_results_page_elements.php');
include_once('../templates/residence_availabilities.php');
include_once('../database/residence_queries.php');

$residenceID = $_GET['residenceID'];
$residence = getResidenceInfo($residenceID);

// Redirect the user if the residence does not exist
if ($residence == FALSE) {
    
    header('Location: not_found_page.php');
}

if (!isset($_SESSION['username'])) {

    header('Location: front_page.php');
}

$owner = getUserInfoById($residence['owner']);

if($owner['username'] == $_SESSION['username']){
    header('Location: front_page.php');
}

$availabilities = getResidenceAvailabilities($residenceID);

draw_header('rent_house', NULL);
add_residence_summary_includes();
add_date_verification_includes();
add_availability_verification_includes();
draw();
draw_footer();
?>

<?php function draw()
{ 
    global $residence;
    ?>

    <section id="main">

        

        <section id="residence_info_simplified">

            <h1>Renting a residence</h1>

            <section id="residence_summary">     

            </section> 


            <h1>Availabilities</h1>

            <?php
                global $residenceID;
                $availabilities = getAvailabilities($residenceID);
            ?>

            <ul>            
                <?php foreach($availabilities as $av){ ?>

                    <li><?=$av['startDate']?> to  <?=$av['endDate']?></li>
                <?php } ?>

            </ul>

            <h1>Almost there...</h1>

            <section id="renting_information">

                <section id="display_price">

                    <h3>Total price: </h3>
                
                </section>

                <form id="rent_form">

                    <input type="hidden" id="residenceID" value="<?=$_GET['residenceID']?>">    
                    <input type="hidden" id="pricePerDay" value="<?=$residence['pricePerDay']?>">    

                    <label>
                        Check-in:
                        <input id="checkin_input" name="checkin_date" required="required" type="date"/>
                    </label>
                    <label>
                        Check-out:
                        <input id="checkout_input"  name="checkout_date" required="required" type="date"/>
                    </label>

                    <input class="button" id="rent_submit_button" type="submit" value="Rent this residence"/>
                </form>
            </section>

        </section>


    </section>

<?php } ?>