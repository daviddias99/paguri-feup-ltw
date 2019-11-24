<!DOCTYPE html>
<html lang="en-US">
    <head>
        <title>Free houses</title>    
        <meta charset="UTF-8">
        <link href="map.css" rel="stylesheet">
        <script src="geocoding_ajax.js" defer></script>
        <script src="google_map.js" defer></script>
        <script src="./map_clusters/markerclusterer.js" defer></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD0_ruPYuzA1ntPaautYqVqtjOT96oNLSE&callback=initMap" async defer></script>
    </head>
    <body>
        <header>
            <h1>Paguri</h1>
        </header>

        <form>
            <input id="address_bar" type="text" placeholder="address">
            <input id="latitude_bar" type="text" placeholder="latitude" disabled>
            <input id="longitude_bar" type="text" placeholder="longitude" disabled>
        </form>

        <div id="map"></div>
    </body>
</html>