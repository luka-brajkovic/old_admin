<?php

//Krenula sesija
session_start();

include ("mysql.php");

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
$f = new Functions();
$site = new Site();

//utvrdjujemo koji je lang code, tj. na kom smo jezuku
$lang_code = $f->getValue("lang_code");

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

$HOST = $_SERVER['HTTP_HOST'];
$REQUEST = $_SERVER['REQUEST_URI'];
$SERVER_NAME = $_SERVER['SERVER_NAME'];

//Citamo iz settings tabele sve za ovaj jezik
$settings = new View("settings", $currentLanguage, "lang_id");

//Vadimo sva podesavanja za jezik
$configSiteTitle = $settings->site_title;
$configSiteFooter = $settings->site_footer;
$configSiteKeywords = $settings->site_keywords;
$configSiteDescription = $settings->site_description;
$configSiteEmail = $settings->site_email;
$configSiteDomain = "http://" . $SERVER_NAME . "/";
$configSitePhone = $settings->site_phone;
$configSiteFacebook = $settings->site_facebook;
$configSiteAccount = $settings->site_account;
$configSiteFirm = $settings->site_firm;
$configSiteTwitter = $settings->site_twitter;
$configSiteLinkedIn = $settings->site_linkedin;
$configSiteInstagram = $settings->site_instagram;
$configSitePinterest = $settings->site_pinterest;
$configSiteYouTube = $settings->site_youtube;
$configSiteVimeo = $settings->site_vimeo;
$configSiteAddress = $settings->site_address;
$configSiteZip = $settings->site_zip;
$configSiteCity = $settings->site_city;
$configSiteCountry = $settings->site_country;
$configSiteGooglePlus = $settings->site_google_plus;
$configSiteKoordinate = $settings->site_koordinate;
$configSiteGoogleMap = $settings->site_embed;
$configSiteAnalyric = $settings->site_analytic;
$configSiteVerification = $settings->site_verification;
$configSiteShop = $settings->online_shop;

if ($configSiteShop == 1 && isset($_SESSION["loged_user"])) {
    $isLoged = true;
    $userData = new View("_content_korisnici", $_SESSION["loged_user"]);
} else {
    $isLoged = false;
}

/*
  $depthQuery = mysql_query("SELECT MAX(level) as max_level FROM categories WHERE status = 1 AND lang = $currentLanguage");
  $depth = mysql_fetch_array($depthQuery);
  $depth = $depth[max_level];
 */

$nizZelja = array();
if ($configSiteShop == 1) {
    if ($isLoged) {
        $idUsera = $userData->resource_id;
        $list = mysql_query("SELECT * FROM list_zelja WHERE user_rid = $idUsera") or die(mysql_error());
        while ($row = mysql_fetch_object($list)) {
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
