<?php
include_once('../database/connection.php');

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

    $stmt = $dbh->prepare('SELECT name FROM residenceType');
    $stmt->execute();

    return $stmt->fetchAll();
}

function getAllCommodities()
{
    global $dbh;

    $stmt = $dbh->prepare('SELECT name FROM commodity');
    $stmt->execute();

    return $stmt->fetchAll();
}

function getResidenceTypeWithID($typeID)
{
    global $dbh;

    $stmt = $dbh->prepare('SELECT name FROM residenceType WHERE residenceTypeID = ?');
    $stmt->execute(array($typeID));

    return $stmt->fetch()['name'];
}

function getResidenceInfo($residenceID)
{
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

function getResidencePhotos($residenceID)
{
    global $dbh;

    $stmt = $dbh->prepare('SELECT filepath, priority FROM residencePhoto WHERE lodge = ?');
    $stmt->execute(array($residenceID));
    return $stmt->fetchAll();
}

function getResidenceComments($residenceID)
{
    global $dbh;

    $stmt = $dbh->prepare('SELECT * FROM comment WHERE lodge = ?');
    $stmt->execute(array($residenceID));
    return $stmt->fetchAll();
}

function getCommentReplies($commentID)
{
    global $dbh;

    $stmt = $dbh->prepare('SELECT * FROM reply WHERE parent = ?');
    $stmt->execute(array($commentID));
    return $stmt->fetchAll();
}

function getResidenceCommodities($residenceID)
{
    global $dbh;

    $stmt = $dbh->prepare(
        'SELECT name
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

    // Some injection safety for the rating variables. Since the query is not working properly we need this workaround
    
    $minRating = floatval($minRating);
    $maxRating = floatval($maxRating);

    $stmt = $dbh->prepare(
        'SELECT residence.*, residencetype.name as typeStr , rating
        FROM residence JOIN residencetype 
                        ON residence.type = residenceTypeID 
                       LEFT JOIN (SELECT lodge, avg(rating) as rating
                             FROM comment JOIN reservation ON (comment.booking = reservation.reservationID) 
                             GROUP BY lodge
                            ) as avgRatingPerResidence
                        ON residence.residenceID = avgRatingPerResidence.lodge
            WHERE capacity >= ? AND nBeds >= ? AND  ( pricePerDay BETWEEN ? AND ?  ) AND typeStr = ? and ( (rating BETWEEN ' . $minRating . ' AND ' . $maxRating .') or (rating IS NULL))');

    $stmt->execute(array($capacity, $nBeds, $minPrice, $maxPrice , $type));

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

function createResidence($residenceObj)
{
    global $dbh;

    $stmt = $dbh->prepare(
        'INSERT INTO 
            residence(owner, title, description, pricePerDay, capacity, nBedrooms, 
            nBathrooms, nBeds, type, address, city, country, latitude, longitude)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)'
    );
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
    ));
}
