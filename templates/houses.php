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

            <input id="submit_button" type="submit" value="Add">
        </form>
    </section>
<?php } ?>

<?php
    function draw_house_type_options() {
        $types = getResidenceTypes();

        foreach ($types as $type) {
?>
            <option value=<?=$type['name']?>><?=$type['name']?></option>
<?php
        }
    }
?>