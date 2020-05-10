<?php
    include("logger.inc");

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

    $mysqli = new mysqli("localhost", "local", "password", "nookbay_data");
    if($mysqli -> connect_errno) {
        echo "Failed to conenct to database: " . $mysqli -> connect_error;
        exit();
    }

    $query = "INSERT INTO users (uuid, username, email, password) VALUES('"
        . $uuid . "', '" . $username . "', '" . $hashedEmail . "', '"
        . $hashedPassword . "')";
    $result = $mysqli -> query($query);

    $mysqli -> close();

    logEntry(ACCOUNTS, "New user created: UUID " . $uuid);
?>