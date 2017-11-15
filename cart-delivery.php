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

if ($f->verifyFormToken('kupon')) {
	$kupon = $f->getValue("kupon");
	$kupon = $f->test_input($kupon);
	$kuponData = mysql_query("SELECT url, title FROM _content_coupons WHERE status = 1 AND title = '$kupon' LIMIT 1") or die(mysql_error());
	$kuponData = mysql_fetch_object($kuponData);

	if ($kuponData->url != "") {
		$_SESSION['kupon'] = $kuponData->url;
	}
}

include_once ("head.php");
?>
</head>
<body>
	<?php
	include_once ("header.php");
	include_once ("includes/cart-delivery-content.php");
	include_once ("footer.php");
	?>
</body>
</html>