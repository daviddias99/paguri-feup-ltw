
<?php function draw_search_box() { ?>

    <section id="search_box" class="formCard">
        <h1>Your dream vacation, one click away... And also an html form filling away.</h1>
        <form action="search_results.php" method="get">
            <label>
                Where to? <input id="location" type="text" name="location" placeholder="Anywhere" required>
            </label>

            <section id="dates">
                <label>
                    Check-in <input id="check_in" type="date" name="checkin_date" placeholder="dd-mm-yyyy" value="" required>
                </label>

                <label>
                    Checkout <input id="check_out" type="date" name="checkout_date" placeholder="dd-mm-yyyy" value="" required>
                </label>
            </section>

            <label>
                Guests <input id="guest_cnt" type="number" name="guest_cnt" value="1" min="0" max="10" step="1">
            </label>

            <input id="submit_button" type="submit" value="SEARCH">
        </form>

    </section>

<?php } ?> 
