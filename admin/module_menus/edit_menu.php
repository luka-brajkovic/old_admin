<?php
require("../library/config.php");
$f->checkLogedAdmin("module");

$module_name = "menus";
$menuResId = $f->getValue("rid");

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
                <div id="content">
                    <div id="main" style="margin-left:320px;">
                        <h1>Edit Menu</h1>

                        <form method="POST" id="edit_menu" name="edit_menu" action="work.php">
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

                                    $menusColl = new Collection("menus");
                                    $menus = $menusColl->getCollection("WHERE resource_id='$menuResId' AND lang_id = '$language->id'");

                                    if ($menusColl->resultCount == 0) {
                                        $menuDefault = $menusColl->getCollection("WHERE resource_id='$menuResId' AND lang_id = '$currentLanguage'");
                                        $menus[0] = new View("menus");
                                        $menus[0]->resource_id = $menuDefault[0]->resource_id;
                                        $menus[0]->lang_id = $language->id;
                                        $menus[0]->Save();
                                    }

                                    $menu = $menus[0];
                                    ?>
                                    <div id="tab<?= $language->code; ?>">
                                        <fieldset>
                                            <legend>Edit menu <?= $menu->title; ?> for language: <?= $language->title; ?></legend>
                                            <p>
                                                <label for="title">Title</label>
                                                <input type="text" class="lf" name="<?= $language->code; ?>[title]" id="title" value="<?= $menu->title; ?>" />
                                            </p>

                                            <input type="submit" class="button" value="Save" /> 
                                        </fieldset>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <input type="hidden" name="resource_id" value="<?= $menuResId; ?>" />
                            <input type="hidden" name="action" value="edit_menu" />
                        </form>
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