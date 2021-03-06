<?php

class View extends Functions {

    public $db, $f;
    private $table, $key_value, $key_field;

    function __construct($table, $key_value = false, $key_field = "id") {
        $this->db = Database::__construct();
        $this->f = Functions::__construct();

        $this->table = $table;
        $this->key_value = $key_value;
        $this->key_field = $key_field;

        $this->setFields();

        if ($key_value) {

            $this->getData();
        }
    }

    function createTableName($title) {
        $tableName = strtolower($title);
        $tableName = str_replace(" ", "_", $tableName);
        $tableName = "_content_" . $tableName;
        return $tableName;
    }

    function createColumnName($title) {
        $columnName = strtolower($title);
        $columnName = str_replace(" ", "_", $columnName);
        return $columnName;
    }

    function __destruct() {
        unset($this->db, $this->f);
    }

    function Save() {

        $fields = $this->getFields();

        $SQL = "";

        // if there is key value, then update this row in a table, else create a new one
        if ($this->key_value !== false) {

            $SQL = "UPDATE `" . $this->table . "` SET ";
            foreach ($fields as $key => $field_info) {
                if ($field_info['Field'] != $this->key_field) {
                    $SQL .= "`" . $field_info['Field'] . "` = '" . $this->{$field_info['Field']} . "', ";
                }
            }

            $SQL = substr($SQL, 0, strlen($SQL) - 2);
            $SQL .= " WHERE `" . $this->key_field . "` = '" . $this->key_value . "'";
        } else { // here we create a new one
            $SQL = "INSERT INTO `" . $this->table . "` (";
            foreach ($fields as $key => $field_info) {
                if ($field_info['Field'] != $this->key_field) {
                    $SQL .= "`" . $field_info['Field'] . "`, ";
                }
            }
            $SQL = substr($SQL, 0, strlen($SQL) - 2);
            $SQL .= ") VALUES (";
            foreach ($fields as $key => $field_info) {
                if ($field_info['Field'] != $this->key_field) {
                    $SQL .= "'" . $this->{$field_info['Field']} . "', ";
                }
            }
            $SQL = substr($SQL, 0, strlen($SQL) - 2);
            $SQL .= ")";
        }

        Database::execQuery($SQL);
        if ($this->key_value === false)
            $this->id = $this->insertId;
    }

    function Remove() {
        if ($this->key_value !== false) {
            Database::execQuery("DELETE FROM `" . $this->table . "` WHERE `" . $this->key_field . "` = '" . $this->key_value . "'");
        }
    }

    function linkWith($foreign_table, $foreign) {


        $SQL = "SELECT * FROM ";
        $SQL .= "`" . $foreign_table . "` WHERE ";
        foreach ($foreign as $rel_id => $for_id) {

            $replacment = (isset($this->{$for_id})) ? $this->{$for_id} : $for_id;

            $SQL .= "`" . $rel_id . "` = '" . $replacment . "' AND ";
        }
        $SQL = substr($SQL, 0, strlen($SQL) - 4);

        $returnObject = array();

        $query = Database::execQuery($SQL);
        while ($data = mysqli_fetch_array($query, MYSQLI_ASSOC)) {

            $returnObject[] = new View($foreign_table, $data['id']);
        }

        return $returnObject;
    }

    function linkWithCustom($foreign_table, $SQL) {
        $returnObject = array();
        $query = Database::execQuery($SQL);
        while ($data = mysqli_fetch_array($query, MYSQLI_ASSOC)) {

            $returnObject[] = new View($foreign_table, $data['id']);
        }

        return $returnObject;
    }

    function extend($input) {
        foreach ($input as $field => $value) {

            if (isset($this->{$field})) {
                $this->{$field} = $this->stringCleaner($value);
            }
        }
    }

    /**
     * Checks if the input value is valid
     *
     * @param string $value
     * @param string $filter bool, email, float, int, ip, regex, url
     * @param string $input_type
     * @return input value if valid, false otherwise
     */
    function checkInput($value, $filter, $input_type = INPUT_POST) {

        $filter_id = "";
        switch ($filter) {
            case "bool": $filter_id = FILTER_VALIDATE_BOOLEAN;
                break;
            case "email": $filter_id = FILTER_VALIDATE_EMAIL;
                break;
            case "float": $filter_id = FILTER_VALIDATE_FLOAT;
                break;
            case "int": $filter_id = FILTER_VALIDATE_INT;
                break;
            case "ip": $filter_id = FILTER_VALIDATE_IP;
                break;
            case "regex": $filter_id = FILTER_VALIDATE_REGEXP;
                break;
            case "url": $filter_id = FILTER_VALIDATE_URL;
                break;
        }

        return filter_input($input_type, $value, $filter_id);
    }

    function dateNow() {
        return date("Y-m-d H:i:s");
    }

    private function setFields() {
        $q = Database::execQuery("SHOW COLUMNS FROM `" . $this->table . "`");

        while ($data = mysqli_fetch_array($q, MYSQLI_ASSOC)) {
            $this->{$data['Field']} = "";
        }
    }

    private function getFields() {

        $q = Database::execQuery("SHOW COLUMNS FROM `" . $this->table . "`");

        $fields = array();
        while ($data = mysqli_fetch_array($q, MYSQLI_ASSOC)) {
            $fields[] = $data;
        }

        return $fields;
    }

    private function getData() {
        $query = Database::execQuery("SELECT * FROM `" . $this->table . "` WHERE `" . $this->key_field . "` = '" . $this->key_value . "'");
        if (mysqli_num_rows($query) == 1) {
            $row = mysqli_fetch_array($query, MYSQLI_ASSOC);
            foreach ($row as $key => $value) {
                $this->{$key} = $value;
            }

            /*
              $foreignKeysQuery = Database::execQuery("SELECT * FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = '".DB_BASE."' AND CONSTRAINT_NAME != 'PRIMARY' AND TABLE_NAME='".$this->table."'");
              $num = mysqli_num_rows($foreignKeysQuery);
              if($num > 0) {
              while($dataForeign = mysqli_fetch_array($foreignKeysQuery)) {
              $column = $dataForeign["COLUMN_NAME"];
              $this->{$dataForeign["REFERENCED_TABLE_NAME"]._object} = new View($dataForeign["REFERENCED_TABLE_NAME"], $row[$column],$dataForeign["REFERENCED_COLUMN_NAME"]);
              }
              } */
        }
    }

    function debug($input) {
        echo "<pre>";
        var_dump($input);
        echo "</pre>";
    }

}
