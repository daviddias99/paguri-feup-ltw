<?php
    function draw_user($user) {
        $userLoggedIn = isset($_SESSION['username']) and $_SESSION['username'] == $user['username'];
?>
        <section class="card" id='profile'>
            <img class='profile_pic' src="../images/users/thumbnails_medium/<?=$user['photo']?>">
            <h1><?=$user['firstName']?> <?=$user['lastName']?></h1>
            <ul id="info">
                <li>
                    <em>Username</em><p><?=$user['username']?>
                </li>
                <li>
                    <em>Email</em><p><?=$user['email']?>
                </li>
                <li>
                    <em>Password</em><p><?=$user['password']?>
                </li>
            </ul>

            <?php if ($userLoggedIn) { ?>
                    <a class="button" href="edit_profile.php">Edit profile</a>
            <?php } ?>

            <section id="about_me" />
                <h2>About me</h2>
                <p><?=$user['biography']?></p>
            </section>
        </section>
<?php
    }
?>

<?php
    function draw_edit_profile($user) {
?>
    <section class="card" id="search_box" >
        <h1>Update profile information</h1>

        <form action="../actions/action_update_profile_picture.php" method="post" enctype="multipart/form-data">
            <input id="username" type="hidden" name="username" value=<?= $user['username']?> />
            <label> Profile picture
                    <input type="file" name="image">
            </label>
            <input id="submit_button" type="submit" value="Upload">
        </form>
        <form action="../actions/action_remove_profile_picture.php" method="post">
            <input id="username" type="hidden" name="username" value=<?= $user['username']?> />
            <input id="submit_button" type="submit" value="Remove">
        </form>
        <form action="../actions/action_edit_profile.php" method="post">
            <input  type="hidden" name="username" value=<?= $user['username']?> />
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
<?php
    }
?>

