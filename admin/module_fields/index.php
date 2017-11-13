<?php
require("../library/config.php");
$f->checkLogedAdmin("module");
$table_name = $f->getValue("table_name");
$infomsg = $f->getValue("infomsg");

$module_name = "fields";
$tablePrint = new Tableprint();
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

                        <?php
                        if ($infomsg != "") {

                            switch ($infomsg) {
                                case "success_create_field":
                                    ?>
                                    <div class="message success close">
                                        <h2>Success!</h2>
                                        <p>Field successfuly added.</p>
                                    </div>
                                    <?php
                                    break;

                                case "success_delete_field":
                                    ?>
                                    <div class="message success close">
                                        <h2>Success!</h2>
                                        <p>Field successfuly deleted.</p>
                                    </div>
                                    <?php
                                    break;

                                case "success_edit_field":
                                    ?>
                                    <div class="message success close">
                                        <h2>Success!</h2>
                                        <p>Field successfuly updated.</p>
                                    </div>
                                    <?php
                                    break;
                            }
                        }
                        ?>

                        <h1>Fields for : <?= ucfirst($table_name); ?></h1>

                        <input type="text" style="float: left;" name="search_input" id="search_input" class="sf right_input" onblur="if (this.value == '')
                                    this.value = this.defaultValue;" onfocus="if (this.value == this.defaultValue)
                                                this.value = '';" value="Live search..." />
                        <br clear="all" />

                        <hr />
                        <p>
                            <?php $tablePrint->printFieldsTable($table_name); ?>
                        </p>

                        <hr />

                        <form method="POST" id="add_field" action="work.php">
                            <fieldset>
                                <legend>Add new field for <?= ucfirst($table_name); ?></legend>
                                <p>
                                    <label for="title">Title</label>
                                    <input type="text" name="title" id="title" class="lf" />
                                </p>

                                <p>
                                    <label for="field_type">Field type</label>
                                    <select name="field_type" id="field_type" class="w120">
                                        <option value="text">Text field</option>
                                        <option value="textarea">Textarea</option>
                                        <option value="wysiwyg">WYSIWYG editor</option>
                                        <option value="select">Select</option>
                                        <option value="radio">Radio</option>
                                        <option value="checkbox">Checkbox</option>
                                        <option value="datepicker">Datepicker</option>
                                        <option value="colorpicker">Colorpicker</option>
                                        <option value="image">Image upload</option>
                                        <option value="file">File upload</option>
                                        <option value="select_table">Select table</option>
                                    </select>
                                </p>

                                <p>
                                    <label for="default_value">Default value</label>
                                    <input type="text" name="default_value" id="default_value" class="lf" />
                                </p>

                                <p>
                                    <label for="show_in_list">Show in list</label>
                                    <select name="show_in_list" id="show_in_list" class="w120">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </p>

                                <input type="hidden" name="action" value="add_field" />
                                <input type="hidden" name="table_name" value="<?= $table_name; ?>" />
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