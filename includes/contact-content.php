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
            <div class="row">
                <div class="quarter">
                    <h4>Adresa</h4>
                    <p><?= $csAddress . ",<br>" . $csZip . " " . $csCity . "<br>" . $csCountry; ?></p>
                </div>
                <div class="quarter">
                    <h4>Radno vreme</h4>
                    <p>Ponedeljak - Petak: <?= $csWorkingTime1; ?></p>
                    <p>Subota: <?= $csWorkingTime2; ?></p>
                    <p>Nedelja: <?= $csWorkingTime3; ?></p>
                </div>
                <div class="quarter">
                    <h4>Telefon</h4>
                    <p><a href="tel:<?= $csPhone; ?>"><?= $csPhone; ?></a></p>
                    <p><a href="tel:<?= $csPhone2; ?>"><?= $csPhone2; ?></a></p>
                </div>
                <div class="quarter">
                    <h4>Email</h4>
                    <p><a href="mailto:<?= $csEmail; ?>"><?= $csEmail; ?></a></p>
                </div>
            </div>
            <?php
            if ($userData->resource_id) {
                if ($userData->title != "" && $name == "") {
                    $nameMess = $userData->title;
                    $userDataMail = mysqli_query($conn, "SELECT `e-mail` as email FROM _content_users WHERE resource_id = $userData->resource_id LIMIT 1");
                    $userDataMails = mysqli_fetch_object($userDataMail);
                } else {
                    $nameMess = $name;
                }
                if (mysqli_num_rows($userDataMail) > 0 && $email == "") {
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
        <form class="contactForm clear" action="<?php echo htmlspecialchars($REQUEST); ?>#contact" method="post">
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
        if ($csGoogleMap != "") {
            ?>
            <h1>Pronađite <?= $csName; ?> na mapi</h1>   
            <iframe src="https://www.google.com/maps/embed?<?= $csGoogleMap; ?>" width="100%" height="350" frameborder="0" style="border:0" allowfullscreen></iframe>
        <?php } else if ($csCoordinates != "" && $csGoogleMapKey != "") { ?>
            <div id="map-canvas"></div>
        <?php } ?>
    </div>
</div>