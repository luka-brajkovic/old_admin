<?php

include_once 'library/config.php';

$md5_email = $f->getValue("md5_email");

$users = new Collection("_content_korisnici");
$usersArr = $users->getCollection("WHERE md5(`e-mail`) = '$md5_email'");
$userData = $usersArr[0];

if (!empty($userData->id)) {
    $userData->status = 1;
    $userData->Save();
    $_SESSION["loged_user"] = $userData->id;
    $f->redirect("/poruka/dobrodosli");
} else {
    $f->redirect("/poruka/ne-postoji-nalog");
}



