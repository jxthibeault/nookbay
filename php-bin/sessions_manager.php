<?php
    
    // Provides some true-IP functionality against proxies and HTTP header mutation
    // Takes no args; returns a string representation of the client IP
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


    /* Once user has been authenticated, generate a new session for the specified UUID on the specified
        client, check for and invalidate any old sessions for the specified UUID on the specified client,
        write a session entry to the sessions database, and store the session ID on the client machine */

    // $uuid: full-form UUID of authenticated user; returns NULL
    function startSession($uuid) {
    
        $authCookie = "nookbayAuth";    // name of cookie browser uses for session validation
        $timestamp = date("Y-m-d H:i:s");
        
        $dbKey = getDatabaseKey();
        $mysqli = new mysqli($dbKey[0], $dbKey[1], $dbKey[2], $dbKey[3]);

        if($mysqli -> connect_errno) {
            echo "Failed to connect to database: " . $mysqli -> connect_error;
            exit();
        }
        
        // Check for other (old) sessions with the same UUID on the same client
        $query = "SELECT sessionID FROM active_sessions WHERE uuid = \"" . $uuid
            . "\" AND hostIP = \"" . getRealIpAddr() . "\"";
        $result = $mysqli -> query($query);

        // Delete any old sessions with the same UUID on the same host from sessions database
        if($result->num_rows > 0) {
            while($row = $result -> fetch_assoc()) {
                $query = "DELETE FROM active_sessions WHERE sessionID = \"" . $row["sessionID"]
                    . "\"";
                
                $mysqli -> query($query);
                $mysqli -> close();
                logEntry(INFORMATION, "Closed conflicting session " . $row["sessionID"]);
                
                $dbKey = getDatabaseKey();
                $mysqli = new mysqli($dbKey[0], $dbKey[1], $dbKey[2], $dbKey[3]);
            }
        }
        
        // Generate a session identifier of the form XXXX-XXXX-XXXX-XXXX
        $sidChars = "23456789ABCDEFGHJKLMNPQRSTUVWXYZ";
        $sid = substr(str_shuffle($sidChars), 0, 16);
        $sidDelimiter = "-";

        $position = 4;
        $sid = substr_replace($sid, $sidDelimiter, $position, 0);
        $position = 9;
        $sid = substr_replace($sid, $sidDelimiter, $position, 0);
        $position = 14;
        $sid = substr_replace($sid, $sidDelimiter, $position, 0);
        
        // Write new session to the sessions database
        $query = "INSERT INTO active_sessions (sessionID, uuid, hostIP, firstAuth, lastUsed) VALUES('"
            . $sid . "', '" . $uuid . "', '" . getRealIpAddr()
            . "', '" . $timestamp . "', '" . $timestamp . "')";
            
        $result = $mysqli -> query($query);
        $mysqli -> close();
        
        // Set a cookie on the client identifying the active session
        $sessionExpiration = time() + 60*60*24*21;
        setcookie($authCookie, $sid, $sessionExpiration, "/", "nookbay.app", 1, 1);
    
        logEntry(SECURITY, "Started session: " . $sid);
    
    }

    /* Check if a session already exists, and if it does, check that it's valid.
    If the session identifier is valid but coming from the wrong host, notifies an
    administrator. */
    // returns TRUE on a valid session, otherwise returns FALSE
    function isValidSession() {
        $authCookie = "nookbayAuth";
        
        if(!isset($_COOKIE[$authCookie])) {
            return false;
        } else {
            $dbKey = getDatabaseKey();
            $mysqli = new mysqli($dbKey[0], $dbKey[1], $dbKey[2], $dbKey[3]);
            
            if($mysqli -> connect_errno) {
                return false;
            }
            
            $query = "SELECT ";
        }
    }

?>