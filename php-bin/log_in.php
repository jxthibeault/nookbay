<?php
    namespace Nookbay\Log_In;

    require_once("Db_Auth.inc");
    require_once("Logger.inc");
    require_once("Sessions.php");

    $username = $_REQUEST['username'];
    $password = $_REQUEST['password'];

    $db_key = \Nookbay\Db_Auth\getDatabaseKey();
    $mysqli = new mysqli($db_key[0], $db_key[1], $db_key[2], $db_key[3]);

    if($mysqli -> connect_errno) {
        echo "Failed to connect to database: " . $mysqli -> connect_error;
        exit();
    }

    $query = "SELECT uuid, username, password FROM users WHERE username = \""
            . $username . "\"";
    $result = $mysqli -> query($query);
    $mysqli -> close();
        
    $row = $result->fetch_assoc();
    $valid_password = password_verify($password, $row['password']);
    if(!$valid_password) {
        echo "BAD LOGIN";
        \Nookbay\Logger\logEntry(5, "Bad login attempt for " . $row["uuid"] . " from " . \Nookbay\Sessions\getRealIpAddr());
    } else {
        \Nookbay\Logger\logEntry(6, "Successful login for " . $row["uuid"] . " from " . \Nookbay\Sessions\getRealIpAddr());
        \Nookbay\Sessions\startSession($row["uuid"]);
        echo "GOOD LOGIN";
    }
