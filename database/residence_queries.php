<?php
    include_once('database/connection.php');

    function getAllResidences() {
        global $dbh;

        $stmt = $dbh->prepare(
            'SELECT residence.*, residencetype.name as type 
            FROM residence JOIN residencetype
            ON residence.type = residenceTypeID'
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function getResidenceInfo($residenceID) {
        global $dbh;

        $stmt = $dbh->prepare(
            'SELECT residence.*, residencetype.name as type 
            FROM residence JOIN residencetype
            ON residence.type = residenceTypeID
            WHERE residenceID = ?'
        );

        $stmt->execute(array($residenceID));
        return $stmt->fetch();        
    }

    function getResidencePhotos($residenceID) {
        global $dbh;

        $stmt = $dbh->prepare('SELECT filepath, priority FROM residencePhoto WHERE lodge = ?');
        $stmt->execute(array($residenceID));
        return $stmt->fetchAll();        
    }

    function getResidenceComments($residenceID) {
        global $dbh;

        $stmt = $dbh->prepare('SELECT * FROM comment WHERE lodge = ?');
        $stmt->execute(array($residenceID));
        return $stmt->fetchAll();        
    }

    function getCommentReplies($commentID) {
        global $dbh;

        $stmt = $dbh->prepare('SELECT * FROM reply WHERE parent = ?');
        $stmt->execute(array($commentID));
        return $stmt->fetchAll();        
    }

    function getResidenceCommodities($residenceID) {
        global $dbh;

        $stmt = $dbh->prepare(
            'SELECT name 
            FROM residenceHasCommodity JOIN commodity 
            ON item = commodityID 
            WHERE lodge = ?');

        $stmt->execute(array($residenceID));
        return $stmt->fetchAll();        
    }


?>