<?php
require("../library/config.php");
$f->checkLogedAdmin("module");
$id = $f->getValue("id");

$module_name = "fields";
$field = new View("fields", $id);
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
                        <h1>Edit field: <?= $field->title; ?></h1>

                        <form method="POST" id="edit_field" action="work.php">
                            <fieldset>
                                <legend>Edit field <?= $field->title; ?></legend>
                                <p>
                                    <label for="title">Title</label>
                                    <input type="text" name="title" id="title" class="lf" value="<?= $field->title; ?>" />
                                </p>

                                <p>
                                    <label for="field_type">Field type</label>
                                    <select name="field_type" id="field_type" class="w120">
                                        <option value="text" <?php if ($field->field_type == "text") echo 'selected="selected"'; ?>>Text field</option>
                                        <option value="textarea" <?php if ($field->field_type == "textarea") echo 'selected="selected"'; ?>>Textarea</option>
                                        <option value="wysiwyg" <?php if ($field->field_type == "wysiwyg") echo 'selected="selected"'; ?>>WYSIWYG editor</option>
                                        <option value="select" <?php if ($field->field_type == "select") echo 'selected="selected"'; ?>>Select</option>
                                        <option value="radio" <?php if ($field->field_type == "radio") echo 'selected="selected"'; ?>>Radio</option>
                                        <option value="checkbox" <?php if ($field->field_type == "checkbox") echo 'selected="selected"'; ?>>Checkbox</option>
                                        <option value="datepicker" <?php if ($field->field_type == "datepicker") echo 'selected="selected"'; ?>>Datepicker</option>
                                        <option value="colorpicker" <?php if ($field->field_type == "colorpicker") echo 'selected="selected"'; ?>>Colorpicker</option>
                                        <option value="image" <?php if ($field->field_type == "image") echo 'selected="selected"'; ?>>Image upload</option>
                                        <option value="file" <?php if ($field->field_type == "file") echo 'selected="selected"'; ?>>File upload</option>
                                        <option value="select_table" <?php if ($field->field_type == "select_table") echo 'selected="selected"'; ?>>Select table</option>
                                    </select>
                                </p>

                                <p>
                                    <label for="default_value">Default value</label>
                                    <input type="text" name="default_value" id="default_value" class="lf" value="<?= $field->default_value; ?>" />
                                </p>

                                <p>
                                    <label for="show_in_list">Show in list</label>
                                    <select name="show_in_list" id="show_in_list" class="w120">
                                        <option value="0" <?php if ($field->show_in_list == 0) echo 'selected="selected"'; ?>>No</option>
                                        <option value="1" <?php if ($field->show_in_list == 1) echo 'selected="selected"'; ?>>Yes</option>
                                    </select>
                                </p>

                                <input type="hidden" name="action" value="edit_field" />
                                <input type="hidden" name="table_name" value="<?= $field->table_name; ?>" />
                                <input type="hidden" name="id" value="<?= $field->id; ?>" />
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