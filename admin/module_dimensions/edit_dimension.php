<?php
require("../library/config.php");
$f->checkLogedAdmin("module");
$id = $f->getValue("id");

$dimension = new View("dimensions", $id);

$module_name = "dimensions";
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

                        <h1>Edit dimension: <?= $dimension->title; ?></h1>

                        <form method="POST" id="edit_dimension" action="work.php">
                            <fieldset>
                                <legend>Edit dimension</legend>
                                <p>
                                    <label for="title">Title</label>
                                    <input type="text" class="lf" name="title" id="title" value="<?= $dimension->title; ?>" />
                                </p>
                                <?php if ($dimension->table_name != "albums") { ?> 						    
                                    <p>
                                        <label for="crop_resize">Crop type</label>
                                        <select name="crop_resize" id="crop_resize" class="w120">
                                            <option value="1" <?php if ($dimension->crop_resize == 1) echo 'selected="selected"'; ?>>Crop</option>
                                            <option value="2" <?php if ($dimension->crop_resize == 2) echo 'selected="selected"'; ?>>Resize</option>
                                        </select>
                                    </p>
                                <?php } ?> 
                                <p>
                                    <label for="width">Width</label>
                                    <input type="text" class="sf" name="width" id="width" value="<?= $dimension->width; ?>" />
                                </p>

                                <p>
                                    <label for="height">Height</label>
                                    <input type="text" class="sf" name="height" id="height" value="<?= $dimension->height; ?>" />
                                </p>

                                <input type="hidden" name="action" value="edit_dimension" />
                                <input type="hidden" name="table_name" value="<?= $dimension->table_name; ?>" />
                                <input type="hidden" name="id" value="<?= $dimension->id; ?>" />
                                <input type="submit" class="button" value="Save" />
                            </fieldset>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <?php include("../footer.php"); ?>

    </body>
</html>