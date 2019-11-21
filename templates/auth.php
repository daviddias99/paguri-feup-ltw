<?php function draw_login() { ?>
    <section id="search_box">
        <h1>Login</h1>
        <form action="../actions/action_login.php" method="post">

            <label>
                Username <input id="check_in" type="text" name="username" placeholder="username" value="">
            </label>
            <label>
                Password <input id="check_out" type="password" name="password" placeholder="********" value="">
            </label>

            <input id="submit_button" type="submit" value="Login">
        </form>
    </section>
<?php } ?>

<?php function draw_register() { ?>
    <section id="search_box">
        <h1>Register</h1>
        <form action="" method="post">
            <label>
                Username <input id="username" type="text" name="username" placeholder="johndoe69" value="">
            </label>
            <label>
                First name <input id="firstName" type="text" name="firstName" placeholder="John" value="">
            </label>
            <label>
                Last name <input id="lastName" type="text" name="lastName" placeholder="Doe" value="">
            </label>
            <label>
                Date of birth <input id="birth" type="date" name="birth" value="">
            </label>
            <label>
                Password <input id="password" type="password" name="password" placeholder="********" value="">
            </label>

            <input id="submit_button" type="submit" value="Register">
        </form>
    </section>
<?php } ?>