<?php
    /**
     * Logs user out and terminates the associated session.
     *
     * @author  Joshua Thibeault <jxthibeault@gmail.com>
     * @since   v0.1.1a
     */
    include("../include/Db_Auth.inc");
    include("../include/Logger.inc");
    include("Sessions.php");

    endSession();
