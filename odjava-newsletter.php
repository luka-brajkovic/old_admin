<?php

include_once 'library/config.php';

$md5_email = $f->getValue("md5_email");

$users = new Collection("_content_newsletter");
$usersArr = $users->getCollection("WHERE md5(`title`) = '$md5_email' AND status = 1");
$userData = $usersArr[0];

if (!empty($userData->id)) {
    $userData->status = 2;
    $userData->Save();
    $f->redirect("/poruka/uspesna-odjava");
} else {
    $f->redirect("/poruka/odjavljen");
}



