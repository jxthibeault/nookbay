<?php
    include("./php_bin/html_include/html_footer.inc");
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>nookBay - Log In</title>
    </head>
    <body>
        <h1>Log in</h1>
        <form action="./php_bin/auth/Log_In.php" method="POST">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" />

            <label for="password">Password</label>
            <input type="password" id="password" name="password" />

            <input type="submit" value="Continue" />
        </form>
        
        <?php
            writeFooter();
        ?>
    </body>
</html>