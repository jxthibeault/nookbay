<?php
    include("logger.php");
    
    $authCookie = "nookbayAuth";
    $timestamp = date("Y-m-d H:i:s");
    
    function getRealIpAddr() {
        if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    function startSession($uuid) {
    
        $mysqli = new mysqli("localhost", "local", "password", "nookbay_data");

        if($mysqli -> connect_errno) {
            echo "Failed to connect to database: " . $mysqli -> connect_error;
            exit();
        }
        
        $sidChars = "23456789ABCDEFGHJKLMNPQRSTUVWXYZ";
        $sid = substr(str_shuffle($uuidChars), 0, 16);
        $sidDelimiter = "-";

        $position = 4;
        $sid = substr_replace($sid, $sidDelimiter, $position, 0);
        $position = 9;
        $sid = substr_replace($sid, $sidDelimiter, $position, 0);
        $position = 14;
        $sid = substr_replace($sid, $sidDelimiter, $position, 0);
        
        $query = "INSERT INTO active_sessions (sessionID, uuid, hostIP, firstAuth, lastUsed) VALUES('"
            . $sid . "', '" . $uuid . "', '" . getRealIpAddr()
            . "', '" . $timestamp . "', '" . $timestamp . "')";
            
        $result = $mysqli -> query($query);
        $mysqli -> close();
        
        $sessionExpiration = time() + 60*60*24*21;
        setcookie($authCookie, $sid, $sessionExpiration);
    
        logEntry(SECURITY, "Started session: " . $sid)
    }
    
    function endSession() {
    
    }
    
    function validateSession() {
    
    }

?>