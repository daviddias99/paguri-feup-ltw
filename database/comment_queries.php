<?php
    include_once('../database/connection.php');

    function getComment($commentID) {
        global $dbh;

        $stmt = $dbh->prepare('SELECT * FROM comment WHERE commentID = ?');
        $stmt->execute(array($commentID));
        return $stmt->fetch();
    }

    function getResidenceComments($residenceID) {
        global $dbh;

        $stmt = $dbh->prepare(
            'SELECT comment.* 
            FROM comment JOIN reservation
            ON comment.booking = reservation.reservationID
            WHERE reservation.lodge = ?');
        $stmt->execute(array($residenceID));
        return $stmt->fetchAll();
    }

    function getReservationComments($reservationID) {
        global $dbh;

        $stmt = $dbh->prepare(
            'SELECT comment.* 
            FROM comment JOIN reservation
            ON comment.booking = reservation.reservationID
            WHERE reservation.reservationID = ?');
        $stmt->execute(array($reservationID));
        return $stmt->fetchAll();
    }

    function getCommentReplies($commentID) {
        global $dbh;

        $stmt = $dbh->prepare('SELECT * FROM reply WHERE parent = ?');
        $stmt->execute(array($commentID));
        return $stmt->fetchAll();
    }

    function deleteComment($commentID) {
        global $dbh;

        if (getComment($commentID) == FALSE)
            return FALSE;

        $stmt = $dbh->prepare('DELETE FROM reply WHERE parent = ?');
        $stmt->execute(array($commentID));

        $stmt = $dbh->prepare('DELETE FROM comment WHERE commentID = ?');
        $stmt->execute(array($commentID));
    }

?>