<?php function draw_profile($user) { ?>
    <section id='profile'>
        <img id='profile_pic' src="../resources/default-profile-pic.jpg" height="150" width="150">
        <h1>Hello, <?=$user['firstName']?> <?=$user['lastName']?>!</h1>
        <a href="edit_profile.php">Edit profile</a>
        <p><?=$user['biography']?></p>
        <?php if (isset($_SESSION['messages'])) {?>
                <section id="messages">
                <?php foreach($_SESSION['messages'] as $message) { ?>
                    <div class="<?=$message['type']?>"><?=$message['content']?></div>
                <?php } ?>
                </section>
        <?php unset($_SESSION['messages']); } ?>
    </section>
<?php } ?>

<?php function draw_edit_profile($user) { ?>
    <section id="search_box">
        <h1>Update profile information</h1>
        <form action="../actions/action_edit_profile.php" method="post">
            <input id="username" type="hidden" name="username" value=<?= $user['username']?> />
            <label>
                Username <input id="username" type="text" name="newUsername" value=<?= $user['username']?>>
            </label>
            <label>
                Email <input id="email" type="text" name="email" value=<?= $user['email']?>>
            </label>
            <label>
                First name <input id="firstName" type="text" name="firstName" value=<?= $user['firstName']?>>
            </label>
            <label>
                Last name <input id="lastName" type="text" name="lastName" value=<?= $user['lastName']?>>
            </label>
            <label>
                Bio <textarea id="bio" type="text" name="bio" rows="6" cols="80"><?= $user['biography'] ?></textarea>
            </label>
            <label>
                Password <input id="password" type="password" name="password" placeholder="********" value="">
            </label>
            <label>
                Confirm your password <input id="pwConfirmation" type="password" name="pwConfirmation" placeholder="********" value="">
            </label>

            <input id="submit_button" type="submit" value="Update">
        </form>
    </section>
<?php } ?>

