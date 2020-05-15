<?php
    namespace Nookbay\Db_Auth;

    function getDatabaseKey() {
        $key_file = fopen("./mysqlDatabaseKey", "r") or die("Database key_file error");
        $key = array("", "");
        
        $key[0] = trim(fgets($key_file));
        $key[1] = trim(fgets($key_file));
        $key[2] = trim(fgets($key_file));
        $key[3] = trim(fgets($key_file));
        
        fclose($key_file);
        return $key;
    }
