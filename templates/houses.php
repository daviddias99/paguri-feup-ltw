<?php function draw_house_entry() { ?>
    <article>
        <header>
            <h1><a href="index.php"?></a>Casa no campo</h1>
        </header>
        <p> Casa no campo, super confortavel </p>
    </article>
<?php } ?>

<?php function draw_house_list() { ?>
    <section id="listing">
        <?php draw_house_entry() ?>
        <?php draw_house_entry() ?>
        <?php draw_house_entry() ?>
    </section>
<?php } ?>