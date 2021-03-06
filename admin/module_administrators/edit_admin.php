<?php
require("../library/config.php");
$f->checkLogedAdmin("module");
$infomsg = $f->getValue("infomsg");

$module_name = "administrators";

$admin_id = $f->getValue("admin_id");
$admin = new View("administrators", $admin_id);
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
                    <div id="main">
                        <?php
                        if ($adminsQuest['role'] == 1){
                        ?>
                        <a onclick="add_new_field();" id="counter" rel="2" style="position: fixed;right: 180px;top: 136px;z-index: 1;width: 128px;height: 39px;background: #454545 none repeat scroll 0% 0%;color: #FFF !important;padding: 5px;text-align: center;cursor: pointer;border-radius: 5px;font-size: 13px;line-height: 39px;" class="button" href="index.php">List admin</a>
                        <a onclick="add_new_field();" id="counter" rel="2" style="position: fixed;right: 20px;top: 136px;z-index: 1;width: 128px;height: 39px;background: #454545 none repeat scroll 0% 0%;color: #FFF !important;padding: 5px;text-align: center;cursor: pointer;border-radius: 5px;font-size: 13px;line-height: 39px;" class="button" href="add_admin.php">Add new admin</a>                        
                        <?php
                        }
                        $role_array = array("2" => "Moderator", "1" => "Administrator");
                        if($_GET['wrong_data']=="yes"){
                        ?>                        
                        <div class="message error close">
                            <h2>Error!</h2>
                            <p>Check the entered data.</p>
                        </div>
                        <?php } 
                        if($_GET['wrong_pass']=="yes"){
                        ?>                        
                        <div class="message error close">
                            <h2>Error!</h2>
                            <p>Check the entered password.</p>
                        </div>
                        <?php } ?>
                        <h1>Edit admin - <?= $admin->fullname; ?></h1>
                        <form method="POST" id="edit_admin" name="edit_admin" action="work.php">
                            <fieldset>
                                <legend>Edit user</legend>
                                <p>
                                    <label for="fullname">Fullname *</label>
                                    <input type="text" class="lf" value="<?= $admin->fullname ?>" name="fullname" id="fullname" required="required" />
                                </p>
                                <p>
                                    <label for="code">Username *</label>
                                    <input type="text" class="lf" name="username" id="username" value="<?= $admin->username ?>" required="required" />
                                </p>
                                <p>
                                    <label for="code">Password *</label>
                                    <input type="password" class="lf" name="password" id="password" required="required"/>
                                </p>
                                <p>
                                    <label for="code">Repeat password *</label>
                                    <input <?= ($_GET['wrong_pass']=="yes")?'style="background: #FFAEB0;"':''; ?> type="password" class="lf" name="rep_pass" id="rep_pass" required="required"/>
                                </p>
                                <p>
                                    <label for="email">Email *</label>
                                    <input type="text" class="mf" name="email" id="email" value="<?= $admin->email ?>" required="required"/>
                                </p>
                                <p>
                                    <?php if ($adminsQuest['role'] == 1) { ?>
                                        <label for="role">Role</label>
                                        <select name="role" id="role" class="lf">
                                            <option <?php if ($admin->role == 1) echo 'selected="selected"' ?> value="1">Administrator</option>
                                            <option <?php if ($admin->role == 2) echo 'selected="selected"' ?> value="2">Moderator</option>
                                        </select>
                                    <?php } ?>
                                </p>


                                <input type="hidden" id="action" name="action" value="edit_admin" />
                                <input type="hidden" id="admin_id" name="admin_id" value="<?= $admin_id ?>" />
                                <input type="submit" class="button" value="Save" /> 
                            </fieldset>
                        </form>
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