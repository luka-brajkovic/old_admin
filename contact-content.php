<div class="container">
    <ul class="pagePosition clear" itemtype="http://data-vocabulary.org/Breadcrumb" itemscope="">
        <li>
            <span>Vi ste ovde:</span>
        </li>
        <li property="itemListElement" typeof="ListItem">
            <a title="Početna" href="/" property="item" typeof="WebPage">
                <span property="name">Početna</span>
                <meta property="position" content="1">
            </a>
        </li>
        <li property="itemListElement" typeof="ListItem">
            <a title="Kontakt" href="/kontakt" property="item" typeof="WebPage">
                <span property="name">Kontakt</span>
                <meta property="position" content="2">
            </a>
        </li>
    </ul>
    <div class="content">
        <h1>Kontakt informacije</h1>
        <div class="kontaktInfoPage">
            <?php
            $address = mysql_query("SELECT text FROM _content_html_blocks WHERE resource_id = 8913 LIMIT 1");
            $address = mysql_fetch_object($address);
            echo $address->text;

            if ($userData->resource_id) {
                if ($userData->title != "" && $name == "") {
                    $nameMess = $userData->title;
                    $userDataMail = mysql_query("SELECT `e-mail` as email FROM _content_korisnici WHERE resource_id = $userData->resource_id LIMIT 1");
                    $userDataMails = mysql_fetch_object($userDataMail);
                } else {
                    $nameMess = $name;
                }
                if (mysql_num_rows($userDataMail) > 0 && $email == "") {
                    $emailMess = $userDataMails->email;
                } else {
                    $emailMess = $email;
                }
                if ($userData->mobilni_telefon != "" && $phone == "") {
                    $phoneMess = $userData->mobilni_telefon;
                } elseif ($userData->fiksni_telefon != "" && $phone == "") {
                    $phoneMess = $userData->fiksni_telefon;
                } else {
                    $phoneMess = $phone;
                }
            } else {
                $emailMess = $email;
                $nameMess = $name;
                $phoneMess = $telefon;
            }
            
            $newToken = $f->generateFormToken('form1');
            ?>
        </div>
        <h1 id="contact">Kontakt forma</h1>
        <form class="contactForm clear" action="<?php echo htmlspecialchars($REQUEST);?>#contact" method="post">
            <p<?= (in_array("name", $greske)) ? ' class="errorRed"' : ""; ?>>
                <input type="text" name="name" value="<?= $nameMess; ?>" placeholder="Vaše ime">
            </p>
            <p<?= (in_array("email", $greske)) ? ' class="errorRed"' : ""; ?>>
                <input type="text" name="email" value="<?= $emailMess; ?>" placeholder="Vaš email">
            </p>
            <p>
                <input type="text" name="phone" value="<?= $phoneMess; ?>" placeholder="Telefon">
            </p>
            <p<?= (in_array("message", $greske)) ? ' class="errorRed"' : ""; ?>>
                <textarea value="" name="message" placeholder="Vaša poruka"><?= ($message != "") ? $message : ""; ?></textarea>
            </p>
            <p>
                <input class="more" type="submit" value="Pošalji">
            </p>
            <input type="hidden" name="token" value="<?= $newToken; ?>">
        </form>
        <?php
        if($configSiteGoogleMap!=""){
        ?>
            <h1><?= $configSiteFirm; ?> na mapi</h1>   
            <iframe src="https://www.google.com/maps/embed?<?= $configSiteGoogleMap; ?>" width="100%" height="350" frameborder="0" style="border:0" allowfullscreen></iframe>
        <?php } ?>
    </div>
</div>