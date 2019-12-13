<?php

include_once('../database/residence_queries.php');

$types = getResidenceTypes();
$commodities = getAllCommodities();


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
    ?>

    <section id="left_side">

        <header id="results_header">
            <h1></h1>
            <h2></h2>
            <label>
                Location <input id="location" type="text" name="location" value="<?= $_GET['location'] ?>" required>
            </label>
            <button id="search_button">
                Search
            </button>

        </header>


        <section id="results">

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
            <label> Rating: <input id="minRating" type="number" value="0" min="0" max="10" step="0.5"> </label>
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
