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

    function deleteReservation($reservationID) {
        global $dbh;

        deleteReservationComments($reservationID);

        $stmt = $dbh->prepare('DELETE FROM reservation WHERE reservationID = ?');
        $stmt->execute(array($reservationID));
    }

    function deleteReservationComments($reservationID) {
        $comments = getReservationComments($reservationID);
        foreach($comments as $comment) {
            deleteComment($comment['commentID']);
        }
    }

?>