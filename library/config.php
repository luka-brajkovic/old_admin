<?php

//Krenula sesija
session_start();

include ("mysql.php");

// Turn off all error reporting
error_reporting(E_ERROR);
ini_set('display_errors', 1);

//Putanja za aplikaciju
define("APP_PATH", "library/");
define("MODULE_PATH", "../library/");

//Podesavanja za FB aplikacije
define('YOUR_APP_ID', ' ');
define('YOUR_APP_SECRET', ' ');

//Podesavanja za slike i za fajlove, ekstenzije
define("PIC_EXT", "jpg,gif,png,jpeg");
define("GALLERY_EXT", "zip");
define("FILE_EXT", "zip,rar,pdf,doc,docx,xls,xlsx,jpg,gif,png,jpg");
// define("DOMAIN" , '');

define("FILE_MANAGER_PATH", "/home/free1/public_html/dev/uploads");

//Autolodujemo sve klase koje nadjemo u library folderu
function __autoload($class_name) {
	if (is_file(APP_PATH . strtolower($class_name) . '.class.php')) {
		include APP_PATH . strtolower($class_name) . '.class.php';
	} else {
		include MODULE_PATH . strtolower($class_name) . '.class.php';
	}
}

//Kreiramo osnovne klase
$db = new Database();
$conn = $db->dbLink;

$f = new Functions();
$site = new Site();

//utvrdjujemo koji je lang code, tj. na kom smo jezuku
if (isset($lang_code)) {
	$lang_code = $f->getValue("lang_code");
} else {
	$lang_code = "";
}

//Ako nema jezika onda cemo da iscitamo defaultni
if ($lang_code == "") {
	$language = new View("languages", 1, "is_default");
	$currentLanguage = $language->id;
	$lang_code = $language->code;
} else {
	//Ako je setovan onda pravimo objekat sa tim jezikom
	$language = new View("languages", $lang_code, "code");
	$currentLanguage = $language->id;
}

//Iz lang fajla citamo sve promenljive i pakujemo u niz. Sve se nalazi u nizu $lng
$langfile = simplexml_load_file("library/languages/" . $lang_code . ".xml");
$lng = array();
foreach ($langfile as $constant) {
	$constkey = $constant->const;
	$lng["$constkey"] = "$constant->value";
}

$REQUEST = $_SERVER['REQUEST_URI'];

//Citamo iz settings tabele sve za ovaj jezik
$settings = new View("settings", $currentLanguage, "lang_id");

//Vadimo sva podesavanja za jezik
$csTitle = $settings->site_title;
$csFooter = $settings->site_footer;
$csDesc = $settings->site_description;
$csEmail = $settings->site_email;
$csDomain = $settings->site_host;
$csPhone = $settings->site_phone;
$csPhone2 = $settings->site_phone_2;
$csFacebook = $settings->site_facebook;
$csFacebookAppID = $settings->site_facebook_app_id;
$csAccount = $settings->site_account;
$csName = $settings->site_firm;
$csTwitter = $settings->site_twitter;
$csTwitterUsername = $settings->site_twitter_username;
$csLinkedIn = $settings->site_linkedin;
$csInstagram = $settings->site_instagram;
$csPinterest = $settings->site_pinterest;
$csYouTube = $settings->site_youtube;
$csVimeo = $settings->site_vimeo;
$csAddress = $settings->site_address;
$csZip = $settings->site_zip;
$csCity = $settings->site_city;
$csCountry = $settings->site_country;
$csGooglePlus = $settings->site_google_plus;
$csCoordinates = $settings->site_koordinate;
$csGoogleMap = $settings->site_embed;
$csGAnalytic = $settings->site_analytic;
$csVerification = $settings->site_verification;
$csMailServer = $settings->site_outgoing_server;
$csMailPort = $settings->site_smtp_port;
$csMailUser = $settings->site_username;
$csMailPassword = $settings->site_password;
$csWorkingTime1 = $settings->site_working_time_1;
$csWorkingTime2 = $settings->site_working_time_2;
$csWorkingTime3 = $settings->site_working_time_3;
$csGoogleMapKey = $settings->site_api_key;
$csShop = $settings->online_shop;

if ($csShop == 1 && isset($_SESSION["loged_user"])) {
	$isLoged = true;
	$userData = new View("_content_users", $_SESSION["loged_user"]);
} else {
	$isLoged = false;
}

/*
  $depthQuery = mysqli_query($conn,"SELECT MAX(level) as max_level FROM categories WHERE status = 1 AND lang = $currentLanguage");
  $depth = mysqli_fetch_array($depthQuery);
  $depth = $depth[max_level];
 */

$nizZelja = array();
if ($csShop == 1) {
	if ($isLoged) {
		$idUsera = $userData->resource_id;
		$list = mysqli_query($conn, "SELECT * FROM list_zelja WHERE user_rid = $idUsera") or die(mysqli_error($conn));
		while ($row = mysqli_fetch_object($list)) {
			array_push($nizZelja, $row->product_rid);
		}
	}
}

// Citanje dimenzija slika
$dimensions = new Collection("content_type_dimensions");
$dimsArr = $dimensions->getCollection("WHERE content_type_id = 72 AND url NOT LIKE 'g%' ORDER BY LENGTH(url),url");
$dimData = $dimsArr[0];
$dimUrlLit = $dimData->url;

$dimDatasecund = $dimsArr[2];
$dimUrlLitSecund = $dimDatasecund->url;

$dimDataShare = $dimsArr[3];
$dimUrlLitShare = $dimDataShare->url;

$dimDataBigs = $dimsArr[4];
$dimUrlLitBigs = $dimDataBigs->url;

$dimDataLittle = $dimsArr[1];
$dimDataLitList = $dimDataLittle->url;

$singleCategories = $fancyboxJS = 0;

$naslovna = false;
$urlAKTIVE = $urlJe = $catMasterDataURL = $catMiddleDataURL = $cat_master_url = $lastletter = $tagCurrent = $ogType = $htmlTagAddOG = "";

// hvatanje extenzije logoa
if (is_file("images/logo.png")) {
	$logoEx = "png";
} elseif (is_file("images/logo.svg")) {
	$logoEx = "svg";
} elseif (is_file("images/logo.jpg")) {
	$logoEx = "jpg";
}