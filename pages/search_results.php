<?php
    include_once('../includes/config.php');
    include_once('../templates/common/header.php');
    include_once('../templates/common/footer.php');

    draw_header('search_results',NULL);

?>

<section id="main">
    <section id="left_side">

        <h1>Showing places near '<?=$_GET["location"]?>'</h1>
        <h2>Showing 1256 resuls (Wow!) </h2>

        <section id="filters">
        </section>

        <section id="search_results">

            <section class="result">
                <img src="../resources/house_image_test.jpeg">

                <section class="info">
                    <h1 class="title">Title</h1>
                    <h2 class="type">Type</h2>
                    <h3 class="location">Location</h2>
                    <p class="info_description"> Description</p>
                    <p class="info_ppd"> Price per Day </p>
                </section>

            </section>

            <section class="result">
                <img src="../resources/house_image_test.jpeg">

                <section class="info">
                    <h1 class="title">Title</h1>
                    <h2 class="type">Type</h2>
                    <h3 class="location">Location</h2>
                    <p class="info_description"> Description</p>
                    <p class="info_ppd"> Price per Day </p>
                </section>

            </section>

            <section class="result">
                <img src="../resources/house_image_test.jpeg">

                <section class="info">
                    <h1 class="title">Title</h1>
                    <h2 class="type">Type</h2>
                    <h3 class="location">Location</h2>
                    <p class="info_description"> Description</p>
                    <p class="info_ppd"> Price per Day </p>
                </section>

            </section>

            <section class="result">
                <img src="../resources/house_image_test.jpeg">

                <section class="info">
                    <h1 class="title">Title</h1>
                    <h2 class="type">Type</h2>
                    <h3 class="location">Location</h2>
                    <p class="info_description"> Description</p>
                    <p class="info_ppd"> Price per Day </p>
                </section>

            </section>

            <section class="result">
                <img src="../resources/house_image_test.jpeg">

                <section class="info">
                    <h1 class="title">Title</h1>
                    <h2 class="type">Type</h2>
                    <h3 class="location">Location</h2>
                    <p class="info_description"> Description</p>
                    <p class="info_ppd"> Price per Day </p>
                </section>

            </section>

        </section>

    </section>

    <section id="right_side">
        <section id="map">
            <img src="../resources/map_example.png"/>
        </section>
    </section>
</section>


<?php


    draw_footer();

?>
