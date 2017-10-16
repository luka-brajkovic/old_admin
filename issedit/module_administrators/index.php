<?php
require("../library/config.php");
$f->checkLogedAdmin("module");

$module_name = "administrators";

$tablePrint = new tablePrint();

$adminsCollection = new Collection("administrators");
$admin = $adminsCollection->getCollection();
$admin_info = $_SESSION['admin_info'];

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

            <?php include("../header.php");
            
            if ($adminsQuest['role'] != 1){
                $f->redirect("edit_admin.php?admin_id=" . $admin_info['id']);
            }
            ?>

            <!-- Background wrapper -->
            <div id="bgwrap">

                <!-- Main Content -->
                <div id="content">
                    <div id="main">
                        <?php
                        if ($adminsQuest['role'] == 1){
                        ?>
                        <a onclick="add_new_field();" id="counter" rel="2" style="position: fixed;right: 20px;top: 136px;z-index: 1;width: 128px;height: 39px;background: #454545 none repeat scroll 0% 0%;color: #FFF !important;padding: 5px;text-align: center;cursor: pointer;border-radius: 5px;font-size: 13px;line-height: 39px;" class="button" href="add_admin.php">Add new admin</a>
                        <?php
                        } ?>
                        <h1>Administrator settings</h1>

                        <?php
                        if ($admin_info['id'] == 1) {
                            ?>
                            <form method="POST" id="add_admin" name="add_admin" action="work.php">
                                <fieldset>
                                    <legend>Add administrator/moderator</legend>
                                    <p>
                                        <label for="username">Username *</label>
                                        <input type="text" class="lf" name="username" id="username" required="required"/>
                                    </p>
                                    <p>
                                        <select name="role" class="w120">
                                            <option value = "1">Administrator</option>
                                            <option value = "2" selected="selected">Moderator</option>
                                        </select>
                                    </p>
                                    <input type="hidden" name="action" value="add_admin"/>
                                    <input type="submit" class="button" value="Save"/>
                                </fieldset>
                            </form>						
                            <?php
                        }

                        $tablePrint->printAdminListTable($admin_info['id']);
                        /*

                          $method = "POST";
                          $action = "work.php";
                          $name = "edit_admin";
                          $style = "";
                          $type = "";

                          //Comments user array for option
                          $comments_user["new"] = array(); //ovo new je label za OPTGROUP

                          $novi = array("1" => "Registred users");
                          $novi2 = array("2" => "Unregistred users");

                          array_push($comments_user["new"], $novi);
                          array_push($comments_user["new"], $novi2);

                          //Comments approval array for option
                          $comments_approval["new"] = array(); //ovo new je label za OPTGROUP

                          $novi = array("1" => "Approve comments");
                          $novi2 = array("2" => "Live comments");

                          array_push($comments_approval["new"], $novi);
                          array_push($comments_approval["new"], $novi2);

                          //Users approval array for option
                          $users_approval["new"] = array(); //ovo new je label za OPTGROUP

                          $novi = array("1" => "Approve users");
                          $novi2 = array("2" => "Do not approve users");

                          array_push($users_approval["new"], $novi);
                          array_push($users_approval["new"], $novi2);

                          $first_select = $f->printSelectOption("content_type", "id", "title", $currentLanguage);
                          $parent_select["parent"] = array();
                          $parent_select["parent"] = $categories->returnCategoriesSelect(0, 0, $currentLanguage, $data_settings['rss_type'], "", $parent_select["parent"]);

                          $fields = array(
                          "username" => array(
                          "type" => "text",
                          "value" => $data_settings['username'],
                          "class" => "lf",
                          "style" => "",
                          "required" => "1",
                          "label" => "",
                          "error" => "Field can not be empty!",
                          "description" => "",
                          "additional" => "",
                          "js_valid" => "text,0,201"
                          ),

                          "fullname" => array(
                          "type" => "text",
                          "value" => $data_settings['fullname'],
                          "class" => "lf",
                          "style" => "",
                          "required" => "1",
                          "label" => "",
                          "error" => "Field can not be empty!",
                          "description" => "",
                          "additional" => "",
                          "js_valid" => "text,0,201"
                          ),

                          "email" => array(
                          "type" => "text",
                          "value" => $data_settings['email'],
                          "class" => "lf",
                          "style" => "",
                          "required" => "1",
                          "label" => "",
                          "error" => "Field can not be empty!",
                          "description" => "",
                          "additional" => "",
                          "js_valid" => "text,0,201"
                          ),

                          "password" => array(
                          "type" => "text",
                          "value" => "",
                          "class" => "lf",
                          "style" => "",
                          "required" => "0",
                          "label" => "",
                          "error" => "Field can not be empty!",
                          "description" => "",
                          "additional" => "",
                          "js_valid" => "text,0,201"
                          ),

                          "repeat_password" => array(
                          "type" => "text",
                          "value" => "",
                          "class" => "lf",
                          "style" => "",
                          "required" => "0",
                          "label" => "",
                          "error" => "Field can not be empty!",
                          "description" => "",
                          "additional" => "",
                          "js_valid" => "text,0,201"
                          ),

                          "action" => array(
                          "type" => "hidden",
                          "value" => "edit_admin",
                          "additional" => ""
                          ),

                          "admin_id" => array(
                          "type" => "hidden",
                          "value" => $adminId,
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

                          $form->printForm($method, $action, $fields, $name, $style, $type);

                         */
                        ?>
                        <hr />

                    </div>
                </div>
                <!-- End of Main Content -->

            </div>
            <!-- End of bgwrap -->

        </div>
        <!-- End of Container -->

        <?php include("../footer.php"); ?>

    </body>
</html>
<script type="text/javascript">

    $('#rss_type').change(function () {
        var type = $(this).attr("value");
        $.ajax({
            type: "POST",
            url: "work.php",
            data: "action=fill_rss_category&type=" + type,
            success: function (msg) {
                $('#rss_category').html(msg);
            }
        });
    });

</script>