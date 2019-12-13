<?php

include_once('../includes/config.php');
include_once('../templates/common/header.php');
include_once('../templates/common/footer.php');
include_once('../templates/filters/includes.php');
include_once('../templates/slideshow/includes.php');
include_once('../templates/search_results_page_elements.php');
include_once('../database/residence_queries.php');
include_once('../database/user_queries.php');

function simplifyPrice($price)	
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

function drawCommodities($residenceCommodities,$commodities){

    foreach ($commodities as $commodity) { 

        $hasCommodity = false;

        foreach($residenceCommodities as $residenceCommodity){ 

            if(strcmp($residenceCommodity['name'], $commodity['name']) == 0 ){
                $hasCommodity = true;
            ?>
                <li class="presentCommodity"> <?= ucfirst($commodity['name']) ?> </li>
            <?php }
        }

    if(!$hasCommodity){ ?>
        
        <li class="missingCommodity"> <?= ucfirst($commodity['name']) ?> </li>
        
 <?php }

    }
    
}


// Database fetching
$residence = getResidenceInfo($_GET['id']);
$owner = getUserInfoById($_GET['id']);
$commodities = getAllCommodities();
$residenceCommodities = getResidenceCommodities($_GET['id']);
$comments = getResidenceComments($_GET['id']);
$rating = getResidenceRating($_GET['id']);

$replies = getCommentReplies($comments[0]['commentID']);
print_r($replies);

// Redirect the user if the residence does not exist
if ($residence == FALSE) {

    header('Location: not_found_page.php');
}

// Variable value assembly
$owner_name = $owner['firstName'] . ' ' . $owner['lastName'];
$rating = ($rating == null) ? '--' : $rating;


// Draw the page
draw_header('residence_page');
add_slideshow_includes();
draw();
draw_footer();

?>


<?php function draw()
{

    global $residence,$residenceCommodities, $owner_name, $rating,$owner_name,$comments,$replies;    ?>

    <section id="main">
        <section id="left_side">

            <section id="residence_info">

                <h1 class="ri_title"><?= $residence['title'] ?></h1>
                <h2 class="ri_type_and_location"> <?= ucfirst($residence['type']) . ' &#8226 ' . $residence['address'] . ', ' . $residence['city'] . ', ' . $residence['country'] ?> </h2>
                <div class="ri_rating"> <h4 ><?=$rating?> &#9733 </h4> </div>
                <p class="ri_description"><?="Existem muitas variações das passagens do Lorem Ipsum disponíveis, mas a maior parte sofreu alterações de alguma forma, pela injecção de humor, ou de palavras aleatórias que nem sequer parecem suficientemente credíveis. Se vai usar uma passagem do Lorem Ipsum, deve ter a certeza que não contém nada de embaraçoso escondido no meio do texto. Todos os geradores de Lorem Ipsum na Internet acabam por repetir porções de texto pré-definido, como necessário, fazendo com que este seja o primeiro verdadeiro gerador na Internet. Usa um dicionário de 200 palavras em Latim, combinado com uma dúzia de modelos de frases, para gerar Lorem Ipsum que pareçam razoáveis. Desta forma, o Lorem Ipsum gerado é sempre livre de repetição, ou de injecção humorística, etc."?></p>
                   
                <section id="ri_residence_properties">
                    <p class="ri_capacity"><?=$residence['capacity'] . " People" ?></p>
                    <p class="ri_nBeds"><?=$residence['nBeds'] . " Beds" ?></p>
                    <p class="ri_nBedrooms"><?=$residence['nBedrooms'] . " Bedrooms"  ?></p>
                    <p class="ri_nBathrooms"><?=$residence['nBathrooms'] . " Bathrooms"?></p>
                    <p class="ri_pricePerDay"><?=simplifyPrice($residence['pricePerDay']) . " € per day"?></p>
                </section>

                <section id="residence_commodities" class="ri_commodities">
                    <h3> Commodities: </h3>
                    <ul>
                        <?php
                            global $commodities,$residenceCommodities;
                            drawCommodities($residenceCommodities,$commodities);
                        ?>
                    </ul>
                </section>       
                
                <section id="ri_owner" >

                    <h3> Owner: </h3>
                    <section id="owner_avatar">
                        <img src="../resources/default-profile-pic.jpg">
                        <p> <?= $owner_name ?></p>
                    </section>
                </section>
            </section>
            
        </section>

        <section id="right_side">

            <section id="residence_images"class="slideshow-container" >
                <img src="../resources/house_image_test.jpeg">
            </section>

        </section>


    </section>


    <section id="review_section" >
        <h1> Reviews </h1>
        <section id="residence_reviews">

            <section class="review">

                <section class="main_review">

                    <section class="comment_header">
                    <h1>"<?=$comments[0]['title']?>" - <?=$comments[0]['rating']?>/10</h1>
                    <section class="reviewer_avatar">
                        <img src="../resources/default-profile-pic.jpg">
                        <a href="./user.php?id=<?=$comments[0]['username']?>"> <p class="reviewer_name"> <?=$comments[0]['firstName'] . ' ' . $comments[0]['lastName']?></p> </a>
                        <a href="./user.php?id=<?=$comments[0]['username']?>"> <p class="reviewer_username">(<?=$comments[0]['username']?>)</p> </a>
                    </section>
                    <h3 class="review_date"><?=$comments[0]['datestamp']?></h3>
                        
                    </section>
                    
                    <p class="review_content"><?=$comments[0]['content']?></p>
                   
                    
                </section>
                
                <section class="replies">
                    <section class="comment_header">
                        <h1>"<?=$replies[0]['title']?>"</h1>
                        <section class="reviewer_avatar">
                            <img src="../resources/default-profile-pic.jpg">
                            <a href="./user.php?id=<?=$replies[0]['username']?>"> <p class="reviewer_name"> <?=$comments[0]['firstName'] . ' ' . $comments[0]['lastName']?></p> </a>
                            <a href="./user.php?id=<?=$comments[0]['username']?>"> <p class="reviewer_username">(<?=$comments[0]['username']?>)</p> </a>
                        </section>
                        <h3 class="review_date"><?=$comments[0]['datestamp']?></h3>

                    </section>
                
                </section>
            
            </section>
        
        </section>
    
    </section>

    

    

<?php } ?>