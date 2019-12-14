<?php

    $api_key = 'AIzaSyD0_ruPYuzA1ntPaautYqVqtjOT96oNLSE';

    function getAddressInfo($address) {
        global $api_key;
        $encoded_addr = urlencode($address);
        return file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=$encoded_addr&key=$api_key");
    }

?>