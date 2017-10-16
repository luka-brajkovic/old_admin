<?php
include_once ("library/config.php");
if (!$isLoged) {
    $f->redirect('/prijava');
}

$sessionID = session_id();
$korpa = new View("korpa", $sessionID, 'session_id');

$korpa->ime = $userData->ime;
$korpa->prezime = $userData->prezime;
$korpa->grad = $userData->grad;
$korpa->zip = $userData->postanski_broj;
$korpa->adresa = $userData->ulica_i_broj;
$korpa->telefon = $userData->mobilni_telefon;
$korpa->naselje = $userData->naselje;
$korpa->nacin_placanja = 1;
$korpa->Save();

$step1Class = "pastactive";
$step2Class = "pastactive";
$step3Class = "pastactive";
$step4Class = "curactive";
?>

<?php include_once ("head.php"); ?>
</head>
<body>
    <?php
    include_once ("header.php");
    include_once ("cart-delivery-content.php");
    include_once ("footer.php");
    ?>
</body>
</html>