<?php
    namespace Nookbay\Logger;

    require_once("Db_Auth.inc");

    define("INFORMATION", "INFORMATION");
    define("SECURITY", "SECURITY");
    define("ERROR", "ERROR");
    define("ACCOUNTS", "ACCOUNTS");
    define("TRANSACTION", "TRANSACTION");

    function logEntry($level, $entryText) {
    
        $event_id_chars = "0123456789";
        $event_id = substr(str_shuffle($event_id_chars), 0, 16);
        
        $timestamp = date("Y-m-d H:i:s");
        
        $db_key = \Nookbay\Db_Auth\getDatabaseKey();
        $mysqli = new mysqli($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
        
        if($mysqli -> connect_errno) {
            echo "Failed to connect to database: " . $mysqli -> connect_error;
            exit();
        }
        
        $query = "INSERT INTO event_log (event_id, timestamp, level, event) VALUE ('"
            . $event_id . "', '" . $timestamp . "', '" . $level . "', '"
            . $entryText . "')";
        $result = $mysqli -> query($query);

        $mysqli -> close();
    }
