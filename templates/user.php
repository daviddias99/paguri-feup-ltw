<?php
function draw_user($user)
{
    $userLoggedIn = isset($_SESSION['username']) and $_SESSION['username'] == $user['username'];
    ?>
    <section class="card" id='profile'>
        <img class='profile_pic' src="../images/users/thumbnails_medium/<?= $user['photo'] ?>">
        <h1><?= $user['firstName'] ?> <?= $user['lastName'] ?></h1>
        <ul id="info">
            <li>
                <em>Username</em>
                <p><?= $user['username'] ?>
            </li>
            <li>
                <em>Email</em>
                <p><?= $user['email'] ?>
            </li>
            <li>
                <em>Password</em>
                <p><?= $user['password'] ?>
            </li>
        </ul>

        <?php if ($userLoggedIn) { ?>
            <a class="button" href="edit_profile.php">Edit profile</a>
        <?php } ?>

        <section id="about_me" />
        <h2>About me</h2>
        <p><?= $user['biography'] ?></p>
    </section>
    </section>
<?php
}
?>

<?php
function draw_edit_profile($user)
{
    ?>
    <section class="card" id="edit_profile">
        <h1>Update profile information</h1>

        <form id="profile_info" action="../actions/action_edit_profile.php" method="post" enctype="multipart/form-data">
            <img id="photo_preview" src=" ../images/users/thumbnails_medium/<?= $user['photo'] ?>" class="profile_pic">

            <input type="hidden" name="username" value=<?= $user['username'] ?> />

            <label id="choose_photo" class="button">Choose
                <input id="choose_photo_input" type="file" name="image" value="unchanged">
            </label>

            <label id="remove_photo" class="button">Remove
            </label>

            <section class="form_entry" id="username">
                <label for="username_input"> Username </label>
                <input id="username_input" type="text" name="newUsername" value=<?= $user['username'] ?>>
            </section>
            <section class="form_entry" id="email">
                <label for="email_input">Email</label>
                <input id="email_input" type="text" name="email" value=<?= $user['email'] ?>>
            </section>
            <section class="form_entry" id="first_name">
                <label for="first_name_input">First name</label>
                <input id="first_name_input" type="text" name="firstName" value=<?= $user['firstName'] ?>>
            </section>
            <section class="form_entry" id="last_name">
                <label for="last_name_input">Last name</label>
                <input id="last_name_input" type="text" name="lastName" value=<?= $user['lastName'] ?>>
            </section>
            <section class="form_entry" id="bio">
                <label for="bio_input">Bio</label>
                <textarea id="bio_input" type="text" name="bio" rows="6"><?= $user['biography'] ?></textarea>
            </section>

            <input class="button" id="submit_button" type="submit" value="Update">
        </form>

        <form id="change_password" action="../actions/action_edit_password.php" method="post">
            <input type="hidden" name="username" value=<?= $user['username'] ?> />
            <section class="form_entry" id="password">
                <label for="password_input">Password</label>
                <input id="password_input" type="password" name="password" value="">
            </section>
            <section class="form_entry" id="pw_conf">
                <label for="pw_conf_input">Confirm password</label>
                <input id="pw_conf_input" type="password" name="pwConfirmation" value="">
            </section>
            <input class="button" type="submit" value="Change password">
        </form>
    </section>
<?php
}
?>