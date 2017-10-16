<?php
require("../library/config.php");
$f->checkLogedAdmin("module");
$infomsg = $f->getValue("infomsg");

$tablePrint = new Tableprint();
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
                    <div id="main" style="margin-left: 320px;">
                        <?php
                        if ($infomsg != "") {

                            switch ($infomsg) {
                                case "success_add_language":
                                    ?>
                                    <div class="message success close">
                                        <h2>Success!</h2>
                                        <p>New language successfuly added.</p>
                                    </div>
                                    <?php
                                    break;

                                case "success_delete_language":
                                    ?>
                                    <div class="message success close">
                                        <h2>Success!</h2>
                                        <p>Language successfuly deleted.</p>
                                    </div>
                                    <?php
                                    break;

                                case "success_edit_language":
                                    ?>
                                    <div class="message success close">
                                        <h2>Success!</h2>
                                        <p>Language successfuly updated.</p>
                                    </div>
                                    <?php
                                    break;
                            }
                        }
                        ?>

                        <h1>Languages</h1>

                        <input type="text" style="float: left;" name="search_input" id="search_input" class="sf right_input" onblur="if (this.value == '')
                                    this.value = this.defaultValue;" onfocus="if (this.value == this.defaultValue)
                                                this.value = '';" value="Live search..." />
                        <br clear="all" />

                        <hr />
                        <p>
                            <?php $tablePrint->printLangTable(); ?>
                        </p>

                        <hr />

                        <form method="POST" id="add_language" action="work.php">
                            <fieldset>
                                <legend>Add new language</legend>
                                <p>
                                    <label for="title">Title</label>
                                    <input type="text" class="lf" name="title" id="title" />
                                </p>

                                <p>
                                    <label for="title">Code</label>
                                    <input type="text" class="lf" name="code" id="code" />
                                </p>

                                <p>
                                    <label for="is_default">Is default?</label>
                                    <select name="is_default" id="is_default" class="w120">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </p>

                                <input type="hidden" name="action" value="add" />
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