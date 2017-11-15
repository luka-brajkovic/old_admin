<?php
include_once ("library/config.php");
$step1Class = "curactive";
$step2Class = $step3Class = $step4Class = "";

$titleSEO = $csName . " - pregled proizvoda u korpi";
$descSEO = "Pregledajte i uredite proizvode pre same kupovine.";

if ($f->verifyFormToken('kupon')) {
	$kupon = $f->getValue("kupon");
	$kupon = $f->test_input($kupon);
	$kuponData = mysql_query("SELECT discount, title, url FROM _content_coupons WHERE status = 1 AND title = '$kupon' LIMIT 1") or die(mysql_error());
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
	include_once ("includes/cart-content.php");
	include_once ("footer.php");
	?>
</body>
</html>