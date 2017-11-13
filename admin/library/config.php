<?php

session_start();

// Turn off all error reporting
error_reporting(E_ERROR);
ini_set('display_errors',1);

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
$conn = $db->dbLink;

$f = new Functions();

$currentLanguage = $_SESSION['admin_lang'];
if ($currentLanguage == "") {
    $queryLang = $db->execQuery("SELECT * FROM languages WHERE is_default='1'");
    $dataLang = mysqli_fetch_array($queryLang, MYSQLI_ASSOC);
    $_SESSION['admin_lang'] = $dataLang['id'];
    $currentLanguage = $_SESSION['admin_lang'];
}
//Citamo iz settings tabele sve za ovaj jezik
$settings = new View("settings", $currentLanguage, "lang_id");

//Vadimo sva podesavanja za jezik
$csTitle = $settings->site_title;
$csFooter = $settings->site_footer;
$csDesc = $settings->site_description;
$csEmail = $settings->site_email;
$csDomain = "http://".$_SERVER['SERVER_NAME']."/";
$csPhone = $settings->site_phone;
$csPhone2 = $settings->site_phone_2;
$csFacebook = $settings->site_facebook;
$csAccount = $settings->site_account;
$csName = $settings->site_firm;
$csTwitter = $settings->site_twitter;
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
$csGAnalytic = $settings->site_analytic;
$csVerification = $settings->site_verification;
$csMailServer = $settings->site_outgoing_server;
$csMailPort = $settings->site_smtp_port;
$csMailUser = $settings->site_username ;
$csMailPassword = $settings->site_password;
$csWorkTime1 = $settings->site_working_time_1;
$csWorkTime2 = $settings->site_working_time_2;
$csWorkTime3 = $settings->site_working_time_3;
$csShop = $settings->online_shop;


define("ADMIN_TITLE", "Admin Panel - " . $csName);
define("AUTHOR_TITLE", "www.webdizajnsrbija.rs");
define("AUTHOR_WEB", "www.webdizajnsrbija.rs");
?>