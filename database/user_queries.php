<?php
include_once('../database/connection.php');

function getAllUsers()
{
    global $dbh;

    $stmt = $dbh->prepare('SELECT * FROM user');
    $stmt->execute();
    return $stmt->fetchAll();
}

function getUserIDByUsername($username) {
    global $dbh;

    $stmt = $dbh->prepare('SELECT userID FROM user WHERE username = ?');
    try {
        $stmt->execute(array($username));
    } catch (PDOException $Exception) {
        return FALSE;
    }
    return $stmt->fetch()['userID'];
}

function getUserInfo($username)
{
    global $dbh;

    $stmt = $dbh->prepare('SELECT * FROM user WHERE username = ?');
    try {
        $stmt->execute(array($username));
    } catch (PDOException $Exception) {
        return FALSE;
    }
    return $stmt->fetch();
}

function getUserInfoById($id)
{
    global $dbh;

    $stmt = $dbh->prepare('SELECT * FROM user WHERE userID = ?');
    $stmt->execute(array($id));
    return $stmt->fetch();
}

function getUserInfoByEmail($email)
{
    global $dbh;

    $stmt = $dbh->prepare('SELECT * FROM user WHERE email = ?');
    $stmt->execute(array($email));
    return $stmt->fetch();
}

function getUserEmail($username)
{
    global $dbh;

    $stmt = $dbh->prepare('SELECT email FROM user WHERE username = ?');
    $stmt->execute(array($username));
    return $stmt->fetch();
}

function getUserCredentials($username)
{
    global $dbh;

    $stmt = $dbh->prepare('SELECT salt, pwdHash FROM user WHERE username = ?');
    $stmt->execute(array($username));
    return $stmt->fetch();
}

function userExists($username, $email)
{
    global $dbh;

    $stmt = $dbh->prepare('SELECT * FROM user WHERE username = ? OR email = ?');
    $stmt->execute(array($username, $email));
    return ($stmt->fetch() === FALSE ? FALSE : TRUE);
}

function userExistsById($userid)
{
    global $dbh;

    $stmt = $dbh->prepare('SELECT * FROM user WHERE userID = ?');
    $stmt->execute(array($userid));
    return ($stmt->fetch() === FALSE ? FALSE : TRUE);
}

function userExistsByUsername($username)
{
    global $dbh;

    $stmt = $dbh->prepare('SELECT * FROM user WHERE username = ?');
    $stmt->execute(array($username));
    return ($stmt->fetch() === FALSE ? FALSE : TRUE);
}

function createUserByObj($userObj)
{
    global $dbh;

    if (userExists($userObj['username'], $userObj['email']))
        return FALSE;

    $options = ['cost' => 12];

    $stmt = $dbh->prepare('INSERT INTO user(username, email, firstName, lastName, password) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute(array(
        $userObj['username'],
        $userObj['email'],
        $userObj['firstName'],
        $userObj['lastName'],
        password_hash(
            $userObj['password'],
            PASSWORD_DEFAULT,
            $options
        )
    ));

    if ($stmt->rowCount() <= 0) return FALSE;

    return $dbh->lastInsertId();
}

function createUser($username, $email, $firstName, $lastName, $password)
{
    global $dbh;

    if (userExists($username, $email)) return FALSE;

    $options = ['cost' => 12];

    $stmt = $dbh->prepare('INSERT INTO user(username, email, firstName, lastName, password) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute(array($username, $email, $firstName, $lastName, password_hash($password, PASSWORD_DEFAULT, $options)));

    return $dbh->lastInsertId();
}

function updateUserInfo($username, $newUsername, $email, $firstName, $lastName, $bio)
{
    global $dbh;

    if (!userExists($username, $email)) return false;

    $stmt = $dbh->prepare('UPDATE user
                               SET username = ?,
                                   email = ?,
                                   firstName = ?,
                                   lastName = ?,
                                   biography = ?
                               WHERE username = ? ');
    $stmt->execute(array($newUsername, $email, $firstName, $lastName, $bio, $username));
    return true;
}

function updateUserPassword($username, $password)
{
    global $dbh;

    if (!userExists($username, null)) return false;

    $options = ['cost' => 12];

    $stmt = $dbh->prepare('UPDATE user SET password = ? WHERE username = ? ');

    $stmt->execute(array(password_hash($password, PASSWORD_DEFAULT, $options), $username));

    return true;
}

function validLogin($username, $password)
{
    global $dbh;

    $stmt = $dbh->prepare('SELECT * FROM user WHERE username = ?');
    $stmt->execute(array($username));
    $user = $stmt->fetch();

    if ($user !== false && password_verify($password, $user['password']))
        return true;

    return false;
}

function getUserPhotoID($username)
{
    global $dbh;

    $stmt = $dbh->prepare('SELECT photo FROM user WHERE username = ?');
    $stmt->execute(array($username));

    return $stmt->fetch()['photo'];
}

function updateProfilePicture($username, $photoID)
{
    global $dbh;

    $oldPhotoID = getUserPhotoID($username);

    $stmt = $dbh->prepare('UPDATE user SET photo = ? WHERE username = ?');
    $stmt->execute(array($photoID, $username));

    return $oldPhotoID;
}

    function getUserID($username) {
        global $dbh;

    $stmt = $dbh->prepare('SELECT RowID FROM user WHERE username = ?');
    $stmt->execute(array($username));

        return $stmt->fetch()['userID'];
    }

    function userHasResidence($username, $residenceID)
    {
        global $dbh;

        $stmt = $dbh->prepare('SELECT *
                                FROM residence, user
                                WHERE userID = owner AND username = ? AND residenceID = ?');

        $stmt->execute(array($username, $residenceID));
        

        return $stmt->fetch();
    }
?>
