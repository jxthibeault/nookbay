<?php
    /**
     * Handles the creation and authentication of user sessions
     *
     * @author  Joshua Thibeault <jxthibeault@gmail.com>
     * @since   v0.1-alpha
     */
    namespace Nookbay\Sessions;
    require_once("Db_Auth.inc");
    require_once("Logger.inc");

    /**
     * This gets the true IP address of the client regardless of proxy or
     * HTTP header mutation.
     *
     * @author  Joshua Thibeault <jxthibeault@gmail.com>
     * @since   v0.1-alpha
     *
     * @return  string  String representation of client IP address
     */
    function getRealIpAddr() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    /**
     * This initiates a user session on the server and client side and deletes
     * all old sessions for this user on this client.
     *
     * @author  Joshua Thibeault <jxthibeault@gmail.com>
     * @since   v0.1-alpha
     *
     * @param   string  $uuid   UUID of the user to open a session for.
     * @return  null
     */
    function startSession($uuid) {

        $auth_cookie = "nookbayAuth";
        $timestamp = date("Y-m-d H:i:s");

        $db_key = \Nookbay\Db_Auth\getDatabaseKey();
        $mysqli = new mysqli($db_key[0], $db_key[1], $db_key[2], $db_key[3]);

        if ($mysqli -> connect_errno) {
            echo "Failed to connect to database: " . $mysqli -> connect_error;
            exit();
        }

        // Check for other (old) sessions with the same UUID on the same client
        $query = "SELECT sessionID FROM active_sessions WHERE uuid = \"" . $uuid
                 . "\" AND hostIP = \"" . getRealIpAddr() . "\"";
        $result = $mysqli -> query($query);

        // Delete any old sessions with the same UUID on the same host
        if($result->num_rows > 0) {
            while ($row = $result -> fetch_assoc()) {
                $query = "DELETE FROM active_sessions WHERE sessionID = \""
                        . $row["sessionID"]
                        . "\"";

                $mysqli -> query($query);
                $mysqli -> close();
                \Nookbay\Logger\logEntry(6, "Closed conflicting session "
                                         . $row["sessionID"]);

                $db_key = \Nookbay\Db_Auth\getDatabaseKey();
                $mysqli = new mysqli($db_key[0], $db_key[1], $db_key[2],
                                     $db_key[3]);
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
        $query = "INSERT INTO active_sessions (sessionID, uuid, hostIP,
                firstAuth, lastUsed) VALUES('"
                . $sid . "', '" . $uuid . "', '" . getRealIpAddr()
                . "', '" . $timestamp . "', '" . $timestamp . "')";

        $result = $mysqli -> query($query);
        $mysqli -> close();

        // Set a cookie on the client identifying the active session
        $session_expiration = time() + 60*60*24*21;
        setcookie($auth_cookie, $sid, $session_expiration, "/", "nookbay.app",
                  1, 1);

        \Nookbay\Logger\logEntry(6, "Started session: " . $sid);

    }

    /**
     * This authenticates an existing session by checking that the session ID
     * and client IP address a) exist, and b) are paired in the database.
     *
     * @author  Joshua Thibeault <jxthibeault@gmail.com>
     * @since   v0.1-alpha
     *
     * @return  boolean TRUE for a valid session, FALSE for an invalid session
     */
    // returns TRUE on a valid session, otherwise returns FALSE
    function isValidSession() {
        $auth_cookie = "nookbayAuth";

        if (!isset($_COOKIE[$auth_cookie])) {
            return FALSE;
        } else {
            $db_key = \Nookbay\Db_Auth\getDatabaseKey();
            $mysqli = new mysqli($db_key[0], $db_key[1], $db_key[2], $db_key[3]);

            if($mysqli -> connect_errno) {
                return FALSE;
            }

            $query = "SELECT ";
        }
    }