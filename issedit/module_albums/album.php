<?php
require("../library/config.php");
$f->checkLogedAdmin("module");

$module_name = "albums";

$album_id = $f->getValue("album_id");

$tablePrint = new Tableprint();

$albumsCollection = new Collection("albums");
$albums = $albumsCollection->getCollection();
$num = $albums->$totalCount;

$title = $db->getValue("title", "albums", "id", $album_id);
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
                        <h1>Pictures in album - <?= $title ?></h1>

                        <form method="POST" action="work.php" class="" name="add_picture" id="add_picture" enctype="multipart/form-data" onsubmit="return add_pictureCheck();">
                            <fieldset>
                                <legend>Add picture</legend>
                                <p>
                                    <label for="picture">Picture *</label>
                                    <input type="file" class="mf" name="picture" id="picture" required="required"/>
                                    <span class="validate_error" id="picture_error">You must choose picture!</span>
                                </p>
                                <p>
                                    <label for="picture_name">Picture name</label>
                                    <input type="text" class="lf" name="picture_name" id="picture_name" />
                                </p>
                                <input type="hidden" name="action" value="add_picture"/>
                                <input type="hidden" name="album_id" value="<?= $album_id ?>"/>
                                <input type="submit" name="save_button" id="save_button" value="Add" style="" class="button"  />
                            </fieldset>
                        </form>							

                        <hr />

                        <input type="text" style="float: left;" name="search_input" id="search_input" class="sf right_input" onblur="if (this.value == '')
                                    this.value = this.defaultValue;" onfocus="if (this.value == this.defaultValue)
                                                this.value = '';" value="Live search..." />
                        <br clear="all" />

                        <hr />

                        <p>
                            <?php $tablePrint->printPicturesListTable($album_id); ?>
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