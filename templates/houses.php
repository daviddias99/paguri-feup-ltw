<?php
    include_once('../database/residence_queries.php');
?>

<?php function draw_add_house() { ?>
    <section id="search_box">
        <h1>Add house</h1>
        <form action="../actions/action_add_house.php" method="post">
            <label>
                Title <input id="title" type="text" name="title" value="">
            </label>
            <label>
                Type
                <select name="type">
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
            <input id="submit_button" type="submit" value="Add">
        </form>
    </section>
<?php } ?>

<?php
    function draw_house_type_options() {
        $types = getResidenceTypes();

        foreach ($types as $type) {
?>
            <option value=<?=$type['name']?>><?=ucfirst($type['name'])?></option>
<?php
        }
    }
?>


<?php
    function draw_commodity_checkboxes() {
        $commodities = getAllCommodities();

        foreach ($commodities as $commodity) {
?>
            <label>
                <input type="checkbox" name="commodities[]" value=<?=$commodity['name']?>> <?=ucfirst($commodity['name'])?>
            </label>
<?php
        }
    }
?>