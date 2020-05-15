<?php
    /**
     * Retrieves database information for the app from a flatfile stored
     * securely on the deployment server.
     *
     * @author  Joshua Thibeault <jxthibeault@gmail.com>
     * @since   v0.1.1a
     */
    namespace Nookbay\Db_Auth;

    /**
     * Get the database information.
     *
     * @author  Joshua Thibeault <jxthibeault@gmail.com>
     * @since   v0.1.1a
     *
     * @return  array   Array containing database connection information.
     */
    function getDatabaseKey() {
        $key_file = fopen("./mysqlDatabaseKey", "r")
            or die("Database key_file error");
        $key = array("", "");

        $key[0] = trim(fgets($key_file));
        $key[1] = trim(fgets($key_file));
        $key[2] = trim(fgets($key_file));
        $key[3] = trim(fgets($key_file));

        fclose($key_file);
        return $key;
    }