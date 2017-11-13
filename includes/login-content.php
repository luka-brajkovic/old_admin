<?php
$greske = array();

if ($f->verifyFormToken('form1')) {

    $email = $f->getValue("email");
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($greske, "email");
    } else {
        $email = $f->test_input($email);
    }

    $pass = $f->getValue("pass");
    if (strlen($pass) < 6) {
        array_push($greske, "pass");
    }
    if (empty($greske)) {
        $userData = new View("_content_users", $email, "e-mail");
        if ($userData->id) {
            if ($userData->lozinka != md5($pass)) { /* OVDE PROMENI KAKO SE ZOVE LOZINKA U ADMINU */
                array_push($greske, "pogresno");
            } else if ($userData->status == 2) {
                array_push($greske, "ne_aktiviran");
                $my_query = mysqli_query($conn, "SELECT `e-mail` FROM _content_users WHERE id = $userData->id");
                $email_neaktivan = mysqli_fetch_array($my_query);
                $email_neaktivan = $email_neaktivan["e-mail"];
            } else {
                $_SESSION["loged_user"] = $userData->id;
                $sessionID = session_id();
                $korpa = new View("korpa", $sessionID, 'session_id');
                if (!empty($korpa->id)) {
                    $f->redirect('/korpa-prijava');
                } else {
                    $_SESSION['infoTitle'] = "<h1>Dobrodošli</h1>";
                    $_SESSION['infoText'] = "<p>Poštovani, želimo Vam prijatno korišćenje naše internet prodavnice.</p>";
                    $f->redirect("/");
                }
            }
        } else {
            array_push($greske, "ne_postoji");
        }
    }
}
?>
<div class="container">
    <ul class="pagePosition clear" itemtype="http://data-vocabulary.org/BreadcrumbList">
        <li>
            <span>Vi ste ovde:</span>
        </li>
        <li itemprop="itemListElement" itemscope="itemscope" itemtype="http://schema.org/ListItem">
            <a title="Početna" href="/" itemprop="url">
                <span itemprop="name">Početna</span>
            </a>
        </li>
        <li>
            <span itemprop="name">Prijava</span>
        </li>
    </ul>
    <h1>Dobrodošli na <?= $csName; ?><a class="right" style="line-height: 34px;" title="<?= $csDomain; ?>" href="/">Natrag na naslovnu stranicu</a></h1>
    <div class="row">
        <div class="logCont third">
            <div class="inner">
                <h4>Prijava postojećeg korisnika:</h4>
                <div class="gray">
                    <?php
                    if (in_array("ne_aktiviran", $greske)) {
                        echo "<em style='display:block;'>* Nalog nije aktiviran. Poslali smo Vam e-mail sa aktivacionim linkom, pokušajte da ga pronađete u Vašem inboxu.<br/><br/>Ukoliko ga ne možete pronaći kliknite na <a href='/posalji-aktivaciju'>pošalji aktivaciju</a> opet.</em>";
                    }
                    ?>    
                    <?php
                    if (in_array("pogresno", $greske)) {
                        echo "<em style='display:block;'>* Uneli ste pogresne podatke. Pokusajte ponovo</em>";
                    }
                    ?>
                    <?php
                    if (in_array("ne_postoji", $greske)) {
                        echo "<em style='display:block;'>* Nalog sa ovim e-mailom ne postoji. Pokusajte ponovo</em>";
                    }

                    $newToken = $f->generateFormToken('form1');
                    ?>
                    <form action="<?php echo htmlspecialchars("/prijava"); ?>" method="post">
                        <input type="hidden" name="okinuto" value="1" />
                        <p>
                            <label>E-mail adresa:</label>
                            <input type="text" name="email" value="<?= $email; ?>" />
                            <?php
                            if (in_array("email", $greske)) {
                                echo "<em style='display:block;'>* Molimo unesite ispravnu e-mail adresu</em>";
                            }
                            ?>
                        </p>
                        <p>
                            <label>Lozinka:</label>
                            <input type="password" name="pass" value="" />
                            <?php
                            if (in_array("pass", $greske)) {
                                echo "<em style='display:block;'>* Molimo unesite ispravnu lozinku</em>";
                            }
                            ?>
                        </p>
                        <p>
                            <a id="forgotPass" href="javascript:void(0);">Zaboravljena lozinka?</a>    
                        </p>
                        <input class="more" type="hidden" value="<?= $_GET['ref']; ?>" name="ref" />
                        <input class="more" type="submit" value="Prijavi se" />
                        <input type="hidden" name="token" value="<?= $newToken; ?>">
                    </form>
                    <div class="newUser">
                        <p class="reg"><strong>Novi korisnik?</strong><br />Pridružite se hiljadama naših kupaca koji već uživaju u svim prednostima on-line kupovine.</p>
                        <a class="moreSecund" href="/registracija" title="">Registruj se</a>
                    </div>
                </div>    
            </div>
        </div>
        <div class="third-x2">
            <div class="inner pictureRight">
                <?= ($db->getValue("text", "_content_html_blocks", "resource_id", "2") != '') ? $db->getValue("text", "_content_html_blocks", "resource_id", "2") : ""; ?>
            </div>    
        </div>
    </div>
</div>