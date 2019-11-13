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

    function getResidenceMapInfo($residenceID) {
        global $dbh;

        $stmt = $dbh->prepare(
            'SELECT address, city, country, latitude, longitude, residencetype.name as type
            FROM residence JOIN residencetype
            ON residence.type = residenceTypeID
            WHERE residenceID = ?'
        );
        $stmt->execute(array($residenceID));
        return $stmt->fetch();
    }


?>