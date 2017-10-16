<?php
require("library/config.php");

$action = $f->getValue("action");
$infomsg = $f->getValue("infomsg")
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>My Admin - Login</title>
        <link type="text/css" href="/issedit/resources/css/login.css" rel="stylesheet" />
    </head>

    <body>

        <div id="container">
            <div class="logo">
                <a href=""><img src="/issedit/resources/assets/logo-login.png" alt="" /></a>
            </div>
            <div id="box">

                <?php
                switch ($action) {
                    default:
                        ?>
                        <form action="work.php" method="POST">
                            <p class="main">
                                <label>Username: </label>
                                <input name="username" class="sf" /> 

                                <label>Password: </label>
                                <input type="password" name="password" class="sf" <?php if ($infomsg == "wrong_password") { ?> style="background:#FFAEB0"<?php } ?>>


                                    <input type="hidden" name="action" value="login" />
                            </p>

                            <p class="space">
                                <input type="submit" value="Login" class="login" />
                            </p>
                            <p align="right"><a href="login.php?action=lostPass" style="font-size:10px; color:#000000;">Forgot password</a></p>

                        </form>

                        <?php
                        break;
                    case "lostPass":
                        ?>
                        <form action="work.php" method="POST">

                            <p class="main" align="right">
                                <label>Email: </label>
                                <input type="text" name="email" class="sf" />
                                <input type="hidden" name="action" value="lostPass" />
                                <label>Press ENTER or <a href="login.php">log in</a>.</label>
                            </p>
                        </form>
                        <?php
                        break;
                }
                ?>
            </div>
        </div>

    </body>
</html>
