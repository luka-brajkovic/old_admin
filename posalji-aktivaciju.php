<?php

include_once 'library/config.php';

$md5_email = $f->getValue("md5_email");

$users = new Collection("_content_korisnici");
$usersArr = $users->getCollection("WHERE md5(`e-mail`) = '$md5_email'");
$userData = $usersArr[0];

if (!empty($userData->id)) {
    $fieldMail = "e-mail";
    if ($userData->status == 1) {

        $f->redirect("/poruka/vas-nalog-je-aktivan");
    } else if ($userData->status == 2) {

        if (!isset($_SESSION["poslat_link"])) {

            $body = "<p>Poštovani/a $userData->ime,</p>";
            $body .= "<p>Vaš nalog će te aktivirati klikom na <a href='" . $configSiteDomain . "aktivacija-naloga/$md5_email'>Aktivacioni link</a></p>";
            $body .= "<p>Ukoliko ne možete klikom da aktivirate nalog, kopirajte i otvorite u vašem pretraživaču sledeći link:<br /> <a href='" . $configSiteDomain . "aktivacija-naloga/$md5_email'>" . $configSiteDomain . "aktivacija-naloga/$md5_email</a></p>";
            $body .= "<p>Još jednom koristimo priliku da Vam se zahvalimo na korišćenju naših usluga.</p><p>Vaš " . $configSiteFirm . " tim</p>";

            $f->sendEmail($configSiteEmail, $configSiteFirm . " | Internet prodavnica", $userData->$fieldMail, "Aktivacija naloga", $body);

            $_SESSION["poslat_link"] = true;

            $f->redirect("/poruka/poslata-aktivacija");
        } else {

            $f->redirect("/poruka/ne-mozete-poslati-aktivaciju");
        }
    }
} else {
    $f->redirect("/poruka/ne-postoji-nalog");
}



