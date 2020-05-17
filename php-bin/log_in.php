<?php
    /**
     * Verifies user login and initiates a session on a good login.
     *
     * @author Joshua Thibeault <jxthibeault@gmail.com>
     * @since v0.1-alpha
     */
    include("Db_Auth.inc");
    include("Logger.inc");
    include("Sessions.php");

    $username = $_REQUEST['username'];
    $password = $_REQUEST['password'];

    $mysqli = connectToDb();

    $query = "SELECT uuid, username, password FROM users WHERE username = \""
            . $username . "\"";
    $result = $mysqli -> query($query);
    $mysqli -> close();

    $row = $result->fetch_assoc();
    $valid_password = password_verify($password, $row['password']);
    if (!$valid_password) {
        echo "BAD LOGIN";
        logEntry(4, "Bad login attempt for " . $row["uuid"]
                                 . " from " . getRealIpAddr());
    } else {
        logEntry(6, "Successful login for " . $row["uuid"]
                                 . " from " . getRealIpAddr());
        startSession($row["uuid"]);
        echo "GOOD LOGIN";
    }