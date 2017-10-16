<?php
require("../library/config.php");
$f->checkLogedAdmin("module");

$module_name = "albums";
$album_id = $f->getValue("album_id");
$picture_id = $f->getValue("picture_id");

$albumsCollection = new Collection("albums");
$albums = $albumsCollection->getCollection();

$picture = new View("pictures", $picture_id);
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

                        <h1>Edit picture - <?= $picture->picture_name; ?></h1>                                
                        <p>
                            <img src="../../uploads/uploaded_pictures/albums/resize/<?= $picture->file_name ?>" />              
                        </p>

                        <form method="POST" action="work.php" class="" name="edit_picture" id="edit_picture" enctype="multipart/form-data" onsubmit="return edit_pictureCheck();">
                            <fieldset>
                                <legend>Edit picture</legend>
                                <p>
                                    <label for="picture_name">Picture name</label>
                                    <input type="text" name="picture_name" id="picture_name"  class="lf" value='<?= $picture->picture_name; ?>' style=""  />
                                    <span class="validate_error" id="picture_name_error"></span>		
                                </p>
                                <input type="hidden" name="action" id="action" value="edit_picture"  />
                                <!-- <input type="hidden" name="album_id" id="album_id" value="2"  /> -->
                                <input type="hidden" name="picture_id" id="picture_id" value="<?= $picture->id ?>"  />
                                <input type="submit" name="save_button" id="save_button" value="Save" style="" class="button"  />
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