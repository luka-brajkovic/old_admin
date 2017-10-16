<?php
require("../library/config.php");
$f->checkLogedAdmin("module");
$table_name = $f->getValue("table_name");

$tablePrint = new Tableprint();

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
                    <div id="main" style="margin-left: 320px;">

                        <?php
                        if ($infomsg != "") {

                            switch ($infomsg) {
                                case "success_create_dimension":
                                    ?>
                                    <div class="message success close">
                                        <h2>Success!</h2>
                                        <p>Content type dimension successfuly added.</p>
                                    </div>
                                    <?php
                                    break;

                                case "success_delete_dimension":
                                    ?>
                                    <div class="message success close">
                                        <h2>Success!</h2>
                                        <p>Content type dimension successfuly deleted.</p>
                                    </div>
                                    <?php
                                    break;

                                case "success_edit_dimension":
                                    ?>
                                    <div class="message success close">
                                        <h2>Success!</h2>
                                        <p>Content type dimension successfuly updated.</p>
                                    </div>
                                    <?php
                                    break;
                            }
                        }
                        ?>   

                        <h1>Dimensions for : <?= ucfirst($table_name); ?></h1>

                        <input type="text" style="float: left;" name="search_input" id="search_input" class="sf right_input" onblur="if (this.value == '')
                                    this.value = this.defaultValue;" onfocus="if (this.value == this.defaultValue)
                                                this.value = '';" value="Live search..." />
                        <br clear="all" />

                        <hr />
                        <p>
                            <?php $tablePrint->printDimensionsTable($table_name); ?>
                        </p>

                        <hr />
                        <?php if ($table_name != "albums") { ?>
                            <form method="POST" id="add_dimension" action="work.php">
                                <fieldset>
                                    <legend>Add new dimension</legend>
                                    <p>
                                        <label for="title">Title</label>
                                        <input type="text" class="lf" name="title" id="title" />
                                    </p>

                                    <p>
                                        <label for="crop_resize">Crop type</label>
                                        <select name="crop_resize" id="crop_resize" class="w120">
                                            <option value="1">Crop</option>
                                            <option value="2">Resize</option>
                                        </select>
                                    </p>

                                    <p>
                                        <label for="width">Width</label>
                                        <input type="text" class="sf" name="width" id="width" />
                                    </p>

                                    <p>
                                        <label for="height">Height</label>
                                        <input type="text" class="sf" name="height" id="height" />
                                    </p>

                                    <input type="hidden" name="action" value="add_dimension" />
                                    <input type="hidden" name="table_name" value="<?= $table_name; ?>" />
                                    <input type="submit" class="button" value="Save" />
                                </fieldset>
                            </form>
                        <?php } ?>
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