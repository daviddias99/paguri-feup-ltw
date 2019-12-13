<?php

    include_once('../database/residence_queries.php');

    $types = getResidenceTypes();
    $commodities = getAllCommodities();

    $location = "";
    $checkin = "";
    $checkout = "";
    $guest_count = "";
    $min_beds = "";
    $min_price = "";
    $max_price = "";
    $min_rating = "";
    $max_rating = "";
    $type_filter = "";
    $commodities_filters = array();

    if (array_key_exists('location', $_GET)) $location = htmlspecialchars($_GET['location'], ENT_QUOTES);
    if (array_key_exists('checkin', $_GET)) $checkin = htmlspecialchars($_GET['checkin']);
    if (array_key_exists('checkout', $_GET)) $checkout = htmlspecialchars($_GET['checkout']);
    if (array_key_exists('guest_count', $_GET)) $guest_count = htmlspecialchars($_GET['guest_count']);
    if (array_key_exists('min_beds', $_GET)) $min_beds = htmlspecialchars($_GET['min_beds']);
    if (array_key_exists('min_price', $_GET)) $min_price = htmlspecialchars($_GET['min_price']);
    if (array_key_exists('max_price', $_GET)) $max_price = htmlspecialchars($_GET['max_price']);
    if (array_key_exists('min_rating', $_GET)) $min_rating = htmlspecialchars($_GET['min_rating']);
    if (array_key_exists('max_rating', $_GET)) $max_rating = htmlspecialchars($_GET['max_rating']);
    if (array_key_exists('type', $_GET)) $type_filter = htmlspecialchars($_GET['type']);
    if (array_key_exists('commodities', $_GET)) $commodities_filters = explode(",", $_GET['commodities']);
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

    global $location;

    ?>

    <section id="left_side">

        <header id="results_header">
            <h1></h1>
            <h2></h2>
            <label> Location 
                <input id="location" type="text" name="location" value="<?= $location ?>" required>
            </label>
            <button id="search_button">Search</button>

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

    global $checkin;
    global $checkout;
    global $guest_count;
    global $min_beds;
    global $min_price;
    global $max_price;
    global $min_rating;
    global $max_rating;
    global $type_filter;
    global $commodities_filters;

    ?>
    <section id="filters">

        <h1 id="title">Filters</h1>

        <section id="more_filters">


            <label> Min. Beds: <input id="nBeds" type="number" value="<?=$min_beds?>" min="0" max="10" step="1"> </label>

            <label> Min. Capacity: <input id="capacity" type="number" value="<?=$guest_count?>" min="0" max="10" step="1"> </label>


        </section>

        <section id="dates">
            <label> From: <input id="check_in" type="date" name="checkin_date" placeholder="dd-mm-yyyy" value="<?= $checkin ?>" required> </label>
            <label> to:<input id="check_out" type="date" name="checkout_date" placeholder="dd-mm-yyyy" value="<?= $checkout ?>" required>
        </section>

        <section id="type">
            <label>Type:
                <select id="housing_type" name="type">

                    <?php foreach ($types as $type) { ?>

                        <option 
                            value="<?= $type['name'] ?>"
                            <?= $type['name'] === $type_filter ? "selected" : "" ?>> 
                            <?= ucfirst($type['name']) ?>

                        <?php } ?>

                </select>
            </label>
        </section>

        <section id="price">
            <label> Price: <input id="minPrice" type="number" value="<?=$min_price?>" min="1" max="9999999999999" step="1"> </price>
                <label> to:<input id="maxPrice" type="number" value="<?=$max_price?>" min="1" max="9999999999999" step="1"> </price>
        </section>

        <section id="rating">
            <label> Rating: <input id="minRating" type="number" value="<?=$min_rating?>" min="0" max="10" step="0.5"> </label>
            <label> to: <input id="maxRating" type="number" value="<?=$max_rating?>" min="0" max="10" step="0.5"> </label>
        </section>


        <section id="commodities">
            Commodities:
            <?php
                foreach ($commodities as $commodity) { ?>

                <label>
                    <input 
                        type="checkbox" 
                        name="commodity" 
                        value="<?= $commodity['name'] ?>" 
                        <?= in_array($commodity['name'], $commodities_filters) ? "checked" : "" ?>>
                        <?= ucfirst($commodity['name']) ?>
                </label>

            <?php } ?>

        </section>

        <button id="filter_button"> Filter </button>
    </section>
<?php } ?>
