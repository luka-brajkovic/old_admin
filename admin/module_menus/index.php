<?php
require("../library/config.php");
$f->checkLogedAdmin("module");
$infomsg = $f->getValue("infomsg");

$module_name = "menus";
$tablePrint = new Tableprint();

$langCollection = new Collection("languages");
$languages = $langCollection->getCollection();
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
                <div id="content" >
                    <div id="main" style="margin-left:320px;">

                        <?php
                        if ($infomsg != "") {

                            switch ($infomsg) {
                                case "success_add_menu":
                                    ?>
                                    <div class="message success close">
                                        <h2>Success!</h2>
                                        <p>New menu successfuly added.</p>
                                    </div>
                                    <?php
                                    break;

                                case "success_delete_menu":
                                    ?>
                                    <div class="message success close">
                                        <h2>Success!</h2>
                                        <p>Menu successfuly deleted.</p>
                                    </div>
                                    <?php
                                    break;

                                case "success_edit_menu":
                                    ?>
                                    <div class="message success close">
                                        <h2>Success!</h2>
                                        <p>Menu successfuly updated.</p>
                                    </div>
                                    <?php
                                    break;
                            }
                        }
                        ?>

                        <h1>Frontend Menus</h1>

                        <form method="POST" id="add_menu" name="add_menu" action="work.php">
                            <div id="tabs">
                                <ul>
                                    <?php
                                    foreach ($languages as $key => $language) {
                                        ?>
                                        <li><a href="#tab<?= $language->code; ?>"><?= $language->title; ?></a></li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                                <?php
                                foreach ($languages as $key => $language) {
                                    $lang_code = $language->code;
                                    ?>
                                    <div id="tab<?= $language->code; ?>">
                                        <fieldset>
                                            <legend>Add new menu for language: <?= $language->title; ?></legend>
                                            <p>
                                                <label for="title">Title</label>
                                                <input type="text" class="lf" name="<?= $language->code; ?>[title]" id="title" />
                                            </p>

                                            <input type="hidden" name="action" value="add_menu" />
                                            <input type="submit" class="button" value="Save" /> 
                                        </fieldset>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </form>
                        <hr />

                        <!-- Print menus table -->   
                        <p>
                            <?php $tablePrint->printMenusTable(); ?>
                        </p>   

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