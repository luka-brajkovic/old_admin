<?php

class Database {

    public $insertId, $lastUsedQuery;
    public $i_user = 1;
    public $dbLink;

    function __construct($db_host = "", $db_user = "", $db_pass = "", $db_name = "") {
        if ($db_host == "") {
            $db_host = DB_HOST;
        }

        if ($db_user == "") {
            $db_user = DB_USER;
        }

        if ($db_pass == "") {
            $db_pass = DB_PASS;
        }

        if ($db_name == "") {
            $db_name = DB_BASE;
        }
        $this->dbLink = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die(mysql_error());
        mysql_query("SET NAMES utf8 COLLATE utf8_general_ci", $this->dbLink);
        mysql_select_db(DB_BASE, $this->dbLink) or die(mysql_error());
    }

    function execQuery($SQL) {

        $query = mysql_query($SQL, $this->dbLink) or die(mysql_error($this->dbLink));
        $this->lastUsedQuery = $query;

        if (substr($SQL, 0, 6) == "INSERT") {
            $this->insertId = mysql_insert_id($this->dbLink);
        }

        return $query;
    }

    function getValue($field, $table, $key, $value) {
        $query = mysql_query("SELECT `$field` FROM `$table` WHERE `$key` = '$value'") or die(mysql_error($this->dbLink));
        $row = mysql_fetch_array($query);

        return $row["$field"];
    }

    function numRows($SQL) {
        $query = $this->execQuery($SQL);

        return mysql_num_rows($query);
    }

    function databaseDiscontect() {
        mysql_close($this->dbLink);
    }

}

// end of class
?>