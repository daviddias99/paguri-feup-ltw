<?php


include_once('../includes/config.php');
include_once('../templates/common/header.php');
include_once('../templates/common/footer.php');
include_once('../templates/map/includes.php');
include_once('../templates/filters/includes.php');
include_once('../templates/search_results_page_elements.php');
include_once('../database/residence_queries.php');
include_once('../database/user_queries.php');


$residence = getResidenceInfo($_GET['id']);
$owner = getUserInfoById($_GET['id']);
$owner_name = $owner['firstName'] . ' ' . $owner['lastName'];
$commodities = getAllCommodities();


if ($residence == FALSE) {

    header('Location: not_found_page.php');
}

draw_header('residence_page');
add_map_includes();
draw();
draw_footer();

?>


<?php function draw()
{

    global $residence, $owner_name;

    ?>

    <section id="main">
        <section id="residence_info">

            <h1><?= $residence['title'] ?></h1>
            <h2><?= $residence['type'] ?> </h2>
            <h3><?= $residence['description'] ?></h3>
            <h4><?= $owner_name ?></h4>
            <section id="residence_location">
                <p><?= $residence['address'] ?></p>
                <p><?= $residence['city'] ?></p>
                <p><?= $residence['country'] ?></p>
            </section>

            <section id="residence_attributes">
                <p><?= "Price per day: " . $residence['pricePerDay'] ?></p>
                <p><?= "Capacity: " . $residence['capacity'] ?></p>
                <p><?= "Number of Beds: " . $residence['nBeds'] ?></p>
                <p><?= "Number of Bedrooms: " . $residence['nBedrooms'] ?></p>
                <p><?= "Number of Bathrooms: " . $residence['nBathrooms'] ?></p>
            </section>

            <section id="residence_commodities">
                Commodities:
                <ul>
                    <?php
                        global $commodities;
                        foreach ($commodities as $commodity) { ?>

                        <li> <?= ucfirst($commodity['name']) ?> </li>
                    <?php } ?>
                </ul>
            </section>

            <section id="images" >
                

            </section>

            <!-- <section id="map">

            </section> -->

        </section>

        <section id="residence_reviews">

        </section>





    </section>

<?php } ?>