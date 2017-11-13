<?php

/** @version $Id: .htusers.php 135 2009-01-27 21:57:15Z ryu_ms $ */
/** ensure this file is being included by a parent file */
if (!defined('_JEXEC') && !defined('_VALID_MOS'))
    die('Restricted access');


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

function database_connect() {
    $link = mysqli_connect(DB_HOST, DB_USER, DB_PASS) or die("MySQL connection error!");
    $baza = mysqli_select_db(DB_BASE, $link) or die('MySQL connection error!');
}

database_connect();

$q_admins = mysqli_query($conn,"SELECT * FROM administrators ORDER BY id");

$GLOBALS["users"] = array();

while ($data = mysqli_fetch_array($q_admins, MYSQLI_ASSOC)) {
    $path = "../../uploads";
    if ($data["id"] == 1)
        $path = "../../";
    array_push($GLOBALS["users"], array("$data[username]", "$data[password]",
        empty($_SERVER['DOCUMENT_ROOT']) ?
                $path :
                $path,
        "http://localhost", 1, "", 7, 1));
}
?>
