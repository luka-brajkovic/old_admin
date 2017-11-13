<?php
require("../library/config.php");
$f->checkLogedAdmin("module");
$id = $f->getValue("id");
$ct = new View("content_types", $id);

$module_name = "contenttypes";
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
                        <h1>Edit Content type: <?= $ct->title; ?></h1>

                        <form method="POST" id="edit_contenttype" action="work.php">
                            <fieldset>
                                <legend>Edit content type</legend>
                                <p>
                                    <label for="title">Title</label>
                                    <input type="text" name="title" id="title" class="lf" value="<?= $ct->title; ?>" />
                                </p>

                                <p>
                                    <label for="comments">Comments?</label>
                                    <select name="comments" id="comments" class="w120">
                                        <option value="0" <?php if ($ct->comments == 0) echo 'selected="selected"'; ?>>No</option>
                                        <option value="1" <?php if ($ct->comments == 1) echo 'selected="selected"'; ?>>Yes</option>
                                    </select>
                                </p>

                                <p>
                                    <label for="category_type">Category type</label>
                                    <select name="category_type" id="category_type" class="w120">
                                        <option value="0" <?php if ($ct->category_type == 0) echo 'selected="selected"'; ?>>No category</option>
                                        <option value="1" <?php if ($ct->category_type == 1) echo 'selected="selected"'; ?>>Single category</option>
                                        <option value="2" <?php if ($ct->category_type == 2) echo 'selected="selected"'; ?>>Multi category</option>
                                    </select>
                                </p>
                                
                                <p>
                                    <label for="ordering">Order</label>
                                    <select name="ordering" id="ordering" class="w120">
                                        <?php
                                        $numbTypes = mysqli_query($conn,"SELECT * FROM `content_types` ")or die(mysqli_error($conn));
                                        $numbTypese = mysqli_num_rows($numbTypes) + 1;
                                        for($i=1;$i<$numbTypese;$i++){
                                        ?>
                                        <option value="<?= $i; ?>" <?php if ($ct->ordering == $i) echo 'selected="selected"'; ?>><?= $i; ?></option>
                                        <?php } ?>
                                    </select>
                                </p>
                                <input type="hidden" name="action" value="edit_ct" />
                                <input type="hidden" name="id" value="<?= $ct->id; ?>" />
                                <input type="submit" class="button" value="Save" />

                            </fieldset>
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