<?php

require("../library/config.php");

//Here we have all values from POST and GET
$values = $f->getRequestValues();
$action = $values["action"];

switch ($action) {

    case "add_field":

        //Adding new field in categories_fields
        $ctField = new View("fields");
        $ctField->extend($_POST);
        $ctField->column_name = $ctField->createColumnName($ctField->title);
        $ctField->Save();

        //Creating column definition  
        switch ($ctField->field_type) {
            case "text":
            case "radio":
            case "select":
            case "select_table":
            case "checkbox":
            case "colorpicker":
                $sqlContentTypeDefinition = " VARCHAR ( 255 ) NULL";
                break;

            case "textarea":
            case "wysiwyg":
            case "file":
                $sqlContentTypeDefinition = " LONGTEXT NULL";
                if (!is_dir("../../uploads/uploaded_files/$ctField->table_name/")) {
                    mkdir("../../uploads/uploaded_files/$ctField->table_name/");
                }
                break;
            case "image":
                $sqlContentTypeDefinition = " LONGTEXT NULL";
                if (!is_dir("../../uploads/uploaded_pictures/$ctField->table_name/")) {
                    mkdir("../../uploads/uploaded_pictures/$ctField->table_name/");
                }
                break;

            case "datepicker":
                $sqlContentTypeDefinition = " DATETIME NULL";
                break;
        }

        //Creating new column in table
        $db->execQuery("ALTER TABLE `$ctField->table_name` ADD `$ctField->column_name` $sqlContentTypeDefinition");

        //Redirecting to list of fields
        $f->redirect("index.php?table_name=$ctField->table_name&infomsg=success_create_field");

        break;

    case "edit_field":

        //Updating content type fields table
        $id = $f->getValue("id");
        $ctField = new View("fields", $id);
        $oldColumnName = $ctField->column_name;
        $ctField->extend($_POST);
        $ctField->column_name = $ctField->createColumnName($ctField->title);
        $ctField->Save();

        //Creating column definition  
        switch ($ctField->field_type) {
            case "text":
            case "radio":
            case "select":
            case "select_table":
            case "checkbox":
            case "colorpicker":
                $sqlContentTypeDefinition = " VARCHAR ( 255 ) NULL";
                break;

            case "textarea":
            case "wysiwyg":
            case "file":
            case "image":
                $sqlContentTypeDefinition = " LONGTEXT NULL";
                break;

            case "datepicker":
                $sqlContentTypeDefinition = " DATETIME NULL";
                break;
        }

        //Updating table column
        $db->execQuery("ALTER TABLE `$ctField->table_name` CHANGE `$oldColumnName` `$ctField->column_name` $sqlContentTypeDefinition");

        //Redirecting to list of fields
        $f->redirect("index.php?table_name=$ctField->table_name&infomsg=success_edit_field");

        break;

    case "delete_field":

        //Removing from content type fields
        $id = $f->getValue("id");
        $ctField = new View("fields", $id);

        //Get col name
        $columnName = $ctField->column_name;
        $table_name = $ctField->table_name;

        //If field type is image deleteing all images for this field
        if ($ctField->field_type == "image") {
            $tableCollection = new Collection("categories");
            $imageCollection = $tableCollection->getCollection();
            foreach ($imageCollection as $key => $content) {
                if ($content->$columnName != "") {
                    unlink("../../uploads/uploaded_pictures/$table_name/$content->$columnName");
                }
            }
        }

        //If field type is file deleteing all images for this field
        if ($ctField->field_type == "file") {
            $tableCollection = new Collection("categories");
            $fileCollection = $tableCollection->getCollection();
            foreach ($fileCollection as $key => $content) {
                if ($content->$columnName != "") {
                    unlink("../../uploads/uploaded_files/$table_name/$content->$columnName");
                }
            }
        }

        //Removing
        $ctField->Remove();

        //Removing column from table
        $db->execQuery("ALTER TABLE `$table_name` DROP `$columnName`");

        //Redirecting to list of fields
        $f->redirect("index.php?table_name=$table_name&infomsg=success_delete_field");

        break;
}
?>