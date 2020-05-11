<?php
    
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
    
        $authCookie = "nookbayAuth";
        $timestamp = date("Y-m-d H:i:s");
        $mysqli = new mysqli("localhost", "local", "password", "nookbay_data");

        if($mysqli -> connect_errno) {
            echo "Failed to connect to database: " . $mysqli -> connect_error;
            exit();
        }
        
        $query = "SELECT sessionID FROM active_sessions WHERE uuid = \"" . $uuid
            . "\" AND hostIP = \"" . getRealIpAddr() . "\"";
        $result = $mysqli -> query($query);

        if($result->num_rows > 0) {
            while($row = $result -> fetch_assoc()) {
                $query = "DELETE FROM active_sessions WHERE sessionID = \"" . $row["sessionID"]
                    . "\"";
                
                $mysqli -> query($query);
                $mysqli -> close();
                logEntry(INFORMATION, "Closed conflicting session " . $row["sessionID"]);
                $mysqli = new mysqli("localhost", "local", "password", "nookbay_data");
            }
        }
        
        $sidChars = "23456789ABCDEFGHJKLMNPQRSTUVWXYZ";
        $sid = substr(str_shuffle($sidChars), 0, 16);
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
        setcookie($authCookie, $sid, $sessionExpiration, "/", "nookbay.app", 1, 1);
    
        logEntry(SECURITY, "Started session: " . $sid);
    
    }

    function isValidSession() {
        $authCookie = "nookbayAuth";
        
        if(!isset($_COOKIE[$authCookie])) {
            return false;
        } else {
            $mysqli = new mysqli("localhost", "local", "password", "nookbay_data");
            if($mysqli -> connect_errno) {
                return false;
            }
            
            $query = "SELECT ";
        }
    }

?>