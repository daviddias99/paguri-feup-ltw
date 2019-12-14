<?php

$dbh = new PDO('sqlite:./paguri.db');
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$stmt = $dbh->prepare(
    'SELECT residence.*, residencetype.name as typeStr , rating
    FROM residence JOIN residencetype ON residence.type = residenceTypeID 
                    LEFT OUTER JOIN (SELECT lodge, avg(rating) as rating
                         FROM comment JOIN reservation ON (comment.booking = reservation.reservationID) 
                         GROUP BY lodge) as avgRatingPerResidence
                    ON residence.residenceID = avgRatingPerResidence.lodge
        WHERE capacity >= ? AND nBeds >= ? AND  ( pricePerDay BETWEEN ? AND ?  ) AND typeStr = ? AND  ( rating BETWEEN ? AND ?  )
        '
);


$stmt->execute(array(0, 0, 0, 5000, 'House', 0, 10));

print_r($stmt->fetchAll());
