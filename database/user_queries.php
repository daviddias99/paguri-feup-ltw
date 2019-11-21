<?php
    include_once('connection.php');

    function getAllUsers() {
        global $dbh;

        $stmt = $dbh->prepare('SELECT * FROM user');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function getUserInfo($id) {
        global $dbh;

        $stmt = $dbh->prepare('SELECT * FROM user WHERE userID = ?');
        $stmt->execute(array($id));
        return $stmt->fetch();
    }

    function getUserEmail($username) {
        global $dbh;

        $stmt = $dbh->prepare('SELECT email FROM user WHERE username = ?');
        $stmt->execute(array($username));
        return $stmt->fetch();
    }

    function getUserCredentials($username) {
        global $dbh;

        $stmt = $dbh->prepare('SELECT salt, pwdHash FROM user WHERE username = ?');
        $stmt->execute(array($username));
        return $stmt->fetch();
    }

    function userExists($username, $email) {
        global $dbh;

        $stmt = $dbh->prepare('SELECT * FROM user WHERE username = ? OR email = ?');
        $stmt->execute(array($username, $email));
        return ($stmt->fetch() === FALSE ? FALSE : TRUE);
    }

    function createUser($username, $email, $firstName, $lastName, $salt, $pwdHash) {
        global $dbh;

        if(userExists($username, $email)) return FALSE;

        $stmt = $dbh->prepare('INSERT INTO user(username, email, firstName, lastName, salt, pwdHash) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute(array($username, $email, $firstName, $lastName, $salt, $pwdHash));

        return TRUE;
    }

    function validLogin($username, $password) {
        global $dbh;

        $stmt = $dbh->prepare('SELECT * FROM user WHERE username = ? AND pwdHash = ?');
        $stmt->execute(array($username, $password));

        return $stmt->fetch();
    }
?>