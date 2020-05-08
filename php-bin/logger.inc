<?php

    define("INFORMATION", "SECURITY");
    define("SECURITY", "SECURITY");
    define("ERROR", "ERROR");
    define("ACCOUNTS", "ACCOUNTS");
    define("TRANSACTION", "TRANSACTION");

    function logEntry($level, $entryText) {
        
        $eventID = openssl_random_pseudo_bytes(4, TRUE);
        $timestamp = date("Y-m-d H:i:s");
        
        $connection = mysql_connect("localhost", "local", "password")
            or die("Couldn't connect to database host.");
        $db = mysql_select_db("nookbay", $connection)
            or die("Couldn't select database.");
        
        $query = "INSERT INTO event_log (eventID, timestamp, level, event) VALUE ('"
            . $eventID . "', '" . $timestamp . "', '" . $level . "', '"
            . $entryText . "')";
        $result = mysql_query($query)
            or die("Query failed: " . mysql_error());
        
        mysql_close($connection);
    }

?>