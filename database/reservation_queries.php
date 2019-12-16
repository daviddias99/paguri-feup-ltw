<?php
    include_once('../database/connection.php');
    include_once('../database/comment_queries.php');

    function getResidenceReservations($residenceID) {
        global $dbh;

        $stmt = $dbh->prepare(
            'SELECT * 
            FROM reservation
            WHERE lodge = ?');
        $stmt->execute(array($residenceID));
        return $stmt->fetchAll();
    }

    function getResidenceReservationsBetween($residenceID, $periodStart, $periodEnd) {
        global $dbh;

        $stmt = $dbh->prepare(
            'SELECT reservationID, lodge, customer, strftime("%Y-%m-%d", startDate) as startDate, strftime("%Y-%m-%d", endDate) as endDate, numPeople
            FROM reservation
            WHERE lodge = ?
            AND strftime("%Y-%m-%d", startDate) >= ?
            AND strftime("%Y-%m-%d", endDate) <= ?');
        $stmt->execute(array($residenceID, $periodStart, $periodEnd));
        return $stmt->fetchAll();
    }

    function deleteReservation($reservationID) {
        global $dbh;

        $stmt = $dbh->prepare('DELETE FROM reservation WHERE reservationID = ?');
        $stmt->execute(array($reservationID));
    }

    function deleteReservationComments($reservationID) {
        $comments = getReservationComments($reservationID);
        foreach($comments as $comment) {
            deleteComment($comment['commentID']);
        }
    }

    function addReservation($reservationObj)
    {
        global $dbh;

        $stmt = $dbh->prepare(
            'INSERT INTO 
                reservation(lodge,customer,startDate,endDate,numPeople)
                VALUES (?,?,?,?,?)');
            
        try{
            $stmt->execute(array(
                    $reservationObj['lodge'],
                    $reservationObj['customer'],
                    $reservationObj['startDate'],
                    $reservationObj['endDate'],
                    1
                    
                )
            );
        }
        catch(PDOException $Exception) {

            return FALSE;
        }

        if ($stmt->rowCount() <= 0) return FALSE;

        return $dbh->lastInsertId();
    }

?>