<?php

include_once('../includes/config.php');
include_once('../templates/common/header.php');
include_once('../templates/common/footer.php');
include_once('../templates/filters/includes.php');
include_once('../templates/house_page_elements.php');
include_once('../templates/slideshow/includes.php');

// Database fetching
$residence = getResidenceInfo($_GET['id']);

// Redirect the user if the residence does not exist
if ($residence == FALSE) {

    header('Location: not_found_page.php');
}

// Draw the page
draw_header('residence_page', NULL);
add_slideshow_includes();
draw();
draw_footer();

?>



