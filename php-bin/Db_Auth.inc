<?php
    namespace Nookbay\Db_Auth;

    function getDatabaseKey() {
        $keyFile = fopen("./mysqlDatabaseKey", "r") or die("Database keyfile error");
        $key = array("", "");
        
        $key[0] = trim(fgets($keyFile));
        $key[1] = trim(fgets($keyFile));
        $key[2] = trim(fgets($keyFile));
        $key[3] = trim(fgets($keyFile));
        
        fclose($keyFile);
        return $key;
    }
