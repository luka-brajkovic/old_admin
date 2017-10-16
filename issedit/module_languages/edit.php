<?php
require("../library/config.php");
$f->checkLogedAdmin("module");
$infomsg = $f->getValue("infomsg");
$id = $f->getValue("id");

$tablePrint = new Tableprint();
$language = new View("languages", $id);

$module_name = "languages";
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

                        <h1>Edit language: <?= $language->title; ?></h1>


                        <form method="POST" id="edit_language" action="work.php">
                            <fieldset>
                                <legend>Edit language</legend>
                                <p>
                                    <label for="title">Title</label>
                                    <input type="text" class="lf" name="title" id="title" value="<?= $language->title; ?>" />
                                </p>

                                <p>
                                    <label for="title">Code</label>
                                    <input type="text" class="lf" name="code" id="code" value="<?= $language->code; ?>"/>
                                </p>

                                <p>
                                    <label for="is_default">Is default?</label>
                                    <select name="is_default" id="is_default" class="w120">
                                        <option value="0" <?php if ($language->is_default == 0) echo 'selected="selected"'; ?>>No</option>
                                        <option value="1" <?php if ($language->is_default == 1) echo 'selected="selected"'; ?>>Yes</option>
                                    </select>
                                </p>

                                <input type="hidden" name="action" value="edit" />
                                <input type="hidden" name="id" value="<?= $language->id; ?>" />
                                <input type="submit" class="button" value="Save" />
                            </fieldset>
                        </form>

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