<?php

session_start();

$role = $_SESSION['admin_info']['role'];

$ifmysql = 0;
$filename = '../library/mysql.php';
if (file_exists($filename)) {
    include ("$filename");
    $ifmysql = 1;
}
if ($ifmysql != 1) {
    $filename = '../../library/mysql.php';
    if (file_exists($filename)) {
        include ("$filename");
        $ifmysql = 1;
    }
}
if ($ifmysql != 1) {
    $filename = '../../../library/mysql.php';
    if (file_exists($filename)) {
        include ("$filename");
    }
}

define("APP_PATH", "library/");
define("MODULE_PATH", "../library/");

define("PIC_EXT", "jpg,gif,png,jpeg");
define("GALLERY_EXT", "zip");
define("FILE_EXT", "zip,rar,pdf,doc,docx,xls,xlsx,jpg,gif,png,jpg");

function __autoload($class_name) {
    if (is_file(APP_PATH . strtolower($class_name) . '.class.php')) {
        include APP_PATH . strtolower($class_name) . '.class.php';
    } else {
        include MODULE_PATH . strtolower($class_name) . '.class.php';
    }
}

$db = new Database();
$f = new Functions();

$currentLanguage = $_SESSION['admin_lang'];
if ($currentLanguage == "") {
    $queryLang = $db->execQuery("SELECT * FROM languages WHERE is_default='1'");
    $dataLang = mysql_fetch_array($queryLang, MYSQL_ASSOC);
    $_SESSION['admin_lang'] = $dataLang['id'];
    $currentLanguage = $_SESSION['admin_lang'];
}
//Citamo iz settings tabele sve za ovaj jezik
$settings = new View("settings", 1, "lang_id");

//Vadimo sva podesavanja za jezik
$configSiteTitle = $settings->site_title;
$configSiteFooter = $settings->site_footer;
$configSiteKeywords = $settings->site_keywords;
$configSiteDescription = $settings->site_description;
$configSiteEmail = $settings->site_email;
$configSiteDomain = "http://".$_SERVER['SERVER_NAME']."/";
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
$configSiteAnalyric = $settings->site_analytic;
$configSiteVerification = $settings->site_verification;
$configSiteShop = $settings->online_shop;


define("ADMIN_TITLE", "Admin Panel - " . $configSiteFirm);
define("AUTHOR_TITLE", "www.webdizajnsrbija.rs");
define("AUTHOR_WEB", "www.webdizajnsrbija.rs");
?>