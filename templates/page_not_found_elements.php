<?php function draw_not_found($message) { ?>

<section id="not_found_card" class="card">
    <h1> Something went wrong :( </h1>
    <?php
        if($message != null){ ?>
        <h2><?=ucfirst($message)?></h2>
        <?php }
            
    ?>
     
    
</section>

<?php } ?>