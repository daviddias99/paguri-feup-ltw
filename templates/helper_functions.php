<?php function simplifyPrice($price)
    {
        if (number_format($price / 1000000000, 2) >= 1)
            return number_format($price / 1000000000, 2) . 'B';
        else if (number_format($price / 1000000, 3) >= 1)
            return number_format($price / 1000000, 2) . 'M';
        else if (number_format($price / 1000, 3) >= 1)
            return number_format($price / 1000, 1) . 'K';
        else
            return $price;
    }
    ?>
