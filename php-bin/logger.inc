<?php

    define("INFORMATION", "SECURITY");
    define("SECURITY", "SECURITY");
    define("ERROR", "ERROR");
    define("ACCOUNTS", "ACCOUNTS");
    define("TRANSACTION", "TRANSACTION");

    function logEntry($level, $entryText) {
    
        $eventIDChars = "0123456789";
        $eventID = substr(str_shuffle($eventIDChars), 0, 16);
        
        $timestamp = date("Y-m-d H:i:s");
        
        $mysqli = new mysqli("localhost", "local", "password", "nookbay");
        if($mysqli -> connect_errno) {
            echo "Failed to conenct to database: " . $mysqli -> connect_error;
            exit();
        }
        
        $query = "INSERT INTO event_log (eventID, timestamp, level, event) VALUE ('"
            . $eventID . "', '" . $timestamp . "', '" . $level . "', '"
            . $entryText . "')";
        $result = $mysqli -> query($query);

        $mysqli -> close();
    }

?>