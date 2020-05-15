<?php
    namespace Nookbay\Register_New_User;

    require_once("Db_Auth.inc");
    require_once("Logger.inc");

    $username = $_REQUEST['username'];
    $email = $_REQUEST['email'];
    $password = $_REQUEST['password'];

    $uuidChars = "23456789ABCDEFGHJKLMNPQRSTUVWXYZ";
    $uuid = substr(str_shuffle($uuidChars), 0, 16);
    $uuidDelimiter = "-";

    $position = 4;
    $uuid = substr_replace($uuid, $uuidDelimiter, $position, 0);
    $position = 9;
    $uuid = substr_replace($uuid, $uuidDelimiter, $position, 0);
    $position = 14;
    $uuid = substr_replace($uuid, $uuidDelimiter, $position, 0);

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $hashedEmail = password_hash($email, PASSWORD_DEFAULT);

    $dbKey = \Nookbay\Db_Auth\getDatabaseKey();
    $mysqli = new mysqli($dbKey[0], $dbKey[1], $dbKey[2], $dbKey[3]);

    if($mysqli -> connect_errno) {
        echo "Failed to conenct to database: " . $mysqli -> connect_error;
        exit();
    }

    $query = "INSERT INTO users (uuid, username, email, password) VALUES('"
        . $uuid . "', '" . $username . "', '" . $hashedEmail . "', '"
        . $hashedPassword . "')";
    $result = $mysqli -> query($query);

    $mysqli -> close();

    \Nookbay\Logger\logEntry(ACCOUNTS, "New user created: UUID " . $uuid);
    \Nookbay\Sessions\startSession($uuid);
