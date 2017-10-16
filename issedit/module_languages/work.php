<?php

require("../library/config.php");

//Here we have all values from POST and GET
$values = $f->getRequestValues();
$action = $values["action"];

switch ($action) {

    case "add":

        //Creating new language
        $language = new View("languages");
        $language->extend($_POST);
        $language->Save();

        //If default set all others to no
        if ($language->is_default == 1) {
            $db->execQuery("UPDATE languages SET `is_default`='0' WHERE id != '$language->id'");
        }

        //Adding row in settings
        $settings = new View("settings");
        $settings->lang_id = $language->id;

        $settings->Save();

        // Createing empty xml file for new language
        $file = fopen("../../library/languages/" . $language->code . ".xml", "w");
        fwrite($file, '<?xml version="1.0" encoding="UTF-8"?>');
        fwrite($file, '<promenljive>');
        fwrite($file, '</promenljive>');
        fclose($file);

        //adding new language to all contents and categories
        /* 	 $content_typesCollection = new Collection("content_types");
          $content_types = $content_typesCollection->getCollection();
          foreach($content_types as $ct){
          $contentsCollections = new Collection($ct->table_name);
          $contents = $contentsCollections->getCollectionCustom("SELECT DISTINCT resource_id, ordering FROM " . $ct->table_name);
          foreach($contents as $content){
          $newContent = new View($ct->table_name);
          $newContent->resource_id = $content->resource_id;
          $newContent->ordering = $content->ordering;
          $newContent->Save();
          }
          } */

        //Redirecting to list of languages
        $f->redirect("index.php?infomsg=success_add_language");

        break;

    case "edit":

        //Editing language
        $id = $values["id"];
        $language = new View("languages", $id);
        $old_xml_name = $language->code;
        $language->extend($_POST);
        $language->Save();

        rename("../../library/languages/" . $old_xml_name . ".xml", "../../library/languages/" . $language->code . ".xml");
        //If set to default set all others to no
        if ($language->is_default == 1) {
            $db->execQuery("UPDATE languages SET `is_default`='0' WHERE id != '$language->id'");
        }

        //Redirecting to languages list
        $f->redirect("index.php?infomsg=success_edit_language");

        break;

    case "delete":

        //Deleting language
        $id = $values["id"];
        $language = new View("languages", $id);
        $language->Remove();

        //Deleting row from settings
        $settings = new View("settings", $id, "lang_id");
        $settings->Remove();

        if (is_file("../../library/languages/" . $language->code . ".xml")) {
            unlink("../../library/languages/" . $language->code . ".xml");
        }
        //Redirecting to languages list
        $f->redirect("index.php?infomsg=success_delete_language");

        break;
}
?>