<?php

include_once('../includes/config.php');
include_once('../database/residence_queries.php');
include_once('../database/user_queries.php');

// Database fetching
$residence = getResidenceInfo($_GET['id']);
$owner = getUserInfoById($_GET['id']);
$commodities = getAllCommodities();
$residenceCommodities = getResidenceCommodities($_GET['id']);
$comments = getResidenceComments($_GET['id']);
$rating = getResidenceRating($_GET['id']);

// Logged in status
$loggedAccountStatus = [];
$loggedAccountStatus['status'] = isset($_SESSION['username']);

if (isset($_SESSION['username'])) {

    $loggedAccountStatus['username'] = $_SESSION['username'];
}

// Redirect the user if the residence does not exist
if ($residence == FALSE) {

    header('Location: not_found_page.php');
}

// Variable value assembly
$owner_name = $owner['firstName'] . ' ' . $owner['lastName'];
$rating = ($rating == null) ? '--' : $rating;

?>

<?php function drawCommodities($residenceCommodities, $commodities)
{

    foreach ($commodities as $commodity) {

        $hasCommodity = false;

        foreach ($residenceCommodities as $residenceCommodity) {

            if (strcmp($residenceCommodity['name'], $commodity['name']) == 0) {

                $hasCommodity = true;

                ?>
                <li class="presentCommodity"> <?= ucfirst($commodity['name']) ?> </li>
            <?php
                        }
                    }

                    if (!$hasCommodity) {

                        ?>
            <li class="missingCommodity"> <?= ucfirst($commodity['name']) ?> </li>
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

<?php function simplifyPrice($price)
{
    if (number_format($price / 1000000000, 2) >= 1)
        return number_format($price / 1000000000, 2) . 'B';
    else if (number_format($price / 1000000, 3) >= 1)
        return number_format($price / 1000000, 2) . 'M';
    else if (number_format($price / 1000, 3) >= 1)
        return number_format($price / 1000, 3) . 'K';
    else
        return $price;
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

        ?>
        <section class="main_review">

            <section class="comment_user_info">
                <img src="../resources/default-profile-pic.jpg">
                <a href="./user.php?id=<?= $review['username'] ?>">
                    <p class="reviewer_name"> <?= $review['firstName'] . ' ' . $review['lastName'] ?></p>
                    <p class="reviewer_username">(<?= $review['username'] ?>)</p>
                </a>
            </section>

            <h1 class="comment_title">"<?= $review['title'] ?>" </h1>

            <section class="comment_date_and_rating">
                <section class="rating_display">
                    <?php drawRatingElements($review['rating']); ?>
                </section>
                <h3>(<?= $review['datestamp'] ?>)</h3>
            </section>

            <p class="comment_content">- <?= $review['content'] ?></p>

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
    { ?>

        <section class="reply">
            <section class="comment_user_info">
                <img src="../resources/default-profile-pic.jpg">
                <a href="./user.php?id=<?= $reply['username'] ?>">
                    <p class="reviewer_name"> <?= $reply['firstName'] . ' ' . $reply['lastName'] ?></p>
                    <p class="reviewer_username">(<?= $reply['username'] ?>)</p>
                </a>
            </section>

            <h1 class="comment_title">"<?= $reply['title'] ?>" </h1>

            <section class="reply_date">
                <h3> Replying to <?= $review['username'] ?> </h3>
                <h3>(<?= $reply['datestamp'] ?>)</h3>
            </section>

            <p class="comment_content">- <?= $reply['content'] ?></p>
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
            <p class="ri_capacity"><?= $residence['capacity'] . " People" ?></p>
            <p class="ri_nBeds"><?= $residence['nBeds'] . " Beds" ?></p>
            <p class="ri_nBedrooms"><?= $residence['nBedrooms'] . " Bedrooms"  ?></p>
            <p class="ri_nBathrooms"><?= $residence['nBathrooms'] . " Bathrooms" ?></p>
            <p class="ri_pricePerDay"><?= simplifyPrice($residence['pricePerDay']) . " € per day" ?></p>
        </section>

    <?php } ?>

    <?php function drawResidenceOwnerInfo()
    {

        global $owner, $owner_name;
        ?>

        <section id="ri_owner">

            <h3> Owner: </h3>
            <section id="owner_avatar">
                <a href="./user.php?id=<?= $owner['username'] ?>">
                    <img src="../resources/default-profile-pic.jpg">
                    <p> <?= $owner_name ?></p>
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
                <button id="rent_button_inactive">RENT NOW</button>
            <?php
                    } else {
                        ?>
                <button id="rent_button_active">RENT NOW</button>
            <?php
                    }
                } else {

                    ?>
            <button id="rent_button_active">RENT NOW</button>
        <?php
            }

            ?>

    <?php } ?>

    <?php function drawResidenceInfo()
    {

        global $residence, $rating;

        ?>
        <section id="residence_info">

            <h1 class="ri_title"><?= $residence['title'] ?></h1>
            <h2 class="ri_type_and_location"> <?= ucfirst($residence['type']) . ' &#8226 ' . $residence['address'] . ', ' . $residence['city'] . ', ' . $residence['country'] ?> </h2>
            <div class="ri_rating">
                <h4><?= $rating ?> &#9733 </h4>
            </div>
            <p class="ri_description"><?= "Existem muitas variações das passagens do Lorem Ipsum disponíveis, mas a maior parte sofreu alterações de alguma forma, pela injecção de humor, ou de palavras aleatórias que nem sequer parecem suficientemente credíveis. Se vai usar uma passagem do Lorem Ipsum, deve ter a certeza que não contém nada de embaraçoso escondido no meio do texto. Todos os geradores de Lorem Ipsum na Internet acabam por repetir porções de texto pré-definido, como necessário, fazendo com que este seja o primeiro verdadeiro gerador na Internet. Usa um dicionário de 200 palavras em Latim, combinado com uma dúzia de modelos de frases, para gerar Lorem Ipsum que pareçam razoáveis. Desta forma, o Lorem Ipsum gerado é sempre livre de repetição, ou de injecção humorística, etc." ?></p>

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

        global $loggedAccountStatus, $actual_link;


        if ($loggedAccountStatus['status']) {
            $loggedUserInfo = getUserInfo($loggedAccountStatus['username']);
            $loggedUserFullName = $loggedUserInfo['firstName'] . ' ' . $loggedUserInfo['lastName'];
            $loggedUserUserName = $loggedAccountStatus['username'];
        } else {
            $loggedUserFullName = 'Anonymous';
            $loggedUserUserName = 'anonymous_user';
        }

        ?>

        <section class="reply">

            <section class="comment_user_info">
                <img src="../resources/default-profile-pic.jpg">

                <?php
                    if ($loggedAccountStatus['status']) {
                        ?>

                    <a href="./user.php?id=<?= $loggedUserUserName ?>">
                    <?php
                        } else { ?>
                        <a>
                        <?php } ?>

                        <p class="reviewer_name"> <?= $loggedUserFullName ?></p>
                        <p class="reviewer_username">(<?= $loggedUserUserName ?>)</p>
                        </a>
            </section>

            <section class="reply_date">
                <h3> Replying to <?= $review['username'] ?> </h3>
            </section>

            <?php if ($loggedAccountStatus['status']) { ?>

                <form class="comment_content">
                    <input type="hidden" name="reviewID" value="<?= $review['commentID'] ?>">
                    <input type="hidden" name="residence" value="<?= $_GET['id'] ?>">
                    <input class="comment_title" placeholder="Title of your reply" required="required" type="text" name="title">
                    <textarea class="comment_content" placeholder="What's on you mind?" required="required" name="content" rows="4" cols="50"></textarea>
                    <input class="submit_reply" formaction="../actions/action_add_reply.php" type="submit" value="Reply">
                </form>

        </section>

    <?php
        } else {
            ?>
        <section class="reply_date">
            <h3> Replying to <?= $review['username'] ?> </h3>
        </section>

        <p class="comment_content">You need to be logged in to reply to a review...</p>
    </section>
<?php
    }
    ?>

<?php } ?>


<?php function draw_main()
{ ?>
    <section id="main">

        <?php draw_left_side() ?>

        <section id="right_side">

            <section id="residence-images" class="slideshow-container">

                <!-- Full-width images with number and caption text -->
                <section class="mySlides fade">
                    <section class="numbertext">1 / 3</section>
                    <img class="slide_show_img" src="../resources/santorini.jpg" >
                </section>

                <section class="mySlides fade">
                    <section class="numbertext">2 / 3</section>
                    <img class="slide_show_img" src="../resources/oporto.jpg" >
                </section>

                <section class="mySlides fade">
                    <section class="numbertext">3 / 3</section>
                    <img class="slide_show_img" src="../resources/bridge_sunset.jpg">
                </section>

                <!-- Next and previous buttons -->
                <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                <a class="next" onclick="plusSlides(1)">&#10095;</a>
            </section>

        </section>


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