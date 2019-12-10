<?php
    include_once('../database/connection.php');

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

    function getResidenceTypes() {
        global $dbh;

        $stmt = $dbh->prepare('SELECT residenceTypeID, name FROM residenceType');
        $stmt->execute();

        return $stmt->fetchAll();
    }

    function getAllCommodities() {
        global $dbh;

        $stmt = $dbh->prepare('SELECT name FROM commodity');
        $stmt->execute();

        return $stmt->fetchAll();
    }

    function getResidenceTypeWithID($typeID) {
        global $dbh;

        $stmt = $dbh->prepare('SELECT name FROM residenceType WHERE residenceTypeID = ?');
        $stmt->execute(array($typeID));

        $res = $stmt->fetch();
        return $res === FALSE ? FALSE : $res['name'];
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

    function createResidence($residenceObj) {
        global $dbh;

        $stmt = $dbh->prepare(
            'INSERT INTO 
            residence(owner, title, description, pricePerDay, capacity, nBedrooms, 
            nBathrooms, nBeds, type, address, city, country, latitude, longitude)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
        
        try{
            $stmt->execute(array(
                    $residenceObj['owner'],
                    $residenceObj['title'],
                    $residenceObj['description'],
                    $residenceObj['pricePerDay'],
                    $residenceObj['capacity'],
                    $residenceObj['nBedrooms'],
                    $residenceObj['nBathrooms'],
                    $residenceObj['nBeds'],
                    $residenceObj['type'],
                    $residenceObj['address'],
                    $residenceObj['city'],
                    $residenceObj['country'],
                    $residenceObj['latitude'],
                    $residenceObj['longitude']
                )
            );
        }
        catch(PDOException $Exception) {
            return FALSE;
        }

        if ($stmt->rowCount() <= 0) return FALSE;

        return $dbh->lastInsertId();
    }

    function deleteResidence($id) {
        global $dbh;

        $residence = getResidenceInfo($id);

        if ($residence == FALSE) return FALSE;

        $stmt = $dbh->prepare('DELETE FROM residence WHERE residenceID = ?');
        $stmt->execute(array($id));

        return $residence;
    }


?>