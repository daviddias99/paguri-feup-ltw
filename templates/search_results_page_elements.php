<?php

include_once('../database/residence_queries.php');


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

<?php function draw_residence_summary($residence)
{
    $descriptionTrimmed = strlen($residence['description']) > 180 ? substr($residence['description'], 0, 180) . "..." : $residence['description'];
    $priceSimple = simplifyPrice($residence['pricePerDay']);
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
            <p class="info_score">4.5</p>
            <p class="info_capacity"> <?= $residence['capacity'] ?></p>
            <p class="info_bedrooms"> <?= $residence['nBedrooms'] ?></p>

        </section>

    </section>
<?php
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
    $result_residences = getAllResidences();
    ?>

    <section id="left_side">

        <header>
            <h1>Showing places near '<?= $_GET["location"] ?>'</h1>
            <h2><?= count($result_residences) ?> resuls found (Wow!) </h2>
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

    $types = getResidenceTypes();
    $commodities = getAllCommodities();

    ?>

    <section id="right_side">
        <section id="filters">
            
            <h1 id="title">Filters</h1>

            <section id="more_filters">

                <label> Beds: <input id="nBeds" type="number" value="" min="0" max="10" step="1"> </label>

                <label> Capacity: <input id="capacity" type="number" value="<?= $_GET['guest_cnt'] ?>" min="0" max="10" step="1"> </label>


            </section>

            <section id="dates">
                <label> From: <input id="check_in" type="date" name="checkin_date" placeholder="dd-mm-yyyy" value="" required> </label>
                <label> to:<input id="check_out" type="date" name="checkout_date" placeholder="dd-mm-yyyy" value="" required>
            </section>

            <section id="type">
                <label>
                    Type:
                    <select id="type" name="type">

                        <?php foreach ($types as $type) { ?>

                            <option value="<?= $type['name'] ?>"> <?= ucfirst($type['name']) ?>

                            <?php } ?>

                    </select>
                </label>
            </section>

            <section id="price">
                <label> Price: <input id="maxPrice" type="number" value="" min="0" max="9999999999999" step="1"> </price>
                    <label> to:<input id="minPrice" type="number" value="" min="0" max="9999999999999" step="1"> </price>
            </section>

            <section id="rating">
                <label> Rating: <input id="minRating" type="number" value="" min="0" max="10" step="'0.5"> </label>
                <label> to: <input id="maxRating" type="number" value="" min="0" max="10" step="0.5"> </label>
            </section>


            <section id="commodities">
                <label> Commodities:
                    <?php

                        foreach ($commodities as $commodity) { ?>

                        <input type="checkbox" name="comomdity" value="<?= $commodity['name'] ?>"> <?= ucfirst($commodity['name']) ?>

                    <?php } ?>
                    <label>
            </section>

            <button> Filter </button>
        </section>
        <section id="map">
        </section>
    </section>
<?php }

?>