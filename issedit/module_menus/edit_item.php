<?php
require("../library/config.php");
$f->checkLogedAdmin("module");

$item_id = $f->getValue("item_id");

$menuItem = new View("menu_items", $item_id);

$module_name = "menus";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />		
        <title><?= ADMIN_TITLE; ?></title>
        <?php include("../head.php"); ?>
    </head>
    <body>
        <!-- Container -->
        <div id="container">

            <?php include("../header.php"); ?>

            <!-- Background wrapper -->
            <div id="bgwrap">

                <!-- Main Content -->
                <div id="content">
                    <div id="main" style="margin-left:320px;">
                        <h1>Edit item menu</h1>

                        <form method="POST" id="edit_user" name="edit_user" action="work.php" >
                            <fieldset>
                                <legend>Edit menu item : <?= $menuItem->title; ?></legend>
                                <p>
                                    <label for="title">Title *</label>
                                    <input type="text" class="lf" value="<?= $menuItem->title; ?>" name="title" id="title" required="required" />
                                </p>
                                <p>
                                    <label for="url">URL</label>
                                    <input type="text" class="lf" value="<?= $menuItem->url; ?>" name="url" id="url" />
                                </p>
                                <p>
                                    <label for="open_type">Open in</label>
                                    <select name="open_type" id="open_type" class="lf">
                                        <option value="1" <?php if ($menuItem->open_type == 1) echo 'selected="selected"'; ?> >Same window</option>
                                        <option value="2" <?php if ($menuItem->open_type == 2) echo 'selected="selected"'; ?> >New tab</option>
                                    </select>
                                </p>
                                <input type="hidden" name="action" value="edit_item" />
                                <input type="hidden" name="item_id" value="<?= $menuItem->id ?>" />
                                <input type="hidden" name="menu_id" value="<?= $menuItem->menu_id; ?>" />
                                <input type="hidden" name="type" value="1" />
                                <input type="hidden" name="resource_id" value="0" />
                                <input type="hidden" name="parent_id" value="<?= $menuItem->parent_id ?>" />
                                <input type="submit" class="button" value="Save" /> 
                            </fieldset>
                        </form>						

                        <?php
                        /*
                          $method = "POST";
                          $action = "work.php";
                          $name = "add_item_to_menu";
                          $style = "";
                          $type = "";


                          $open["new"] = array();
                          $type["new"] = array();

                          $novi = array("1" => "Same widnow");
                          $novi2 = array("2" => "New tab");

                          array_push($open["new"], $novi);
                          array_push($open["new"], $novi2);

                          $novi = array("1" => "Link");
                          $novi2 = array("2" => "Content");
                          $novi3 = array("3" => "Category");

                          array_push($type["new"], $novi);
                          array_push($type["new"], $novi2);
                          array_push($type["new"], $novi3);

                          if($data_item['type'] == 1) {

                          $fields = array(
                          "title" => array(
                          "type" => "text",
                          "value" => $data_item['title'],
                          "class" => "lf",
                          "style" => "",
                          "required" => "1",
                          "label" => "",
                          "error" => "Field can not be empty!.::Max field size is 200!",
                          "description" => "",
                          "additional" => "",
                          "js_valid" => "text,0,201"
                          ),

                          "url" => array(
                          "type" => "text",
                          "value" => $data_item['url'],
                          "class" => "lf",
                          "style" => "",
                          "required" => "1",
                          "label" => "",
                          "error" => "Field can not be empty!.::Max field size is 200!",
                          "description" => "",
                          "additional" => "",
                          "js_valid" => "text,0,201"
                          ),

                          "type" => array(
                          "type" => "select",
                          "option" => $type,
                          "selected" => $data_item['type'],
                          "class" => "lf",
                          "default_msg" => "Choose...",
                          "style" => "",
                          "required" => "1",
                          "label" => "", // ako je prazno onda u labelu upisuje naziv polja
                          "error" => "You must choose something!", //ako je prazno onda ide default poruka
                          "description" => "", //Opis polja sta treba da se unese ili tako nesto
                          "additional_option" => "",
                          "additional" => "", // sve ostalo sto moze da stane u <input> ili ostalo
                          "js_valid" => "select,0"
                          ),

                          "open_in" => array(
                          "type" => "select",
                          "option" => $open,
                          "selected" => $data_item['open_type'],
                          "class" => "lf",
                          "default_msg" => "Choose...",
                          "style" => "",
                          "required" => "1",
                          "label" => "", // ako je prazno onda u labelu upisuje naziv polja
                          "error" => "You must choose something!", //ako je prazno onda ide default poruka
                          "description" => "", //Opis polja sta treba da se unese ili tako nesto
                          "additional_option" => "",
                          "additional" => "", // sve ostalo sto moze da stane u <input> ili ostalo
                          "js_valid" => "select,0"
                          ),

                          "action" => array(
                          "type" => "hidden",
                          "value" => "edit_item",
                          "additional" => ""
                          ),

                          "item_id" => array(
                          "type" => "hidden",
                          "value" => $item_id,
                          "additional" => ""
                          ),

                          "menu_id" => array(
                          "type" => "hidden",
                          "value" => $data_item['menu_id'],
                          "additional" => ""
                          ),

                          "parent_id" => array(
                          "type" => "hidden",
                          "value" => $data_item['parent_id'],
                          "additional" => ""
                          ),

                          "save_button" => array(
                          "type" => "submit",
                          "value" => "Save",
                          "class" => "button",
                          "style" => "",
                          "additional" => ""
                          )

                          );

                          } else if($data_item['type'] == 2) {

                          $first_select = $f->printSelectOption("content", "id", "title", $currentLanguage);

                          $fields = array(

                          "type" => array(
                          "type" => "select",
                          "option" => $type,
                          "selected" => $data_item['type'],
                          "class" => "lf",
                          "default_msg" => "Choose...",
                          "style" => "",
                          "required" => "1",
                          "label" => "", // ako je prazno onda u labelu upisuje naziv polja
                          "error" => "You must choose something!", //ako je prazno onda ide default poruka
                          "description" => "", //Opis polja sta treba da se unese ili tako nesto
                          "additional_option" => "",
                          "additional" => "", // sve ostalo sto moze da stane u <input> ili ostalo
                          "js_valid" => "select,0"
                          ),

                          "content_id" => array(
                          "type" => "select",
                          "option" => $first_select,
                          "selected" => $data_item['resource_id'],
                          "default_msg" => "Choose content ...",
                          "style" => "",
                          "required" => "1",
                          "label" => "", // ako je prazno onda u labelu upisuje naziv polja
                          "error" => "You must choose something!", //ako je prazno onda ide default poruka
                          "description" => "", //Opis polja sta treba da se unese ili tako nesto
                          "additional_option" => "",
                          "additional" => "", // sve ostalo sto moze da stane u <input> ili ostalo
                          "js_valid" => "select,0"
                          ),

                          "open_in" => array(
                          "type" => "select",
                          "option" => $open,
                          "selected" => $data_item['open_type'],
                          "class" => "lf",
                          "default_msg" => "Choose...",
                          "style" => "",
                          "required" => "1",
                          "label" => "", // ako je prazno onda u labelu upisuje naziv polja
                          "error" => "You must choose something!", //ako je prazno onda ide default poruka
                          "description" => "", //Opis polja sta treba da se unese ili tako nesto
                          "additional_option" => "",
                          "additional" => "", // sve ostalo sto moze da stane u <input> ili ostalo
                          "js_valid" => "select,0"
                          ),

                          "action" => array(
                          "type" => "hidden",
                          "value" => "edit_item",
                          "additional" => ""
                          ),

                          "item_id" => array(
                          "type" => "hidden",
                          "value" => $item_id,
                          "additional" => ""
                          ),

                          "menu_id" => array(
                          "type" => "hidden",
                          "value" => $data_item['menu_id'],
                          "additional" => ""
                          ),

                          "parent_id" => array(
                          "type" => "hidden",
                          "value" => $data_item['parent_id'],
                          "additional" => ""
                          ),

                          "save_button" => array(
                          "type" => "submit",
                          "value" => "Save",
                          "class" => "button",
                          "style" => "",
                          "additional" => ""
                          )

                          );

                          } else if($data_item['type'] == 3) {

                          //Fieldsets for printing form
                          $field_sets = array();

                          //options for select content_type
                          $first_select = $f->printSelectOption("categories", "id", "title", $currentLanguage);

                          $fields = array(
                          "type" => array(
                          "type" => "select",
                          "option" => $type,
                          "selected" => $data_item['type'],
                          "class" => "lf",
                          "default_msg" => "Choose...",
                          "style" => "",
                          "required" => "1",
                          "label" => "", // ako je prazno onda u labelu upisuje naziv polja
                          "error" => "You must choose something!", //ako je prazno onda ide default poruka
                          "description" => "", //Opis polja sta treba da se unese ili tako nesto
                          "additional_option" => "",
                          "additional" => "", // sve ostalo sto moze da stane u <input> ili ostalo
                          "js_valid" => "select,0"
                          ),

                          "category_id" => array(
                          "type" => "select",
                          "option" => $first_select,
                          "selected" => $data_item['resource_id'],
                          "default_msg" => "Choose category ...",
                          "style" => "",
                          "required" => "1",
                          "label" => "", // ako je prazno onda u labelu upisuje naziv polja
                          "error" => "You must choose something!", //ako je prazno onda ide default poruka
                          "description" => "", //Opis polja sta treba da se unese ili tako nesto
                          "additional_option" => "",
                          "additional" => "", // sve ostalo sto moze da stane u <input> ili ostalo
                          "js_valid" => "select,0"
                          ),

                          "open_in" => array(
                          "type" => "select",
                          "option" => $open,
                          "selected" => $data_item['open_type'],
                          "class" => "lf",
                          "default_msg" => "Choose...",
                          "style" => "",
                          "required" => "1",
                          "label" => "", // ako je prazno onda u labelu upisuje naziv polja
                          "error" => "You must choose something!", //ako je prazno onda ide default poruka
                          "description" => "", //Opis polja sta treba da se unese ili tako nesto
                          "additional_option" => "",
                          "additional" => "", // sve ostalo sto moze da stane u <input> ili ostalo
                          "js_valid" => "select,0"
                          ),

                          "action" => array(
                          "type" => "hidden",
                          "value" => "edit_item",
                          "additional" => ""
                          ),

                          "item_id" => array(
                          "type" => "hidden",
                          "value" => $item_id,
                          "additional" => ""
                          ),

                          "menu_id" => array(
                          "type" => "hidden",
                          "value" => $data_item['menu_id'],
                          "additional" => ""
                          ),

                          "parent_id" => array(
                          "type" => "hidden",
                          "value" => $data_item['parent_id'],
                          "additional" => ""
                          ),

                          "save_button" => array(
                          "type" => "submit",
                          "value" => "Save",
                          "class" => "button",
                          "style" => "",
                          "additional" => ""
                          )

                          );
                          }

                          $form->printForm($method, $action, $fields, $name, $style, $type);
                         */
                        ?>
                        <hr />

                    </div>
                </div>
                <!-- End of Main Content -->


                <?php include("../sidebar.php"); ?>


            </div>
            <!-- End of bgwrap -->

        </div>
        <!-- End of Container -->

        <?php include("../footer.php"); ?>

    </body>
</html>