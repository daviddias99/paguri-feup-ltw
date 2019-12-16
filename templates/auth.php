<?php function draw_login()
{ ?>
    <section id="login" class="formCard">
        <h1>Login</h1>
        <form action="../actions/action_login.php" method="post">

            <section class="form_entry">
                <label for="username_input">Username</label>
                <input id="username_input" type="text" name="username" placeholder="username" value="">
            </section>
            <section class="form_entry">
                <label>Password</label>
                <input id="check_out" type="password" name="password" placeholder="********" value="">
            </section>

            <input class="button" id="submit_button" type="submit" value="Login">

        </form>
        <p>Don't have an account? Register <a href="register.php">here</a>.</p>
    </section>
<?php } ?>

<?php function draw_register()
{ ?>
    <section id="register" class="formCard">
        <h1>Register</h1>
        <form action="../actions/action_register.php" method="post">
            <section id="username" class="form_entry">
                <label for="username_input">Username</label>
                <input id="username_input" type="text" name="username" placeholder="johndoe" value="">
            </section>
            <section id="email" class="form_entry">
                <label for="email_input">Email</label>
                <input id="email_input" type="text" name="email" placeholder="hello@johndoe.com" value="">
            </section>
            <section id="first_name" class="form_entry">
                <label for="first_name_input">First name</label>
                <input id="first_name_input" type="text" name="firstName" placeholder="John" value="">
            </section>
            <section id="last_name" class="form_entry">
                <label for="last_name_input">Last name</label>
                <input id="last_name_input" type="text" name="lastName" placeholder="Doe" value="">
            </section>
            <section id="password" class="form_entry">
                <label for="passowrd_input">Password</label>
                <input id="password_input" type="password" name="password" placeholder="********" value="">
            </section>
            <section id="conf_password" class="form_entry">
                <label for="pw_conf_input">Confirm password</label>
                <input id="pw_conf_input" type="password" name="passwordConfirmation" placeholder="********" value="">
            </section>
            <input class="button" id="submit_button" type="submit" value="Register">
        </form>
        <p>Already have an account? Log in <a href="login.php">here</a>.</p>
    </section>
<?php } ?>