<?php function draw_availabilities($residence)
{

    $availabilities = getAvailabilities($residence['residenceID']);
    ?>
    <ul>
        <?php foreach ($availabilities as $av) { ?>

            <li><?= htmlentities($av['startDate']) ?> to <?= htmlentities($av['endDate']) ?></li>
        <?php } ?>

    </ul>

<?php } ?>


<?php function draw_rent_form($residence)
{ ?>

    <form id="rent_form" action="../actions/action_add_reservation.php" method="get">

        <input type="hidden" name="residenceID" id="residenceID" value="<?= htmlentities($residence['residenceID']) ?>">
        <input type="hidden" id="pricePerDay" value="<?= htmlentities($residence['pricePerDay']) ?>">

        <label>
            Check-in:
            <input id="checkin_input" name="checkin_date" required="required" type="date" />
        </label>
        <label>
            Check-out:
            <input id="checkout_input" name="checkout_date" required="required" type="date" />
        </label>
        <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">

        <input class="button disabled" id="rent_submit_button" type="submit" value="Rent this residence" />
    </form>
<?php } ?>

<?php function draw_main($residence)
{ ?>


    <section id="residence_info_simplified" class="card">

        <h1>Renting a residence</h1>

        <section id="residence_summary">

        </section>

        <h1>Availabilities</h1>

        <?php draw_availabilities($residence) ?>

        <h1>Almost there...</h1>

        <section id="renting_information">

            <section id="display_price">

                <h3>Total price: </h3>

            </section>

            <?php draw_rent_form($residence) ?>


        </section>

    </section>


<?php } ?>