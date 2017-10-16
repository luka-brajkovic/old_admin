<?php

require("../library/config.php");



//Here we have all values from POST and GET
$values = $f->getRequestValues();



$action = $f->getValue("action");



switch ($action) {

    //Creating new content type
    case "add_ct":

        //Creating row in content_types table
        $contentType = new View("content_types");

        //Expanding with posted fields
        $contentType->extend($_POST);
        $contentType->table_name = $contentType->createTableName($contentType->title);
        $contentType->url = $f->generateUrlFromText($contentType->title);

        //Saving new content type
        $contentType->Save();

        //Creating table in database
        $queryLine = "CREATE TABLE `" . $contentType->table_name . "` (
                        `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                        `resource_id` INT NOT NULL,
                        `title` varchar(255) NOT NULL,
                        `url` varchar(255) NOT NULL,
                        `ordering` INT NOT NULL,
                        `num_views` INT NOT NULL,
                        `status` INT NOT NULL,
                        `system_date` DATETIME NOT NULL,
                        `lang` varchar(255) NOT NULL";

        $queryLine .= ",INDEX ( `resource_id` , `lang` )
                         ) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;";

        //Exec query for creating table
        $db->execQuery($queryLine);

        //Adding relations for created table
        $relations = "ALTER TABLE `$contentType->table_name` ADD FOREIGN KEY ( `resource_id` ) REFERENCES  `resources` (`id`) ON DELETE CASCADE;";

        //Exec query for relations
        $db->execQuery($relations);

        //Creating sample category for new content type
        if ($contentType->category_type != 0) {
            $resource = new View("resources");
            $resource->table_name = "categories";
            $resource->Save();

            $langCollection = new Collection("languages");
            $languages = $langCollection->getCollection();

            foreach ($languages as $lang) {
                $category = new View("categories");
                $category->resource_id = $resource->id;
                $category->title = "Sample " . $contentType->title;
                $category->url = "sample-" . $contentType->title;
                $category->parent_id = 0;
                $category->content_type_id = $contentType->id;
                $category->lang = $lang->id;
                $category->Save();
            }
        }

        //Creating image upload folder
        if (!is_dir("../../uploads/uploaded_pictures/$contentType->table_name")) {
            mkdir("../../uploads/uploaded_pictures/$contentType->table_name");
        }

        //Creating file upload folder
        if (!is_dir("../../uploads/uploaded_files/$contentType->table_name")) {
            mkdir("../../uploads/uploaded_files/$contentType->table_name");
        }

        //Redirecting to index page of content types
        $f->redirect("index.php?infomsg=success_create_ct");

        break;

    case "edit_ct":

        //Fetching content type id
        $id = $f->getValue("id");

        //Creation content type view
        $contentType = new View("content_types", $id);

        //Getting old table name
        $oldTableName = $contentType->table_name;

        //Updating fields
        $contentType->extend($_POST);
        $contentType->table_name = $contentType->createTableName($contentType->title);
        $contentType->url = $f->generateUrlFromText($contentType->title);
        $contentType->Save();

        //Renaming table name in database
        if ($oldTableName != $contentType->table_name) {
            $db->execQuery("RENAME TABLE $oldTableName TO `$contentType->table_name ;");

            //Renaming image upload folder
            if (is_dir("../../uploads/uploaded_pictures/$oldTableName")) {
                rename("../../uploads/uploaded_pictures/$oldTableName", "../../uploads/uploaded_pictures/$contentType->table_name");
            }

            //Renaming file upload folder
            if (is_dir("../../uploads/uploaded_files/$oldTableName")) {
                rename("../../uploads/uploaded_files/$oldTableName", "../../uploads/uploaded_files/$contentType->table_name");
            }
        }

        //Redirecting to index page of content types
        $f->redirect("index.php?infomsg=success_edit_ct");

        break;

    case "delete_ct":

        $id = $f->getValue("id");



        //Creating view for content type
        $contentType = new View("content_types", $id);
        $tableName = $contentType->table_name;

        //Removing from content type
        $contentType->Remove();

        //Deleting from resources 
        $db->execQuery("DELETE FROM resources WHERE `table_name` = '$tableName'");

        //Deleting content type fields
        $db->execQuery("DELETE FROM content_type_fields WHERE content_type_id = '$id'");

        //Droping table
        $db->execQuerY("DROP TABLE `$tableName`");

        //Deleting uploaded pictures folder
        $f->recursive_remove_directory("../../uploads/uploaded_pictures/$tableName");

        //Deleting uploaded files folder
        $f->recursive_remove_directory("../../uploads/uploaded_files/$tableName");

        //Redirecting index
        $f->redirect("index.php?infomsg=success_delete_ct");

        break;

    case "add_ct_field":

        //Adding new field in content_type_fields
        $ctField = new View("content_type_fields");
        $ctField->extend($_POST);
        $ctField->column_name = $ctField->createColumnName($ctField->title);
        $ctField->Save();

        //Getting tablename
        $tableName = $db->getValue("table_name", "content_types", "id", $ctField->content_type_id);

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
            case "gallery":
                $sqlContentTypeDefinition = " LONGTEXT NULL";
                break;

            case "datepicker":
                $sqlContentTypeDefinition = " DATETIME NULL";
                break;
        }

        //Creating new column in table
        $db->execQuery("ALTER TABLE `$tableName` ADD `$ctField->column_name` $sqlContentTypeDefinition");

        //Redirecting to list of fields
        $f->redirect("fields.php?id=$ctField->content_type_id&infomsg=success_create_field");

        break;

    case "add_ct_cat_field":

        //Adding new field in categories_fields
        $ctField = new View("categories_fields");
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

        //Creating new column in table
        $db->execQuery("ALTER TABLE `categories` ADD `$ctField->column_name` $sqlContentTypeDefinition");

        //Redirecting to list of fields
        $f->redirect("category_fields.php?cid=$ctField->content_type_id&infomsg=success_create_field");

        break;

    case "edit_ct_field":

        //Updating content type fields table
        $id = $f->getValue("id");
        $ctField = new View("content_type_fields", $id);
        $oldColumnName = $ctField->column_name;
        $ctField->extend($_POST);
        $ctField->column_name = $ctField->createColumnName($ctField->title);
        $ctField->Save();

        //Getting tablename
        $tableName = $db->getValue("table_name", "content_types", "id", $ctField->content_type_id);

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
        $db->execQuery("ALTER TABLE `$tableName` CHANGE `$oldColumnName` `$ctField->column_name` $sqlContentTypeDefinition");

        //Redirecting to list of fields
        $f->redirect("fields.php?id=$ctField->content_type_id&infomsg=success_edit_field");

        break;

    case "edit_ct_cat_field":

        //Updating content type fields table
        $id = $f->getValue("id");
        $ctField = new View("categories_fields", $id);
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
        $db->execQuery("ALTER TABLE `categories` CHANGE `$oldColumnName` `$ctField->column_name` $sqlContentTypeDefinition");

        //Redirecting to list of fields
        $f->redirect("category_fields.php?cid=$ctField->content_type_id&infomsg=success_edit_field");

        break;

    case "delete_ct_field":

        //Removing from content type fields
        $id = $f->getValue("id");



        $ctField = new View("content_type_fields", $id);

        //Get col name and table name
        $columnName = $ctField->column_name;
        $tableName = $db->getValue("table_name", "content_types", "id", $ctField->content_type_id);
        $ctId = $ctField->content_type_id;

        echo $id;
        //If field type is file deleteing all images for this field
        if ($ctField->field_type == "file") {
            $tableCollection = new Collection($tableName);
            $fileCollection = $tableCollection->getCollection();
            foreach ($fileCollection as $key => $content) {
                if ($content->$columnName != "") {
                    unlink("../../uploads/uploaded_files/$tableName/$content->$columnName");
                }
            }
        }

        //Removing
        $ctField->Remove();

        //Removing column from table
        $db->execQuery("ALTER TABLE `$tableName` DROP `$columnName`");

        //Redirecting to list of fields
        $f->redirect("fields.php?id=$ctId&infomsg=success_delete_field");

        break;

    case "delete_ct_cat_field":

        //Removing from content type fields
        $id = $f->getValue("id");
        $ctField = new View("categories_fields", $id);

        //Get col name
        $columnName = $ctField->column_name;

        //If field type is image deleteing all images for this field
        if ($ctField->field_type == "image") {
            $tableCollection = new Collection("categories");
            $imageCollection = $tableCollection->getCollection();
            foreach ($imageCollection as $key => $content) {
                if ($content->$columnName != "") {
                    unlink("../../uploads/uploaded_pictures/categories/$content->$columnName");
                }
            }
        }

        //If field type is file deleteing all images for this field
        if ($ctField->field_type == "file") {
            $tableCollection = new Collection("categories");
            $fileCollection = $tableCollection->getCollection();
            foreach ($fileCollection as $key => $content) {
                if ($content->$columnName != "") {
                    unlink("../../uploads/uploaded_files/categories/$content->$columnName");
                }
            }
        }

        //Removing
        $ctField->Remove();

        //Removing column from table
        $db->execQuery("ALTER TABLE `categories` DROP `$columnName`");

        //Redirecting to list of fields
        $f->redirect("category_fields.php?cid=$ctId&infomsg=success_delete_field");

        break;

    case "add_ct_dimension":

        //Creating new dimension
        $ctDimension = new View("content_type_dimensions");
        $ctDimension->extend($_POST);
        $ctDimension->url = $f->generateUrlFromText($ctDimension->title);
        $ctDimension->Save();

        //Table name of content type
        $tableName = $db->getValue("table_name", "content_types", "id", $ctDimension->content_type_id);

        //Creating image upload folder for dimension
        if (!is_dir("../../uploads/uploaded_pictures/$tableName/$ctDimension->url")) {
            mkdir("../../uploads/uploaded_pictures/$tableName/$ctDimension->url");
        }

        //Redirecting to list of dimensions
        $f->redirect("dimensions.php?id=$ctDimension->content_type_id&infomsg=success_create_dimension");

        break;

    case "edit_ct_dimension":

        //Geting some values
        $id = $f->getValue("id");
        $ctDimension = new View("content_type_dimensions", $id);
        $old_url = $ctDimension->url;

        //Updating dimension
        $ctDimension->extend($_POST);
        $ctDimension->url = $f->generateUrlFromText($ctDimension->title);
        $ctDimension->Save();

        //Table name of content type
        $tableName = $db->getValue("table_name", "content_types", "id", $ctDimension->content_type_id);

        //Creating image upload folder for dimension
        if (is_dir("../../uploads/uploaded_pictures/$tableName/$old_url")) {
            rename("../../uploads/uploaded_pictures/$tableName/$old_url", "../../uploads/uploaded_pictures/$tableName/$ctDimension->url");
        }

        //Redirecting to list of dimensions
        $f->redirect("dimensions.php?id=$ctDimension->content_type_id&infomsg=success_edit_dimension");

        break;

    case "delete_ct_dimension":

        //Creating dimension object
        $id = $f->getValue("id");
        $ctDimension = new View("content_type_dimensions", $id);

        //Getting vars
        $url = $ctDimension->url;
        $tableName = $db->getValue("table_name", "content_types", "id", $ctDimension->content_type_id);
        $cid = $ctDimension->content_type_id;

        //Deleting dimension
        $ctDimension->Remove();

        //Deleteing all images and folder
        if (is_dir("../../uploads/uploaded_pictures/$tableName/$url")) {
            $f->recursive_remove_directory("../../uploads/uploaded_pictures/$tableName/$url");
        }

        //Redirecting to list of dimensions
        $f->redirect("dimensions.php?id=$cid&infomsg=success_delete_dimension");

        break;

    case "order":

        $item = $f->getValue("item");
        var_dump($item);
        for ($i = 0; $i < count($item); $i++) {
            $ordering = $i + 1;
            $db->execQuery("UPDATE content_type_fields SET `ordering`='$ordering' WHERE id='$item[$i]'");
        }

        break;
}
?>