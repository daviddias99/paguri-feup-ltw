<?php

    include_once('../includes/config.php');
    include_once('../templates/helper_functions.php');
    include_once('../database/residence_queries.php');
    include_once('../database/user_queries.php');

    if (!isset($_GET['id']))
        header('Location: front_page.php');

    // Database fetching
    $residence = getResidenceInfo($_GET['id']);
    $owner = getUserInfoById($residence['owner']);
    $commodities = getAllCommodities();
    $residenceCommodities = getResidenceCommodities($_GET['id']);
    $residencePhotos= getResidencePhotos($_GET['id']);
    $comments = getResidenceComments($_GET['id']);
    $rating = getResidenceRating($_GET['id']);

    // Logged in status
    $loggedAccountStatus = [];
    $loggedAccountStatus['status'] = isset($_SESSION['username']);

    if (isset($_SESSION['username'])) {

        $loggedAccountStatus['username'] = $_SESSION['username'];
    }

    // Owner photo
    $ownerphoto = getUserPhotoID($owner['username']);
    $ownerphotopath = "../images/users/thumbnails_medium/$ownerphoto";

    // Variable value assembly
    $owner_name = htmlentities($owner['firstName'] . ' ' . $owner['lastName']);
    $rating = htmlentities(($rating == null) ? '--' : $rating/2);

    ?>

    <?php function drawCommodities($residenceCommodities, $commodities)
    {

        foreach ($commodities as $commodity) {

            $hasCommodity = false;

            foreach ($residenceCommodities as $residenceCommodity) {

                if (strcmp($residenceCommodity['name'], $commodity['name']) == 0) {

                    $hasCommodity = true;

                    ?>
                    <li class="presentCommodity"> <?= htmlentities(ucfirst($commodity['name'])) ?> </li>
                <?php
                            }
                        }

                        if (!$hasCommodity) {

                            ?>
                <li class="missingCommodity"> <?= htmlentities(ucfirst($commodity['name'])) ?> </li>
    <?php
            }
        }
    } ?>

    <?php function drawRatingElements($rating)
    {


        for ($i = 0; $i < floor($rating / 2); $i++) {
            ?>
            <span class="fa fa-star checked"></span>
            <?php
                }

                if (floor($rating / 2) != $rating / 2) {

                    if ($rating / 2 - floor($rating / 2) >= 0.5) {
                        ?>
                <span class="fa fa-star-half-o"></span>
            <?php
                    } else {
                        ?>
                <span class="fa fa-star-o"></span>
            <?php
                    }
                }

                for ($i = ceil($rating / 2); $i < 5; $i++) {
                    ?>
            <span class="fa fa-star-o"></span>
    <?php
        }
    }
    ?>


    <?php function drawReviews($comments)
    { ?>

        <section id="residence_reviews">

            <?php

                foreach ($comments as $review) {

                    drawReview($review);
                }
                ?>

        <?php } ?>


        <?php function drawReview($review)
        {

            $replies = getCommentReplies($review['commentID']);

            ?>
            <section class="review">

                <?php
                    drawMainReview($review);
                    drawReplies($review, $replies);
                    ?>

            </section>
        <?php } ?>

        <?php function drawMainReview($review)
        {
            // Owner photo
            $userphoto = getUserPhotoID($review['username']);
            $userphotopath = "../images/users/thumbnails_medium/$userphoto";

            ?>
            <section class="main_review">

                <section class="comment_user_info">
                    <img src="../resources/<?= $userphotopath ?>">
                    <a href="./user.php?id=<?= htmlentities($review['username']) ?>">
                        <p class="reviewer_name"> <?= htmlentities($review['firstName']) . ' ' . htmlentities($review['lastName']) ?></p>
                        <p class="reviewer_username">(<?= htmlentities($review['username']) ?>)</p>
                    </a>
                </section>

                <h1 class="comment_title">"<?= htmlentities($review['title']) ?>" </h1>

                <section class="comment_date_and_rating">
                    <section class="rating_display">
                        <?php drawRatingElements($review['rating']); ?>
                    </section>
                    <h3>(<?= htmlentities($review['datestamp']) ?>)</h3>
                </section>

                <p class="comment_content">- <?= htmlentities($review['content']) ?></p>

            </section>


        <?php } ?>

        <?php function drawReplies($review, $replies)
        {

            ?>
            <section class="replies">

                <?php
                    foreach ($replies as $reply) {

                        drawReply($reply, $review);
                    }

                    drawNewReplyForm($review);
                    ?>

            </section>

        <?php } ?>

        <?php function drawReply($reply, $review)
        {

            // Owner photo
            $userphoto = getUserPhotoID($reply['username']);
            $userphotopath = "../images/users/thumbnails_medium/$userphoto";
            ?>

            <section class="reply">
                <section class="comment_user_info">
                    <img src="../resources/<?= $userphotopath ?>">
                    <a href="./user.php?id=<?= htmlentities($reply['username']) ?>">
                        <p class="reviewer_name"> <?= htmlentities($reply['firstName']) . ' ' . htmlentities($reply['lastName']) ?></p>
                        <p class="reviewer_username">(<?= htmlentities($reply['username']) ?>)</p>
                    </a>
                </section>

                <h1 class="comment_title">"<?= htmlentities($reply['title']) ?>" </h1>

                <section class="reply_date">
                    <h3> Replying to <?= htmlentities($review['username']) ?> </h3>
                    <h3>(<?= htmlentities($reply['datestamp']) ?>)</h3>
                </section>

                <p class="comment_content">- <?= htmlentities($reply['content']) ?></p>
            </section>

        <?php } ?>

        <?php function draw_left_side()
        {


            ?>
            <section id="left_side">

                <?php drawResidenceInfo(); ?>

            </section>

        <?php } ?>

        <?php function drawResidenceProperties($residence)
        { ?>

            <section id="ri_residence_properties">
                <p class="ri_capacity"><?= htmlentities($residence['capacity']) . " People" ?></p>
                <p class="ri_nBeds"><?= htmlentities($residence['nBeds']) . " Beds" ?></p>
                <p class="ri_nBedrooms"><?= htmlentities($residence['nBedrooms']) . " Bedrooms"  ?></p>
                <p class="ri_nBathrooms"><?= htmlentities($residence['nBathrooms']) . " Bathrooms" ?></p>
                <p class="ri_pricePerDay"><?= htmlentities(simplifyPrice($residence['pricePerDay'])) . " € per day" ?></p>
            </section>

        <?php } ?>

        <?php function drawResidenceOwnerInfo()
        {

            global $owner, $owner_name, $ownerphotopath;
            ?>

            <section id="ri_owner">

                <h3> Owner: </h3>
                <section id="owner_avatar">
                    <a href="./user.php?id=<?= htmlentities($owner['username']) ?>">
                        <img src="<?= $ownerphotopath ?>">
                        <p> <?= htmlentities($owner_name) ?></p>
                    </a>
                </section>
            </section>

        <?php } ?>

        <?php function drawRentButton()
        {

            global $owner, $loggedAccountStatus;
            ?>

            <?php

                if ($loggedAccountStatus['status']) {

                    if (strcmp($loggedAccountStatus['username'], $owner['username']) == 0) {
                        ?>

                    <form id="rent_action_form">
                        <input id="rent_button_inactive" disabled="disabled" type="submit" value="RENT NOW" />
                    </form>
                <?php
                        } else {
                            ?>
                    <form id="rent_action_form"  action="rent_house.php">
                        <input type="hidden" name="residenceID" value="<?= htmlentities($_GET['id']) ?>">
                        <input id="rent_button_active" type="submit" value="RENT NOW" />
                    </form>
                <?php
                        }
                    } else { ?>


                <form id="rent_action_form">
                    <input id="rent_button_inactive" disabled="disabled" type="submit" value="RENT NOW" />
                </form>
            <?php
                }

                ?>

        <?php } ?>

        <?php function drawResidenceInfo()
        {

            global $residence, $rating;

            ?>
            <section id="residence_info">

                <h1 class="ri_title"><?= htmlentities($residence['title']) ?></h1>
                <h2 class="ri_type_and_location"> <?= htmlentities(ucfirst($residence['type'])) . ' &#8226 ' . $residence['address'] . ', ' . $residence['city'] . ', ' . $residence['country'] ?> </h2>
                <div class="ri_rating">
                    <h4><?= htmlentities($rating) ?> &#9733 </h4>
                </div>
                <p class="ri_description"><?= htmlentities(ucfirst($residence['description'])) ?></p>

                <?php drawResidenceProperties(($residence)) ?>

                <section id="residence_commodities" class="ri_commodities">
                    <h3> Commodities: </h3>
                    <ul>
                        <?php
                            global $commodities, $residenceCommodities;
                            drawCommodities($residenceCommodities, $commodities);
                            ?>
                    </ul>
                </section>

                <?php drawResidenceOwnerInfo() ?>
                <?php drawRentButton() ?>

            </section>

        <?php } ?>

        <?php function drawNewReplyForm($review)
        {

            global $loggedAccountStatus;



            if ($loggedAccountStatus['status']) {
                $loggedUserInfo = getUserInfo($loggedAccountStatus['username']);
                $loggedUserFullName = $loggedUserInfo['firstName'] . ' ' . $loggedUserInfo['lastName'];
                $loggedUserUserName = $loggedAccountStatus['username'];

                // Owner photo
                $userphoto = getUserPhotoID($loggedAccountStatus['username']);
                $userphotopath = "../images/users/thumbnails_medium/$userphoto";
            } else {
                $loggedUserFullName = 'Anonymous';
                $loggedUserUserName = 'anonymous_user';

                $userphotopath = "../resources/default-profile-pic.jpg";
            }

            ?>

            <section class="reply reply_form">

                <section class="comment_user_info">
                    <img src="<?= $userphotopath ?>">

                    <?php
                        if ($loggedAccountStatus['status']) { ?>

                        <a href="./user.php?id=<?= htmlentities($loggedUserUserName) ?>">
                        <?php
                            } else { ?>
                            <a>
                            <?php } ?>
                            <p class="reviewer_name"> <?= htmlentities($loggedUserFullName) ?></p>
                            <p class="reviewer_username">(<?= htmlentities($loggedUserUserName) ?>)</p>
                            </a>
                </section>

                <section class="reply_date">
                    <h3> Replying to <?= htmlentities($review['username']) ?> </h3>
                </section>

                <?php if ($loggedAccountStatus['status']) { ?>

                    <form class="comment_content">
                        <input type="hidden" name="reviewID" value="<?= htmlentities($review['commentID']) ?>">
                        <input type="hidden" name="residence" value="<?= htmlentities($_GET['id']) ?>">
                        <input class="comment_title" placeholder="Title of your reply" required="required" type="text" name="title">
                        <textarea class="comment_content" placeholder="What's on you mind?" required="required" name="content" rows="4" cols="50"></textarea>
                        <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
                        <input class="submit_reply" formaction="../actions/action_add_reply.php" type="submit" value="Reply">
                    </form>

            </section>

        <?php
            } else {
                ?>
            <section class="reply_date">
                <h3> Replying to <?= htmlentities($review['username']) ?> </h3>
            </section>

            <p class="comment_content">You need to be logged in to reply to a review...</p>
        </section>
        <?php
            }
        }
        ?>


<?php function draw_right_side() { ?>

    <section id="right_side">

        <?php draw_residence_images(); ?>
    </section>

<?php } ?>

<?php function draw_residence_images() { 
    
    global $residencePhotos;
    ?>

    <section id="residence-images" class="slideshow-container">

        <?php for ($i = 0; $i < count($residencePhotos); $i++)  { ?>

            <section class="mySlides fade">
                <section class="numbertext"><?=$i+1?> / <?=count($residencePhotos)?></section>
                <img class="slide_show_img" src="../images/properties/big/<?=$residencePhotos[$i]['filepath']?>">
            </section>


        <?php } ?>


        <!-- Next and previous buttons -->
        <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
        <a class="next" onclick="plusSlides(1)">&#10095;</a>
    </section>

<?php } ?>

<?php function draw_main()
{ ?>
    <section id="main">

        <?php 
        
            draw_left_side();
            draw_right_side();
        ?>


        </section>
    <?php } ?>

    <?php function draw_review_section()
    { ?>
        <section id="review_section">
            <h1> Reviews </h1>
            <?php

                global $comments;
                drawReviews($comments);
                ?>
        </section>
    <?php } ?>

    <?php function draw()
    {

        draw_main();
        draw_review_section();
    } ?>