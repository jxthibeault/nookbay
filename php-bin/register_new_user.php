<?php
    namespace Nookbay\Register_New_User;

    require_once("Db_Auth.inc");
    require_once("Logger.inc");

    $username = $_REQUEST['username'];
    $email = $_REQUEST['email'];
    $password = $_REQUEST['password'];

    $uuid_chars = "23456789ABCDEFGHJKLMNPQRSTUVWXYZ";
    $uuid = substr(str_shuffle($uuid_chars), 0, 16);
    $uuid_delimiter = "-";

    $position = 4;
    $uuid = substr_replace($uuid, $uuid_delimiter, $position, 0);
    $position = 9;
    $uuid = substr_replace($uuid, $uuid_delimiter, $position, 0);
    $position = 14;
    $uuid = substr_replace($uuid, $uuid_delimiter, $position, 0);

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $hashed_email = password_hash($email, PASSWORD_DEFAULT);

    $db_key = \Nookbay\Db_Auth\getDatabaseKey();
    $mysqli = new mysqli($db_key[0], $db_key[1], $db_key[2], $db_key[3]);

    if($mysqli -> connect_errno) {
        echo "Failed to conenct to database: " . $mysqli -> connect_error;
        exit();
    }

    $query = "INSERT INTO users (uuid, username, email, password) VALUES('"
        . $uuid . "', '" . $username . "', '" . $hashed_email . "', '"
        . $hashed_password . "')";
    $result = $mysqli -> query($query);

    $mysqli -> close();

    \Nookbay\Logger\logEntry(ACCOUNTS, "New user created: UUID " . $uuid);
    \Nookbay\Sessions\startSession($uuid);
