<?php
    namespace Nookbay\Logger;

    require_once("Db_Auth.inc");

    define("INFORMATION", "INFORMATION");
    define("SECURITY", "SECURITY");
    define("ERROR", "ERROR");
    define("ACCOUNTS", "ACCOUNTS");
    define("TRANSACTION", "TRANSACTION");

    function logEntry($level, $entryText) {
    
        $eventIDChars = "0123456789";
        $eventID = substr(str_shuffle($eventIDChars), 0, 16);
        
        $timestamp = date("Y-m-d H:i:s");
        
        $dbKey = \Nookbay\Db_Auth\getDatabaseKey();
        $mysqli = new mysqli($dbKey[0], $dbKey[1], $dbKey[2], $dbKey[3]);
        
        if($mysqli -> connect_errno) {
            echo "Failed to connect to database: " . $mysqli -> connect_error;
            exit();
        }
        
        $query = "INSERT INTO event_log (eventID, timestamp, level, event) VALUE ('"
            . $eventID . "', '" . $timestamp . "', '" . $level . "', '"
            . $entryText . "')";
        $result = $mysqli -> query($query);

        $mysqli -> close();
    }
