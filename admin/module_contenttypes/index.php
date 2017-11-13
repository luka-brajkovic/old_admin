<?php
require("../library/config.php");
$f->checkLogedAdmin("module");
$infomsg = $f->getValue("infomsg");

$module_name = "contenttypes";
$sidebar = "index";
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
                                case "success_create_ct":
                                    ?>
                                    <div class="message success close">
                                        <h2>Success!</h2>
                                        <p>Content type successfuly added.</p>
                                    </div>
                                    <?php
                                    break;

                                case "success_delete_ct":
                                    ?>
                                    <div class="message success close">
                                        <h2>Success!</h2>
                                        <p>Content type successfuly deleted.</p>
                                    </div>
                                    <?php
                                    break;

                                case "success_edit_ct":
                                    ?>
                                    <div class="message success close">
                                        <h2>Success!</h2>
                                        <p>Content type successfuly updated.</p>
                                    </div>
                                    <?php
                                    break;
                            }
                        }
                        ?>
                        <h1>Content types</h1>

                        <input type="text" name="search_input" id="search_input" class="sf right_input" onblur="if (this.value == '')
                                    this.value = this.defaultValue;" onfocus="if (this.value == this.defaultValue)
                                                this.value = '';" value="Live search..." />
                        <br clear="all" />

                        <hr />
                        <p>
                            <?php $tablePrint->printContentTypesTable("content_types"); ?>
                        </p>

                        <hr />

                        <form method="POST" id="add_contenttype" action="work.php">
                            <fieldset>
                                <legend>Add new content type</legend>
                                <p>
                                    <label for="title">Title</label>
                                    <input type="text" name="title" id="title" class="lf" />
                                </p>

                                <p>
                                    <label for="comments">Comments?</label>
                                    <select name="comments" id="comments" class="w120">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </p>

                                <p>
                                    <label for="category_type">Category type</label>
                                    <select name="category_type" id="category_type" class="w120">
                                        <option value="0">No category</option>
                                        <option value="1">Single category</option>
                                        <option value="2">Multi category</option>
                                    </select>
                                </p>

                                <input type="hidden" name="action" value="add_ct" />
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