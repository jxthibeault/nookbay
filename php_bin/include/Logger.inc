<?php
    /**
     * Handles all application logging to the logs database.
     *
     * @author  Joshua Thibeault <jxthibeault@gmail.com>
     * @since   v0.1-alpha
     */

    define("SYSLOG", array(
        "EMERGENCY",
        "ALERT",
        "CRITICAL",
        "ERROR",
        "WARNING",
        "NOTICE",
        "INFO",
        "DEBUG"
    ));

    /**
     * Writes an entry to the system log.
     *
     * @author  Joshua Thibeault <jxthibeault@gmail.com>
     * @since   v0.1-alpha
     *
     * @param   int     $level      RFC 5424 SYSLOG level of the message
     * @param   string  $entryText  Text to write to the log
     * @return  null
     */
    function logEntry($level, $entryText) {

        $event_id_chars = "0123456789";
        $event_id = substr(str_shuffle($event_id_chars), 0, 16);

        $timestamp = date("Y-m-d H:i:s");

        $mysqli = connectToDb();

        $query = "INSERT INTO event_log (event_id, timestamp, level, event) VALUE ('"
            . $event_id . "', '" . $timestamp . "', '" . SYSLOG[$level] . "', '"
            . $entryText . "')";
        $result = $mysqli -> query($query);

        $mysqli -> close();
    }