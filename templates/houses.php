<?php
include_once('../database/residence_queries.php');
?>

<?php function draw_add_house()
{ ?>
    <section id="search_box">
        <h1>Add house</h1>
        <form action="../actions/action_add_house.php" method="post">
            <label>
                Title <input id="title" type="text" name="title" value="">
            </label>
            <label>
                Type
                <select id="house_type" name="type">
                    <?php draw_house_type_options() ?>
                </select>
            </label>
            <?php draw_commodity_checkboxes() ?>
            <label>
                Location <input id="location" type="text" name="location" value="">
            </label>
            <!--   <label>
                Description <textarea id="description" type="text" name="description" rows="6" cols="80"></textarea>
            </label>
            <label>
                Capacity <input id="capacity" type="number" name="capacity" value="1" min="0" max="10" step="1">
            </label>
            <label>
                Number of bedrooms <input id="num-bedrooms" type="number" name="num-bedrooms" value="1" min="0" max="10" step="1">
            </label>
            <label>
                Number of bathrooms <input id="num-bathrooms" type="number" name="num-bathrooms" value="1" min="0" max="10" step="1">
            </label>
            <label>
                Number of beds <input id="num-beds" type="number" name="num-beds" value="1" min="0" max="10" step="1">
            </label> -->
            <input id="latitude" type="hidden">
            <input id="longitude" type="hidden">
            <input id="city" type="hidden">
            <input id="country" type="hidden">
            <input id="submit_button" type="submit" value="Add">
        </form>
        <section id="map"></section>
    </section>
<?php } ?>

<?php
function draw_house_type_options()
{
    $types = getResidenceTypes();

    foreach ($types as $type) {
        ?>
        <option value=<?= $type['name'] ?>><?= ucfirst($type['name']) ?></option>
<?php
    }
}
?>


<?php
function draw_commodity_checkboxes()
{
    $commodities = getAllCommodities();

    foreach ($commodities as $commodity) {
        ?>
        <label>
            <input type="checkbox" name="commodities[]" value=<?= $commodity['name'] ?>> <?= ucfirst($commodity['name']) ?>
        </label>
<?php
    }
}
?>


<?php
function draw_list_user_places($userID)
{
    $user = getUserInfoById($userID);
    $userLoggedIn = (isset($_SESSION['username']) and $_SESSION['username'] == $user['username']);
    $places = getUserResidences($userID);
    ?>
    <section id="places_list" class="card">

        <?php if ($userLoggedIn) { ?>
            <h1>My places</h1>
        <?php } else { ?>
            <h1><?= $user['firstName'] ?> <?= $user['lastName'] ?>'s places</h1>
        <?php }
            foreach ($places as $place) { ?>
            <section class="places_list_entry">
                <?php
                        draw_place_summary($place);
                        if ($userLoggedIn)
                            draw_place_operations($place);
                        ?>
            </section>
        <?php } ?>
    </section>
<?php
}
?>

<?php
function draw_place_summary($place)
{
    ?>
    <section class="result">
        <section class="image">
            <img src="../resources/house_image_test.jpeg">
        </section>
        <section class="info">
            <h1 class="info_title"><?= $place['title'] ?> </h1>
            <h2 class="info_type_and_location"><?= $place['type'] . ' &#8226 ' . $place['address'] ?></h2>
            <p class="info_description"> <?= $place['bio'] ?></p>
            <p class="info_ppd"><?= $place['price'] ?></p>
            <p class="info_score">4.5</p>
            <p class="info_capacity"> <?= $place['capacity'] ?></p>
            <p class="info_bedrooms"> <?= $place['nBedrooms'] ?></p>
        </section>
    </section>
<?php
}
?>

<?php
function draw_place_operations($place)
{ ?>
    <section class="place_operations">
        <a class="button" href="">Add availability</a>
        <a class="button" href="">Reservations</a>
        <a class="button" href="">Edit</a>
        <a class="button" href="">Remove</a>
    </section>
<?php } ?>