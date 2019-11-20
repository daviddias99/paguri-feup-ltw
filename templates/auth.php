<?php function draw_login() { ?>
    <section id="search_box">
        <h1>Login</h1>
        <form action="" method="post">

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