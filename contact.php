<?php
include_once ("library/config.php");
$urlAKTIVE = "/kontakt";

$titleSEO = 'Kontakt telefon, email, adresa - ' . $csName;
$descSEO = 'Kontaktirajte nas za više informacija o svim našim proizvoda, cenama i detaljima ili bilo kakvo pitanje da imate';

$email = $name = $message = $telefon = "";
$greske = array();

if ($f->verifyFormToken('form1')) {
	
	$email = $f->getValue("email");
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		array_push($greske, "email");
	} else {
		$email = $f->test_input($email);
	}

	$name = $f->getValue("name");
	if (!preg_match('/^[\p{L}\s]+$/u', $name) || strlen($name) < 3) {
		array_push($greske, "name");
	} else {
		$name = $f->test_input($name);
	}

	$message = $f->getValue("message");
	if (strlen($message) < 4) {
		array_push($greske, "message");
	} else {
		$message = $f->test_input($message);
	}

	$phone = $f->getValue("phone");
	$phone = $f->test_input($phone);

	if (empty($greske)) {
		$body = "<strong>Ime:</strong> $name<br/><strong>Email:</strong> $email<br/><strong>Telefon:</strong> $phone<br/><br/><strong>Poruka: </strong>$message";

		$f->sendMail($email, $name, $csEmail, $csName, "Poruka sa sajta " . $csName, $body, $currentLanguage);

		$_SESSION['infoTitle'] = "<h1>Poruka je uspešno poslata</h1>";
		$_SESSION['infoText'] = "<p>Poštovani, Vaša poruka je uspešno poslata, uskoro očekujte naš odgovor!</p>";

		$name = $email = $message = $phone = "";
	} else {
		$_SESSION['infoTitle'] = "<h1>Poruka NIJE poslata</h1>";
		$_SESSION['infoText'] = "<p>Poštovani, Vaša poruka NIJE posalta, molimo Vas da proverite podatke koje ste uneli.</p>";
	}
}
include_once ("head.php");
if ($csCoordinates != "" && $csGoogleMapKey != "") {
	?>
	<script src = "http://maps.googleapis.com/maps/api/js?key="<?= $csGoogleMapKey; ?>></script>
<?php } ?>
</head>
<body>
	<?php
	include_once ("header.php");
	include_once ("includes/contact-content.php");
	include_once ("footer.php");

	if ($csCoordinates != "" && $csGoogleMapKey != "") {
		?><script type="text/javascript">
			for (var locations = [["<div class='mapDesc'><h4><?= $csName; ?></h4><p><?= $csAddress; ?><br><?= $csZip; ?>,<?= $csCity; ?><br/><a href='tel:<?= str_replace(array(" ", "-", "/"), "", $csPhone); ?>'><?= $csPhone; ?></a><br><a href='mailto:<?= $csEmail; ?>' title='Naš email'><?= $csEmail; ?></a></p></div>",<?= $csCoordinates; ?>]], icons = ["<?= $csDomain; ?>images/marker.png"], icons_length = icons.length, map = new google.maps.Map(document.getElementById("map-canvas"), {zoom:16, center:new google.maps.LatLng(<?= $csCoordinates; ?>), mapTypeId:google.maps.MapTypeId.ROADMAP, mapTypeControl:!0, streetViewControl:!0, panControl:!0, zoomControlOptions:{position:google.maps.ControlPosition.DROPDOWN_MENU}}), infowindow = new google.maps.InfoWindow({maxWidth:220}), marker, markers = new Array, iconCounter = 0, i = 0; i < locations.length; i++)marker = new google.maps.Marker({position:new google.maps.LatLng(locations[i][1], locations[i][2]), map:map, icon:icons[iconCounter]}), markers.push(marker), google.maps.event.addListener(marker, "click", function(o, n){return function(){infowindow.setContent(locations[n][0]), infowindow.open(map, o)}}(marker, i)), iconCounter++, iconCounter >= icons_length && (iconCounter = 0);
		</script>
	<?php }
	?>
</body>
</html>