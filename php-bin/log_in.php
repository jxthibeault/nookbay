<?php
    include("database_auth.inc");
    include("logger.inc");
    include("sessions_manager.php");

    $username = $_REQUEST['username'];
    $password = $_REQUEST['password'];

    $dbKey = getDatabaseKey();
    $mysqli = new mysqli($dbKey[0], $dbKey[1], $dbKey[2], $dbKey[3]);

    if($mysqli -> connect_errno) {
        echo "Failed to connect to database: " . $mysqli -> connect_error;
        exit();
    }

    $query = "SELECT uuid, username, password FROM users WHERE username = \""
            . $username . "\"";
    $result = $mysqli -> query($query);
    $mysqli -> close();
        
    $row = $result->fetch_assoc();
    $validPassword = password_verify($password, $row['password']);
    if(!$validPassword) {
        echo "BAD LOGIN";
        logEntry(SECURITY, "Bad login attempt for " . $row["uuid"] . " from " . getRealIpAddr());
    } else {
        logEntry(SECURITY, "Successful login for " . $row["uuid"] . " from " . getRealIpAddr());
        startSession($row["uuid"]);
        echo "GOOD LOGIN";
    }

?>