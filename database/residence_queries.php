<?php
    include_once('../database/connection.php');
    include_once('../database/comment_queries.php');
    include_once('../database/reservation_queries.php');

    function getAllResidences()
    {
        global $dbh;

        $stmt = $dbh->prepare(
            'SELECT residence.*, residencetype.name as typeStr , rating
                FROM residence JOIN residencetype ON residence.type = residenceTypeID
                                LEFT OUTER JOIN (SELECT lodge, avg(rating) as rating
                                    FROM comment JOIN reservation ON (comment.booking = reservation.reservationID)
                                    GROUP BY lodge) as avgRatingPerResidence
                                ON residence.residenceID = avgRatingPerResidence.lodge'
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function getResidenceTypes()
    {
        global $dbh;

        $stmt = $dbh->prepare('SELECT residenceTypeID, name FROM residenceType');
        $stmt->execute();

        return $stmt->fetchAll();
    }

    function getAllCommodities()
    {
        global $dbh;

        $stmt = $dbh->prepare('SELECT * FROM commodity');
        $stmt->execute();

        return $stmt->fetchAll();
    }

    function getResidenceTypeWithID($typeID)
    {
        global $dbh;

        $stmt = $dbh->prepare('SELECT name FROM residenceType WHERE residenceTypeID = ?');
        $stmt->execute(array($typeID));

        $res = $stmt->fetch();
        return $res === FALSE ? FALSE : $res['name'];
    }


function getResidenceInfo($residenceID)
{
    global $dbh;

    $stmt = $dbh->prepare(
        'SELECT residence.*, residenceTypeID, residencetype.name as type , rating
        FROM residence JOIN residencetype
                        ON residence.type = residenceTypeID
                       LEFT JOIN (SELECT lodge, avg(rating) as rating
                             FROM comment JOIN reservation ON (comment.booking = reservation.reservationID)
                             GROUP BY lodge
                            ) as avgRatingPerResidence
                        ON residence.residenceID = avgRatingPerResidence.lodge
            WHERE residenceID = ?'
    );

    try{

        $stmt->execute(array($residenceID));
    }
    catch(PDOException $Exception) {
        return FALSE;
    }


    return $stmt->fetch();
}

    function getResidenceAvailabilities($residenceID) {
        global $dbh;

        $stmt = $dbh->prepare('SELECT strftime("%Y-%m-%d",startDate) as startDate, strftime("%Y-%m-%d",endDate) as endDate FROM availability WHERE lodge = ?');
        $stmt->execute(array($residenceID));

        return $stmt->fetchAll();
    }


    function getResidencePhotos($residenceID)
    {
        global $dbh;

        $stmt = $dbh->prepare('SELECT * FROM residencePhoto WHERE lodge = ?');
        $stmt->execute(array($residenceID));

        return $stmt->fetchAll();
    }


    function getResidenceCommodities($residenceID)
    {
        global $dbh;

        $stmt = $dbh->prepare(
            'SELECT *
                FROM residenceHasCommodity JOIN commodity
                ON item = commodityID
                WHERE lodge = ?'
        );

        $stmt->execute(array($residenceID));
        return $stmt->fetchAll();
    }

    function getResidencesWith($capacity, $nBeds, $type, $minPrice, $maxPrice, $minRating, $maxRating)
    {
        global $dbh;

        $query_vals = array();
        $query_base =
            'SELECT residence.*, residencetype.name as typeStr, rating
            FROM residence JOIN residencetype ON residence.type = residenceTypeID
                        LEFT JOIN (SELECT lodge, avg(rating) as rating
                                        FROM comment JOIN reservation ON (comment.booking = reservation.reservationID)
                                        GROUP BY lodge
                                        ) as avgRatingPerResidence
                            ON residence.residenceID = avgRatingPerResidence.lodge
            WHERE 1=1';

        if (is_numeric($capacity)) {
            $query_base .= ' AND capacity >= ?';
            array_push($query_vals, intval($capacity));
        }
        if (is_numeric($nBeds)) {
            $query_base .= ' AND nBeds >= ?';
            array_push($query_vals, intval($nBeds));
        }
        if (is_numeric($minPrice)) {
            $query_base .= ' AND pricePerDay >= ?';
            array_push($query_vals, floatval($minPrice));
        }
        if (is_numeric($maxPrice)) {
            $query_base .= ' AND pricePerDay <= ?';
            array_push($query_vals, floatval($maxPrice));
        }
        if (is_numeric($minRating)) {
            $query_base .= ' AND rating >= ' . floatval($minRating);
        }
        if (is_numeric($maxRating)) {
            $query_base .= ' AND rating <= ' . floatval($maxRating);
        }
        if ($type !== "") {
            $query_base .= ' AND typeStr = ?';
            array_push($query_vals, $type);
        }


        $stmt = $dbh->prepare($query_base);
        $stmt->execute($query_vals);

        return $stmt->fetchAll();
    }



    function getResidencesRatings() {
        global $dbh;

        $stmt = $dbh->prepare(
            'SELECT lodge, avg(rating) as rating
            FROM comment JOIN reservation ON (comment.booking = reservation.reservationID)
            GROUP BY lodge'

        );

        $stmt->execute();

        return $stmt->fetchAll();
    }

    function getResidenceRating($residenceID) {
        global $dbh;

        $stmt = $dbh->prepare(
            'SELECT rating
            FROM residence LEFT JOIN (SELECT lodge, avg(rating) as rating
                            FROM comment JOIN reservation ON (comment.booking = reservation.reservationID)
                            GROUP BY lodge
                            ) as avgRatingPerResidence
                        ON residence.residenceID = avgRatingPerResidence.lodge
            WHERE residence.residenceID = ?
            '
        );

        $stmt->execute(array($residenceID));

        if ($stmt->rowCount() <= 0) return null;

        return $stmt->fetchAll()[0]['rating'];
    }

    function createResidence($residenceObj)
    {
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

            $residenceID = $dbh->lastInsertId();

            $commodities = json_decode($residenceObj['commodities']);

            foreach ($commodities as $commodity) {
                $commodityStmt = $dbh->prepare('INSERT INTO residencehascommodity VALUES (?, ?)');
                $commodityStmt->execute(array($residenceID, $commodity));
            }
        }
        catch(PDOException $Exception) {
            return FALSE;
        }

        if ($stmt->rowCount() <= 0) return FALSE;

        return $residenceID;
    }


    function deleteResidence($residenceID) {
        global $dbh;

        $residence = getResidenceInfo($residenceID);
        if ($residence == FALSE) return FALSE;

        deleteResidenceReservations($residenceID);

        $stmt = $dbh->prepare('DELETE FROM residence WHERE residenceID = ?');
        try {
            $stmt->execute(array($residenceID));
        }
        catch(PDOException $Exception) {
            return FALSE;
        }

        return $residence;
    }

    function deleteResidencePhotos($residenceID) {
        global $dbh;

        $stmt = $dbh->prepare('DELETE FROM residencePhoto WHERE lodge = ?');
        $stmt->execute(array($residenceID));
    }

    function deleteResidenceComments($residenceID) {
        $comments = getResidenceComments($residenceID);
        foreach($comments as $comment) {
            deleteComment($comment['commentID']);
        }
    }

    function deleteResidenceCommodities($residenceID) {
        global $dbh;

        $stmt = $dbh->prepare('DELETE FROM residenceHasCommodity WHERE lodge = ?');
        $stmt->execute(array($residenceID));
    }

    function deleteResidenceAvailabilities($residenceID) {
        global $dbh;

        $stmt = $dbh->prepare('DELETE FROM availability WHERE lodge = ?');
        $stmt->execute(array($residenceID));
    }

    function deleteResidenceReservations($residenceID) {
        $reservations = getResidenceReservations($residenceID);
        foreach($reservations as $reservation) {
            deleteReservation($reservation['reservationID']);
        }
    }

    function updateResidence($updatedRes) {
        global $dbh;

        $stmt = $dbh->prepare(
            'UPDATE residence
                SET owner = ?,
                    title = ?,
                    description = ?,
                    pricePerDay = ?,
                    capacity = ?,
                    nBedrooms = ?,
                    nBathrooms = ?,
                    nBeds = ?,
                    type = ?,
                    address = ?,
                    city = ?,
                    country = ?,
                    latitude = ?,
                    longitude = ?
            WHERE residenceID = ?');

        try {

            $stmt->execute(array(
                    $updatedRes['owner'],
                    $updatedRes['title'],
                    $updatedRes['description'],
                    $updatedRes['pricePerDay'],
                    $updatedRes['capacity'],
                    $updatedRes['nBedrooms'],
                    $updatedRes['nBathrooms'],
                    $updatedRes['nBeds'],
                    $updatedRes['type'],
                    $updatedRes['address'],
                    $updatedRes['city'],
                    $updatedRes['country'],
                    $updatedRes['latitude'],
                    $updatedRes['longitude'],
                    $updatedRes['id']
                )
            );
        }
        catch(PDOException $Exception) {
            return FALSE;
        }

        if ($stmt->rowCount() <= 0) return FALSE;

        return TRUE;
    }

    function getUserResidences($userID) {
        global $dbh;

        $stmt = $dbh->prepare('SELECT residence.*
                               FROM residence, user
                               WHERE userID = owner AND userID = ?');

        $stmt->execute(array($userID));

        return $stmt->fetchAll();
    }

    function getCommoditiesAsKeysString($id) {
        $commodities = getResidenceCommodities($id);
        $commoditiesKeys = array();

        foreach ($commodities as $commodity) {
            array_push($commoditiesKeys, $commodity['commodityID']);
        }

        return json_encode($commoditiesKeys);
    }


    function addResidencePhoto($residenceID, $path) {
        global $dbh;

        $stmt = $dbh->prepare('INSERT INTO residencePhoto(lodge, filepath)
                               VALUES (?, ?)');
        $stmt->execute(array($residenceID, $path));

        $photoID = $dbh->lastInsertId();

        $stmt = $dbh->prepare('UPDATE residencePhoto
                               SET filepath = ?
                               WHERE photoID = ?');
        $stmt->execute(array($photoID . $path, $photoID));

        return $photoID . $path;
    }

    function getResidencePhotoPath($photoID) {
        global $dbh;

        $stmt = $dbh->prepare('SELECT filepath
                               FROM residencePhoto
                               WHERE photoID = ?');
        $stmt->execute(array($photoID));

        return $stmt->fetch()['filepath'];
    }

    function removeResidencePhoto($photoID) {
        global $dbh;

        $stmt = $dbh->prepare('DELETE FROM residencePhoto WHERE photoID = ?');

        $stmt->execute(array($photoID));
    }

    function getResidenceOfPhoto($photoID) {
        global $dbh;

        $stmt = $dbh->prepare('SELECT *
                                FROM residence JOIN residencePhoto
                                ON residenceID=lodge
                                WHERE photoID = ?');
        $stmt->execute(array($photoID));
        return $stmt->fetch();
    }

    function addAvailability($residenceID, $start, $end) {

        global $dbh;

        $stmt = $dbh->prepare('INSERT INTO availability(lodge, startDate, endDate)
                               VALUES (?, ?, ?)');
        $stmt->execute(array($residenceID, $start, $end));
    }

?>