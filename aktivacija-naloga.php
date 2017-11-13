<?php

include_once 'library/config.php';

$md5_email = $f->getValue("md5_email");

$users = new Collection("_content_users");
$usersArr = $users->getCollection("WHERE md5(`e-mail`) = '$md5_email'");
$userData = $usersArr[0];

if (!empty($userData->id)) {
    $userData->status = 1;
    $userData->Save();
    $_SESSION["loged_user"] = $userData->id;
    
    $_SESSION['infoTitle'] = "<h1>Dobrodošli</h1>";
    $_SESSION['infoText'] = "<p>Poštovani, želimo Vam prijatno korišćenje naše internet prodavnice.</p>";
    $f->redirect("/");
} else {
    $_SESSION['infoTitle'] = "<h1>Nalog sa ovim e-mailom ne postoji</h1>";
    $_SESSION['infoText'] = "<p>Poštovani, ovaj nalog ne postoji.</p>
            <p>Pokušajte da se registrujete klikom na link <a href='/registracija'>registracija</a>.</p>
            <p>Ako imate problema sa registracijom, kontaktirajte nas na:<br/><br/>Telefon: <a href='tel:$csPhone'>$csPhone</a><br/> E-mail: <a href='mailto:$csEmail'>$csEmail</a>.</p> ";
    $f->redirect("/");
}



