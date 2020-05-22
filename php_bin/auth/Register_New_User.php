<?php
    /**
     * Registers a new user to the users database.
     *
     * @author  Joshua Thibeault <jxthibeault@gmail.com>
     * @since   v0.1-alpha
     */
    include("../include/Db_Auth.inc");
    include("../include/Logger.inc");
    include("Sessions.php");

    $username = $_REQUEST['username'];
    $email = $_REQUEST['email'];
    $password = $_REQUEST['password'];

    // Check if the username is already in use
    $mysqli = connectToDb();
    $query = "SELECT username FROM users WHERE username = \"" . $username
        . "\"";
    $result = $mysqli->query($query);

    if ($result->num_rows > 0) {
        // Halt everything and go back
        header("Location: https://nookbay.app/register.html?usernameError=true");
        exit();
    }

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

    $mysqli = connectToDb();

    $query = "INSERT INTO users (uuid, username, email, password) VALUES('"
        . $uuid . "', '" . $username . "', '" . $hashed_email . "', '"
        . $hashed_password . "')";
    $result = $mysqli -> query($query);

    $mysqli -> close();

    logEntry(5, "New user created: UUID " . $uuid);
    startSession($uuid);