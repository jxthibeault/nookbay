<?php
    include("logger.php");
    include("session_manager.php");

    $username = $_REQUEST['username'];
    $password = $_REQUEST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $mysqli = new mysqli("localhost", "local", "password", "nookbay_data");

    if($mysqli -> connect_errno) {
        echo "Failed to connect to database: " . $mysqli -> connect_error;
        exit();
    }

    $query = "SELECT uuid, username, password FROM users WHERE username = '"
            . $username . "' AND password = '" . $passwordHash . "'";
    $result = $mysqli -> query($query);
        
    $row = $result->fetch_assoc();
    if($result -> num_rows < 1) {
        echo "BAD LOGIN";
        logEntry(SECURITY, "Bad login attempt for " . $row["uuid"] . " from " . getRealIpAddr());
    } else {
        logEntry(SECURITY, "Successful login for " . $row["uuid"] . " from " . getRealIpAddr());
        startSession($row["uuid"]);
        echo "GOOD LOGIN";
    }

?>