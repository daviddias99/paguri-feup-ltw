<?php
include_once('../database/residence_queries.php');
include_once('../database/user_queries.php');
include_once('../templates/helper_functions.php');
?>

<?php function draw_add_house()
{ ?>
    <section id="add_place" class="card">
        <h1>Add place</h1>
        <form id="add_place_form" action="" method="post">
            <input id="user_id" type="hidden" value=<?= htmlentities($_SESSION['userID']) ?>>
            <section class="form_entry" id="title">
                <label for="title_input">Title</label>
                <input id="title_input" type="text" name="title" value="">
            </section>
            <section class="form_entry" id="house_type">
                <label for="house_type_input">Type</label>
                <select id="house_type_input" name="type">
                    <?php draw_house_type_options() ?>
                </select>
            </section>
            <section class="form_entry" id="price">
                <label for="price_input">Price per night</label>
                <input type="number" id="price_input" value="1" min="1">
            </section>
            <section class="form_entry" id="description">
                <label for="description_input">Description</label>
                <textarea id="description_input" type="text" name="description" rows="6"></textarea>
            </section>
            <section class="form_entry" id="location_wrapper">
                <label for="location">Location</label>
                <input id="location" type="text" name="location" value="">
            </section>
            <section id="map"></section>
            <section class="form_entry" id="commodities">
                <label>Commodities</label>
                <section id="commodity_list">
                    <?php draw_commodity_checkboxes() ?>
                </section>
            </section>
            <section class="form_entry" id="capacity">
                <label for="capacity_input">Capacity</label>
                <input id="capacity_input" type="number" name="capacity" value="1" min="0" max="10" step="1">
            </section>
            <section class="form_entry" id="num_bedrooms">
                <label for="num_bedrooms_input">Number of bedrooms </label>
                <input id="num_bedrooms_input" type="number" name="num-bedrooms" value="1" min="0" max="10" step="1">
            </section>
            <section class="form_entry" id="num_bathrooms">
                <label for="num_bathrooms_input">Number of bathrooms</label>
                <input id="num_bathrooms_input" type="number" name="num-bathrooms" value="1" min="0" max="10" step="1">
            </section>
            <section class="form_entry" id="num_beds">
                <label for="num_beds_input">Number of beds</label>
                <input id="num_beds_input" type="number" name="num-beds" value="1" min="0" max="10" step="1">
            </section>

            <input id="latitude" type="hidden">
            <input id="longitude" type="hidden">
            <input id="city" type="hidden">
            <input id="country" type="hidden">
            <input class="button" id="submit_button" type="submit" value="Add">
        </form>
    </section>
<?php } ?>

<?php
    function draw_house_type_options()
    {
        $types = getResidenceTypes();

    foreach ($types as $type) {
        ?>
        <option value=<?= htmlentities($type['residenceTypeID']) ?>><?= htmlentities(ucfirst($type['name'])) ?></option>
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
            <input type="checkbox" class="commodities" name="commodities[]" value="<?= htmlentities($commodity['commodityID']) ?>"> <?= htmlentities(ucfirst($commodity['name'])) ?>
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
            <div class="my_places_title">
                <h1>My places</h1>
                <a class="button" href="./add_house.php">Add place</a>
            </div>
        <?php } else { ?>
            <h1><?= htmlentities($user['firstName']) ?> <?= htmlentities($user['lastName']) ?>'s places</h1>
        <?php }
            if ($places == 0) {
        ?>
            <p class="empty_message">No listed places yet.</p>
            <?php } else {

            foreach ($places as $place) {
                $info =  getResidenceInfo($place['residenceID']);
                ?>
                <section class="places_list_entry">
                    <?php
                        draw_place_summary($info);
                        if ($userLoggedIn)
                            draw_place_operations($info);
                    ?>
                </section>
        <?php }
            } ?>
    </section>
<?php
    }
?>

<?php
    function draw_place_summary($place) {
        $descriptionTrimmed = strlen($place['description']) > 180 ? substr($place['description'], 0, 180) . "..." : $place['description'];
        $priceSimple = simplifyPrice($place['pricePerDay']);
        $rating = $place['rating'] == 0 ? '--' : sprintf('%0.2f', $place['rating'] / 2);

        $photos = getResidencePhotos($place['residenceID']);
        $photoPath = sizeof($photos) > 0 ? "../images/properties/medium/".$photos[0]['filepath'] : "../resources/medium-none.jpg";
?>
        <a href="../pages/view_house.php?id=<?= $place['residenceID'] ?>">
            <section class="result">
                <section class="image">
                    <img src="<?= htmlentities($photoPath) ?>">
                </section>
                <section class="info">
                    <h1 class="info_title"><?= htmlentities($place['title']) ?></h1>
                    <h2 class="info_type_and_location"><?= htmlentities(ucfirst($place['type'])) ?>  &#8226  <?= htmlentities($place['address']) ?></h2>
                    <p class="info_description"><?= htmlentities($descriptionTrimmed) ?></p>
                    <p class="info_ppd"><?= htmlentities($priceSimple) ?></p>
                    <p class="info_score"><?= htmlentities($rating) ?></p>
                    <p class="info_capacity"><?= htmlentities($place['capacity']) ?></p>
                    <p class="info_bedrooms"><?= htmlentities($place['nBedrooms']) ?></p>
                </section>
            </section>
        </a>
<?php
    }
?>

<?php
    function draw_place_operations($place) {
?>
    <section class="place_operations">
        <input type="hidden" value=<?= htmlentities($place['residenceID']) ?>>
        <a class="button" id="" href="">Add availability</a>
        <a class="button" href="">Reservations</a>
        <a class="button" href="../pages/edit_place.php?id=<?= $place['residenceID']?>">Edit</a>
        <button class="button remove_reservation">Remove</button>
    </section>
<?php } ?>


<?php
function draw_reservations($reservations)
{
?>
    <section id="user_reservations" class="card">
        <h1>My reservations</h1>
    </section>
<?php
}
?>



<?php
function draw_edit_place($place)
{
    $photos = getResidencePhotos($place['residenceID']);
?>
    <section class="card" id="edit_place">
        <h1>Edit place information</h1>

        <form id="edit_place_form" action="" method="">
            <section class="form_entry" id="title">
                <label for="title_input">Title</label>
                <input id="title_input" type="text" name="title" value="<?= htmlentities($place['title']) ?>">
            </section>
            <section class="form_entry" id="description">
                <label for="description_input">Description</label>
                <textarea id="description_input" type="text" name="description" rows="6"><?= htmlentities($place['description']) ?></textarea>
            </section>

            <input id="user_id" type="hidden" value="<?= htmlentities($place['owner']) ?>">
            <input id="place_id" type="hidden" value="<?= htmlentities($place['residenceID']) ?>">
            <input id="commodities" type="hidden" value="<?= htmlentities(getCommoditiesAsKeysString($place['residenceID'])) ?>">
            <input id="capacity" type="hidden" value="<?= htmlentities($place['capacity']) ?>">
            <input id="num_bathrooms" type="hidden" value="<?= htmlentities($place['nBathrooms']) ?>">
            <input id="num_bedrooms" type="hidden" value="<?= htmlentities($place['nBedrooms']) ?>">
            <input id="num_beds" type="hidden" value="<?= htmlentities($place['nBeds']) ?>">
            <input id="location" type="hidden" value="<?= htmlentities($place['address']) ?>">
            <input id="price" type="hidden" value="<?= htmlentities($place['pricePerDay']) ?>">
            <input id="type" type="hidden" value="<?= htmlentities($place['residenceTypeID']) ?>">
            <input id="latitude" type="hidden" value="<?= htmlentities($place['latitude']) ?>">
            <input id="longitude" type="hidden" value="<?= htmlentities($place['longitude']) ?>">
            <input id="city" type="hidden" value="<?= htmlentities($place['country']) ?>">
            <input id="country" type="hidden" value="<?= htmlentities($place['city']) ?>">

            <section id="edit_place_images">
                <?php foreach ($photos as $photo) { ?>
                    <section class="image_preview" id="<?= $photo['photoID'] ?>">
                        <img src="../images/properties/medium/<?= $photo['filepath'] ?>">
                        <div class="remove_image fas fa-trash-alt"></div>
                    </section>
                <?php } ?>
                <label class="choose_photo button fas fa-plus">
                    <input class="choose_photo_input" type="file" name="image" multiple>
                </label>
            </section>

            <input class="button" id="submit_button" type="submit" value="Update">
        </form>
    </section>
<?php
}
?>