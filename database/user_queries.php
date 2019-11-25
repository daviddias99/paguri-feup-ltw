<?php
    include_once('connection.php');

    function getAllUsers() {
        global $dbh;

        $stmt = $dbh->prepare('SELECT * FROM user');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function getUserInfo($username) {
        global $dbh;

        $stmt = $dbh->prepare('SELECT * FROM user WHERE username = ?');
        $stmt->execute(array($username));
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

    function createUser($username, $email, $firstName, $lastName, $password) {
        global $dbh;

        if (userExists($username, $email)) return FALSE;

        $options = ['cost' => 12];

        $stmt = $dbh->prepare('INSERT INTO user(username, email, firstName, lastName, password) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute(array($username, $email, $firstName, $lastName, password_hash($password, PASSWORD_DEFAULT, $options)));

        return TRUE;
    }

    function updateUserInfo($username, $email, $firstName, $lastName, $bio) {
        global $dbh;

        if (! userExists($username, $email)) return false;

        $stmt = $dbh->prepare('UPDATE user
                               SET email = ?,
                                   firstName = ?,
                                   lastName = ?,
                                   biography = ?
                               WHERE username = ? ');
        $stmt->execute(array($email, $firstName, $lastName, $bio, $username));
        return true;
    }

    function updateUserPassword($username, $password) {
        global $dbh;

        if (! userExists($username, null)) return false;

        $options = ['cost' => 12];

        $stmt = $dbh->prepare('UPDATE user SET password = ? WHERE username = ? ');

        $stmt->execute(array(password_hash($password, PASSWORD_DEFAULT, $options), $username));

        return true;
    }




    function validLogin($username, $password) {
        global $dbh;

        $stmt = $dbh->prepare('SELECT * FROM user WHERE username = ?');
        $stmt->execute(array($username));
        $user = $stmt->fetch();

        if ($user !== false && password_verify($password, $user['password']))
            return true;

        return false;
    }
?>