<?php

include_once 'library/config.php';

$md5_email = $f->getValue("md5_email");

$users = new Collection("_content_newsletter");
$usersArr = $users->getCollection("WHERE md5(`title`) = '$md5_email' AND status = 1");
$userData = $usersArr[0];

if (!empty($userData->id)) {
    $userData->status = 2;
    $userData->Save();

    $_SESSION['infoTitle'] = "<h1>Odjava sa newsletter liste</h1>";
    $_SESSION['infoText'] = "<p>Poštovani, uspešno ste se odjavili sa naše newsletter liste!</p>";
    $f->redirect("/");
} else {
    $_SESSION['infoTitle'] = "<h1>Već ste odjavljeni</h1>";
    $_SESSION['infoText'] = "<p>Poštovani, Vaš email je već bio odjavljen sa naše newsletter liste!</p>";
    $f->redirect("/");
}



