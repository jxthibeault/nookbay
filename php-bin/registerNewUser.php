<?php
    include("logger.inc");

    $username = $_REQUEST['username'];
    $email = $_REQUEST['email'];
    $password = $_REQUEST['password'];

    $uuid = openssl_random_pseudo_bytes(8, TRUE);
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $hashedEmail = password_hash($email, PASSWORD_DEFAULT);

    $connection = mysql_connect("localhost", "local", "password")
        or die("Couldn't connect to database host.");
    $db = mysql_select_db("nookbay", $connection)
        or die("Couldn't select database.");

    $query = "INSERT INTO users (uuid, username, email, password) VALUES('"
        . $uuid . "', '" . $username . "', '" . $hashedEmail . "', '"
        . $hashedPassword . "')";
    $result = mysql_query($query)
        or die("Query failed: " . mysql_error());

    mysql_close($connection);

    logEntry(ACCOUNTS, "New user created: UUID " . $uuid);
?>