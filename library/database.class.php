<?php

class Database {

    public $insertId, $lastUsedQuery;
    public $i_user = 1;
    public $dbLink;

    function __construct() {
        $this->dbLink = mysqli_connect(DB_HOST, DB_USER, DB_PASS) or die(mysqli_error($this->dbLink));
        mysqli_query($this->dbLink, "SET NAMES utf8 COLLATE utf8_general_ci");
        mysqli_select_db($this->dbLink, DB_BASE) or die(mysqli_error($this->dbLink));
    }

    function execQuery($SQL) {

        $query = mysqli_query($this->dbLink, $SQL) or die(mysqli_error($this->dbLink));
        $this->lastUsedQuery = $query;

        if (substr($SQL, 0, 6) == "INSERT") {
            $this->insertId = mysqli_insert_id($this->dbLink);
        }

        return $query;
    }

    function getValue($field, $table, $key, $value) {
        $query = mysqli_query($this->dbLink, "SELECT `$field` FROM `$table` WHERE `$key` = '$value'") or die(mysqli_error($this->dbLink));
        $row = mysqli_fetch_array($query);

        return $row["$field"];
    }

    function getValue2($field, $table, $key, $value, $key1, $value1) {
        $query = mysqli_query($this->dbLink, "SELECT `$field` FROM `$table` WHERE `$key` = '$value' AND `$key1` = '$value1'") or die(mysqli_error($this->dbLink));
        $row = mysqli_fetch_array($query);

        return $row["$field"];
    }

    function numRows($SQL) {
        $query = $this->execQuery($SQL);

        return mysqli_num_rows($query);
    }

    function databaseDiscontect() {
        mysqli_close($this->dbLink);
    }
    
    public function __destruct() {
        $this->databaseDiscontect();
    }

}

// end of class
?>