<?php
    namespace Nookbay\Log_In;

    require_once("Db_Auth.inc");
    require_once("Logger.inc");
    require_once("Sessions.php");

    $username = $_REQUEST['username'];
    $password = $_REQUEST['password'];

    $dbKey = \Nookbay\Db_Auth\getDatabaseKey();
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
        \Nookbay\Logger\logEntry(SECURITY, "Bad login attempt for " . $row["uuid"] . " from " . \Nookbay\Sessions\getRealIpAddr());
    } else {
        \Nookbay\Logger\logEntry(SECURITY, "Successful login for " . $row["uuid"] . " from " . \Nookbay\Sessions\getRealIpAddr());
        \Nookbay\Sessions\startSession($row["uuid"]);
        echo "GOOD LOGIN";
    }
