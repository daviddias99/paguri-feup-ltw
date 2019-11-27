<?php

include_once('../database/residence_queries.php');

?>

<?php function draw_residence_summary($residence)
{

    $typeStr = getResidenceTypeWithID($residence['type']);

    ?>

    <section class="result">
        <img src="../resources/house_image_test.jpeg">

        <section class="info">
            <h1 class="info_title"><?= $residence['title'] ?> </h1>
            <h2 class="info_type"><?= $typeStr ?></h2>
            <h3 class="info_location"><?= $residence['location'] ?></h3>
            <p class="info_description"> <?= $residence['description'] ?></p>
            <p class="info_ppd"><?= $residence['pricePerDay'] ?> </p>
            <p class="info_score">SCORE WIP </p>
            <p class="info_capacity"> <?= $residence['capacity'] ?></p>
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
            <h2>Showing <?= count($result_residences) ?> resuls (Wow!) </h2>
        </header>

        <section id="search_results">
            <?php
                foreach ($result_residences as $residence) {
                    draw_residence_summary($residence);
                }
                ?>

        </section>

    </section>

<?php }

function draw_right_side()
{ ?>

    <section id="right_side">
        <section id="filters">
            <section id="comodities">

            </section>
        </section>
        <section id="map">
            <img src="../resources/map_example.png" />
        </section>
    </section>
<?php }

?>