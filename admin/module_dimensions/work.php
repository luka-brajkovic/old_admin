<?php

require("../library/config.php");

//Here we have all values from POST and GET
$values = $f->getRequestValues();
$action = $values["action"];

switch ($action) {

    case "add_dimension":

        //Creating new dimension
        $ctDimension = new View("dimensions");
        $ctDimension->extend($_POST);
        $ctDimension->url = $f->generateUrlFromText($ctDimension->title);
        $ctDimension->Save();

        //Creating image upload folder for dimension
        if (!is_dir("../../uploads/uploaded_pictures/$ctDimension->table_name/$ctDimension->url")) {
            mkdir("../../uploads/uploaded_pictures/$ctDimension->table_name/$ctDimension->url");
        }

        //Redirecting to list of dimensions
        $f->redirect("index.php?table_name=$ctDimension->table_name&infomsg=success_create_dimension");

        break;

    case "edit_dimension":

        //Geting some values
        $id = $f->getValue("id");
        $ctDimension = new View("dimensions", $id);
        $old_url = $ctDimension->url;

        //Updating dimension
        $ctDimension->extend($_POST);
        $ctDimension->url = $f->generateUrlFromText($ctDimension->title);
        $ctDimension->Save();

        //Creating image upload folder for dimension
        if (is_dir("../../uploads/uploaded_pictures/$ctDimension->table_name/$old_url")) {
            rename("../../uploads/uploaded_pictures/$ctDimension->table_name/$old_url", "../../uploads/uploaded_pictures/$ctDimension->table_name/$ctDimension->url");
        }

        //Redirecting to list of dimensions
        $f->redirect("index.php?table_name=$ctDimension->table_name&infomsg=success_edit_dimension");

        break;

    case "delete_dimension":

        //Creating dimension object
        $id = $f->getValue("id");
        $ctDimension = new View("dimensions", $id);

        //Getting vars
        $url = $ctDimension->url;
        $tableName = $ctDimension->table_name;

        //Deleting dimension
        $ctDimension->Remove();

        //Deleteing all images and folder
        if (is_dir("../../uploads/uploaded_pictures/$tableName/$url")) {
            $f->recursive_remove_directory("../../uploads/uploaded_pictures/$tableName/$url");
        }

        //Redirecting to list of dimensions
        $f->redirect("index.php?table_name=$tableName&infomsg=success_delete_dimension");

        break;
}
?>