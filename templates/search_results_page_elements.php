<?php

include_once('../database/residence_queries.php');

$types = getResidenceTypes();
$commodities = getAllCommodities();
$result_residences = getAllResidences();

function simplifyPrice($price)
{

    if (number_format($price / 1000000000, 2) >= 1)
        return number_format($price / 1000000000, 2) . 'B';
    else if (number_format($price / 1000000, 3) >= 1)
        return number_format($price / 1000000, 2) . 'M';
    else if (number_format($price / 1000, 3) >= 1)
        return number_format($price / 1000, 3) . 'K';
    else
        return $price;
}

?>

<?php

function draw_main()
{ ?>

    <section id="main">
        <?php
            draw_left_side();
            draw_right_side();
            ?>

    </section>

<?php }

function draw_left_side()
{
    // TODO: change this to reflect the search results
    global $result_residences;
    ?>

    <section id="left_side">

        <header>
            <h1>Showing places near '<?= $_GET["location"] ?>'</h1>
            <h2><?= count($result_residences) ?> resuls found (Wow!) </h2>
            <label>
                Location <input id="location" type="text" name="location" value="<?= $_GET['location'] ?>" required>
            </label>
        </header>


        <section id="results">
            <?php
                foreach ($result_residences as $residence) {
                    draw_residence_summary($residence);
                }
                ?>

        </section>

    </section>

<?php }

function draw_right_side()
{
    ?>

    <section id="right_side">

        <?php draw_filters() ?>
        <?php draw_map(); ?>
    </section>
<?php } ?>

<?php function draw_map()
{ ?>
    <section id="map"></section>
<?php } ?>

<?php function draw_filters()
{
    global $types;
    global $commodities;
    ?>
    <section id="filters">

        <h1 id="title">Filters</h1>

        <section id="more_filters">


            <label> Min. Beds: <input id="nBeds" type="number" value="1" min="0" max="10" step="1"> </label>

            <label> Min. Capacity: <input id="capacity" type="number" value="<?= $_GET['guest_cnt'] ?>" min="0" max="10" step="1"> </label>


        </section>

        <section id="dates">
            <label> From: <input id="check_in" type="date" name="checkin_date" placeholder="dd-mm-yyyy" value="" required> </label>
            <label> to:<input id="check_out" type="date" name="checkout_date" placeholder="dd-mm-yyyy" value="" required>
        </section>

        <section id="type">
            <label>
                Type:
                <select id="housing_type" name="type">

                    <?php foreach ($types as $type) { ?>

                        <option value="<?= $type['name'] ?>"> <?= ucfirst($type['name']) ?>

                        <?php } ?>

                </select>
            </label>
        </section>

        <section id="price">
            <label> Price: <input id="minPrice" type="number" value="0" min="1" max="9999999999999" step="1"> </price>
                <label> to:<input id="maxPrice" type="number" value="5000" min="1" max="9999999999999" step="1"> </price>
        </section>

        <section id="rating">
            <label> Rating: <input id="minRating" type="number" value="0" min="0" max="10" step="'0.5"> </label>
            <label> to: <input id="maxRating" type="number" value="10" min="0" max="10" step="0.5"> </label>
        </section>


        <section id="commodities">
            Commodities:
                <?php

                    foreach ($commodities as $commodity) { ?>

                    <label>
                        <input type="checkbox" name="commodity" value="<?= $commodity['name'] ?>"> <?= ucfirst($commodity['name']) ?>
                    </label>

                <?php } ?>
                
        </section>

        <button id="filter_button"> Filter </button>
    </section>
<?php } ?>

<?php function draw_residence_summary($residence)
{
    $descriptionTrimmed = strlen($residence['description']) > 180 ? substr($residence['description'], 0, 180) . "..." : $residence['description'];
    $priceSimple = simplifyPrice($residence['pricePerDay']);

    if ($residence['rating'] == null)
        $residence['rating'] = '-- ';

    ?>

    <section class="result">

        <section class="image">
            <img src="../resources/house_image_test.jpeg">
        </section>

        <section class="info">
            <h1 class="info_title"><?= $residence['title'] ?> </h1>
            <h2 class="info_type_and_location"><?= $residence['type'] . ' &#8226 ' . $residence['address'] ?></h2>
            <p class="info_description"> <?= $descriptionTrimmed ?></p>
            <p class="info_ppd"><?= $priceSimple ?></p>
            <p class="info_score"><?= $residence['rating'] ?></p>
            <p class="info_capacity"> <?= $residence['capacity'] ?></p>
            <p class="info_bedrooms"> <?= $residence['nBedrooms'] ?></p>

        </section>

    </section>
<?php
}
?>