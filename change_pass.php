<?php
include_once ("library/config.php");

$md5_email = $f->getValue("md5_email");

$userQuery = mysqli_query($conn,"SELECT * FROM _content_users WHERE MD5(`e-mail`) = '$md5_email' AND poslat_email = '" . date("Y-m-d") . "'");

if (mysqli_num_rows($userQuery) == 0) {
    $f->redirect("/");
}
$errors = array();
$hitted = $f->getValue("change_pass");
if ($hitted != '') {
    $pass = $f->getValue("pass");
    $repass = $f->getValue("repass");

    if ($pass != $repass) {
        array_push($errors, "nisu-iste-lozinke");
    } else {
        mysqli_query($conn,"UPDATE _content_users SET lozinka = '" . md5($pass) . "', poslat_email = '0000-00-00' WHERE MD5(`e-mail`) = '$md5_email'");
        $f->redirect("/");
    }
}
?>
<?php
include_once ("head.php");
?>
</head>
<body>
    <?php
    include_once ("log-header.php");
    ?>
    <div class="container logCont ">
        <h1>Unesite Vašu novu lozinku <a class="right" style="line-height: 34px;" title="<?= $csDomain; ?>" href="/">Natrag na naslovnu stranicu</a></h1>
        <p>U polja ispod unesite lozinku koju želite da imate za Vaš nalog na web sajtu <?= $csName; ?></p>
        <div class="gray third">
            <form class="" method="POST" action="" id="" >
                <input type="hidden" name="md5_email" value="<?= $f->getValue("md5_email"); ?>" />
                <?php if (!empty($errors)) {
                    ?>
                    <p><em style="color:red; display: block;">Greška! Polja za lozinku moraju biti popunjena i identična.</em></p>
                    <?php
                }
                ?>
                <p>    
                    <label for="password">Unesite novu lozinku</label>
                    <input class="box" type="password" name="pass" value="" placeholder="" />
                </p>
                <p>
                    <label for="password">Ponovite novu lozinku</label>
                    <input class="box" type="password" name="repass" value="" placeholder="" />
                </p>
                <p style="margin-bottom: 0;">
                    <input class="box transition more" name="change_pass" type="submit" value="Sačuvaj" />
                </p>
            </form>
        </div>    
    </div>    
    <?php
    include_once ("log-footer.php");
    ?>
</body>
</html>

