<?php
    include_once('database/connection.php');


    function getAllResidencesMapInfo() {
        global $dbh;

        $stmt = $dbh->prepare(
            'SELECT address, city, country, latitude, longitude, residencetype.name as type
            FROM residence JOIN residencetype
            ON residence.type = residenceTypeID'
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }


?>