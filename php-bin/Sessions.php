<?php
    namespace Nookbay\Sessions;

    require_once("Db_Auth.inc");
    require_once("Logger.inc");
    
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
    
        $auth_cookie = "nookbayAuth";    // name of cookie browser uses for session validation
        $timestamp = date("Y-m-d H:i:s");
        
        $db_key = \Nookbay\Db_Auth\getDatabaseKey();
        $mysqli = new mysqli($db_key[0], $db_key[1], $db_key[2], $db_key[3]);

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
                \Nookbay\Logger\logEntry(INFORMATION, "Closed conflicting session " . $row["sessionID"]);
                
                $db_key = \Nookbay\Db_Auth\getDatabaseKey();
                $mysqli = new mysqli($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
            }
        }
        
        // Generate a session identifier of the form XXXX-XXXX-XXXX-XXXX
        $sid_chars = "23456789ABCDEFGHJKLMNPQRSTUVWXYZ";
        $sid = substr(str_shuffle($sid_chars), 0, 16);
        $sid_delimiter = "-";

        $position = 4;
        $sid = substr_replace($sid, $sid_delimiter, $position, 0);
        $position = 9;
        $sid = substr_replace($sid, $sid_delimiter, $position, 0);
        $position = 14;
        $sid = substr_replace($sid, $sid_delimiter, $position, 0);
        
        // Write new session to the sessions database
        $query = "INSERT INTO active_sessions (sessionID, uuid, hostIP, firstAuth, lastUsed) VALUES('"
            . $sid . "', '" . $uuid . "', '" . getRealIpAddr()
            . "', '" . $timestamp . "', '" . $timestamp . "')";
            
        $result = $mysqli -> query($query);
        $mysqli -> close();
        
        // Set a cookie on the client identifying the active session
        $session_expiration = time() + 60*60*24*21;
        setcookie($auth_cookie, $sid, $session_expiration, "/", "nookbay.app", 1, 1);
    
        \Nookbay\Logger\logEntry(SECURITY, "Started session: " . $sid);
    
    }

    /* Check if a session already exists, and if it does, check that it's valid.
    If the session identifier is valid but coming from the wrong host, notifies an
    administrator. */
    // returns TRUE on a valid session, otherwise returns FALSE
    function isValidSession() {
        $auth_cookie = "nookbayAuth";
        
        if(!isset($_COOKIE[$auth_cookie])) {
            return false;
        } else {
            $db_key = \Nookbay\Db_Auth\getDatabaseKey();
            $mysqli = new mysqli($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
            
            if($mysqli -> connect_errno) {
                return false;
            }
            
            $query = "SELECT ";
        }
    }
