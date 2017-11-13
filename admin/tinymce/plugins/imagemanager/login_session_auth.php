<?php
// Some settings
$msg = "";

//	$ifmysql = 0;
//$filename = '../library/mysql.php';
//if (file_exists($filename)) {include ("$filename"); $ifmysql = 1;}
//if ($ifmysql != 1) {$filename = '../../library/mysql.php'; if (file_exists($filename)) { include ("$filename"); $ifmysql = 1;}}
//if ($ifmysql != 1) {$filename = '../../../library/mysql.php'; if (file_exists($filename)) { include ("$filename");}}
//Konekcija za bazu
// $db_host= "localhost";
// $db_user="free1";
//	$db_pass="JsGpctA4r9";
//	$db_base="free1_dev";
//	
//
//function database_connect()
//
//{
//	$link_base = mysqli_connect($db_host, $db_user, $db_pass);	
//	$baza = mysqli_select_db($db_base, $link_base);
//
//}
//database_connect();
//$q_admins = mysqli_query($conn,"SELECT * FROM administrators WHERE id = 1");
//
//while($data = mysqli_fetch_array($q_admins, MYSQLI_ASSOC)){
//	$username = $data[username];
//	$password = $data[password]; // Change the password to something suitable
//}
//mysqli_close($link_base);

if (!$password)
    $msg = 'You must set a password in the file "login_session_auth.php" inorder to login using this page or reconfigure it the authenticator config options to fit your needs. Consult the <a href="http://wiki.moxiecode.com/index.php/Main_Page" target="_blank">Wiki</a> for more details.';

//if (isset($_POST['submit_button'])) {
// If password match, then set login
if (/* $_POST['login'] == $username && md5($_POST['password']) == $password && $password */true) {
    // Set session
    session_start();
    $_SESSION['isLoggedIn'] = true;
    $_SESSION['user'] = $_POST['login'];

    // Override any config option
    //$_SESSION['imagemanager.filesystem.rootpath'] = 'some path';
    //$_SESSION['filemanager.filesystem.rootpath'] = 'some path';
    // Redirect
    header("location: " . $_POST['return_url']);
    die;
} else
    $msg = "Wrong username/password.";
//}
?>

<html>
    <head>
        <title>Sample login page</title>
        <style>
            body { font-family: Arial, Verdana; font-size: 11px; }
            fieldset { display: block; width: 170px; }
            legend { font-weight: bold; }
            label { display: block; }
            div { margin-bottom: 10px; }
            div.last { margin: 0; }
            div.container { position: absolute; top: 50%; left: 50%; margin: -100px 0 0 -85px; }
            h1 { font-size: 14px; }
            .button { border: 1px solid gray; font-family: Arial, Verdana; font-size: 11px; }
            .error { color: red; margin: 0; margin-top: 10px; }
        </style>
    </head>
    <body>

        <div class="container">
            <form action="login_session_auth.php" method="post">
                <input type="hidden" name="return_url" value="<?= isset($_REQUEST['return_url']) ? htmlentities($_REQUEST['return_url']) : ""; ?>" />
                <?php
                echo htmlentities($_REQUEST['return_url']);
                echo $username;
                echo $password;
                ?>
                <fieldset>
                    <legend>Example login</legend>

                    <div>
                        <label>Username:</label>
                        <input type="text" name="login" class="text" value="<?= isset($_POST['login']) ? htmlentities($_POST['login']) : ""; ?>" />
                    </div>

                    <div>
                        <label>Password:</label>
                        <input type="password" name="password" class="text" value="<?= isset($_POST['password']) ? htmlentities($_POST['password']) : ""; ?>" />
                    </div>

                    <div class="last">
                        <input type="submit" name="submit_button" value="Login" class="button" />
                    </div>

                    <?php if ($msg) { ?>
                        <div class="error">
                            <?= $msg; ?>
                        </div>
                    <?php } ?>
                </fieldset>
            </form>
        </div>

    </body>
</html>
