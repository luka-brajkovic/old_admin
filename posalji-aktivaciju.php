<?php

include_once 'library/config.php';

$md5_email = $f->getValue("md5_email");

$users = new Collection("_content_users");
$usersArr = $users->getCollection("WHERE md5(`e-mail`) = '$md5_email'");
$userData = $usersArr[0];

if (!empty($userData->id)) {
    $fieldMail = "e-mail";
    if ($userData->status == 1) {
            $_SESSION['infoTitle'] = "<h1>Vaš nalog je aktivan</h1>";
            $_SESSION['infoText'] = "<p>Poštovani, Vaš nalog je aktivan, želimo vam prijatno korišćenje naše online prodavnice.</p>";
            $f->redirect("/");
    } else if ($userData->status == 2) {

        if (!isset($_SESSION["poslat_link"])) {

            $body = "<p>Poštovani/a $userData->ime,</p>";
            $body .= "<p>Vaš nalog će te aktivirati klikom na <a href='" . $csDomain . "aktivacija-naloga/$md5_email'>Aktivacioni link</a></p>";
            $body .= "<p>Ukoliko ne možete klikom da aktivirate nalog, kopirajte i otvorite u vašem pretraživaču sledeći link:<br /> <a href='" . $csDomain . "aktivacija-naloga/$md5_email'>" . $csDomain . "aktivacija-naloga/$md5_email</a></p>";
            $body .= "<p>Još jednom koristimo priliku da Vam se zahvalimo na korišćenju naših usluga.</p><p>Vaš " . $csName . " tim</p>";

            $f->sendEmail($csEmail, $csName . " | Internet prodavnica", $userData->$fieldMail, "Aktivacija naloga", $body, $currentLanguage);

            $_SESSION["poslat_link"] = true;

            $_SESSION['infoTitle'] = "<h1>Poslat aktivacioni link</h1>";
            $_SESSION['infoText'] = "<p>Poštovani, na Vaš e-mail je poslat aktivacioni link. U cilju aktivacije Vašeg naloga potrebno je kliknuti na isti.</p>
            <p>Ukoliko ne možete da ga pronađete proverite JUNK SPAM i TRASH folder Vašeg e-maila.</p>
            <p>Ako ne pronađete aktivacioni link kontaktirajte nas na:<br/><br/>Telefon: <a href='tel:$csPhone'>$csPhone</a><br/> E-mail: <a href='mailto:$csEmail'>$csEmail</a>.</p>";
            $f->redirect("/");
        } else {
            $_SESSION['infoTitle'] = "<h1>Ne možete poslati aktivaciju</h1>";
            $_SESSION['infoText'] = "<p>Poštovani, na Vaš e-mail je već jednom poslat aktivacioni link.</p>
            <p>Ukoliko ne možete da ga pronađete proverite JUNK SPAM i TRASH folder Vašeg e-maila.</p>
            <p>Ako ne pronađete aktivacioni link kontaktirajte nas na:<br/><br/>Telefon: <a href='tel:$csPhone'>$csPhone</a><br/> E-mail: <a href='mailto:$csEmail'>$csEmail</a>.</p> ";
            $f->redirect("/");
        }
    }
} else {    
    $_SESSION['infoTitle'] = "<h1>Nalog sa ovim e-mailom ne postoji</h1>";
    $_SESSION['infoText'] = "<p>Poštovani, ovaj nalog ne postoji.</p>
            <p>Pokušajte da se registrujete klikom na link <a href='/registracija'>registracija</a>.</p>
            <p>Ako imate problema sa registracijom, kontaktirajte nas na:<br/><br/>Telefon: <a href='tel:$csPhone'>$csPhone</a><br/> E-mail: <a href='mailto:$csEmail'>$csEmail</a>.</p> ";
    $f->redirect("/");
}



