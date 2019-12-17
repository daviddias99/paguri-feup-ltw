<?php function draw_review_form($residence,$reservation) 
{ ?>

    <section id="review_form">

        <h1>Writing a review for the residence '<?=$residence['title']?>'</h1>

        <form method="get" action="../actions/action_add_review.php">
        
            <input type="hidden" name="reservationID" value="<?=$reservation['reservationID']?>">
            <input type="text" placeholder="Title" name="review_title" required="required"> 
            <textarea class="comment_content" placeholder="What's on you mind?" required="required" name="content" rows="4" cols="50"></textarea>
            <input class="comment_rating"  type="number" value="" min="0" max="10" step="1" name="rating" required="required" placeholder="Rating">
            <input class="submit_reply" type="submit" value="Review">
        </form>

    </section>

<?php } ?>