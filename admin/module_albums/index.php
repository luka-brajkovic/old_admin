<?php
require("../library/config.php");
$f->checkLogedAdmin("module");
$infomsg = $f->getValue("infomsg");

$module_name = "albums";

$tablePrint = new Tableprint();
$albumsCollection = new Collection("albums");
$albums = $albumsCollection->getCollection("", "ORDER BY title");
$num = $albums->$totalCount;

$album_id = $f->getValue('album_id');
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
                        <h1>Albums</h1>

                        <?php
                        if ($infomsg != "") {

                            switch ($infomsg) {
                                case "success_add_album":
                                    ?>
                                    <div class="message success close">
                                        <h2>Success!</h2>
                                        <p>New album successfuly added.</p>
                                    </div>
                                    <?php
                                    break;

                                case "success_delete_album":
                                    ?>
                                    <div class="message success close">
                                        <h2>Success!</h2>
                                        <p>Album successfuly deleted.</p>
                                    </div>
                                    <?php
                                    break;

                                case "success_edit_album":
                                    ?>
                                    <div class="message success close">
                                        <h2>Success!</h2>
                                        <p>ALbum successfuly updated.</p>
                                    </div>
                                    <?php
                                    break;
                            }
                        }
                        ?>						

                        <form method="POST" id="add_album" name="add_album" action="work.php">
                            <fieldset>
                                <legend>Add album</legend>
                                <p>
                                    <label for="title">Title *</label>
                                    <input type="text" class="lf" name="title" id="title" required="required"/>
                                </p>
                                <input type="hidden" name="action" value="add_album"/>
                                <input type="submit" class="button" value="Save"/>
                            </fieldset>
                        </form>						

                        <hr />

                        <input type="text" name="search_input" id="search_input" class="sf right_input" onblur="if (this.value == '')
                                    this.value = this.defaultValue;" onfocus="if (this.value == this.defaultValue)
                                                this.value = '';" value="Live search..." />
                        <br clear="all" />

                        <hr />
                        <p>
                            <?php $tablePrint->printAlbumsListTable($albums); ?>
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