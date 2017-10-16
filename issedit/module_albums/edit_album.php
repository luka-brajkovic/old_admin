<?php
require("../library/config.php");
$f->checkLogedAdmin("module");

$module_name = "albums";

$album_id = $f->getValue("album_id");

$albumsCollection = new Collection("albums");
$albums = $albumsCollection->getCollection();

$albumSingle = new View("albums", $album_id);
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
                        <h1>Edit album - <?= $albumSingle->title; ?></h1>

                        <form method="POST" action="work.php" name="edit_album" id="edit_album" onsubmit="return edit_albumCheck();">
                            <fieldset>
                                <legend>Edit album</legend>
                                <p>
                                    <label for="title">Title *</label>
                                    <input type="text" name="title" id="title" required="required" class="lf" value='<?= $albumSingle->title ?>'  />
                                    <span class="validate_error" id="title_error"></span>				
                                </p>
                                <p>
                                    <label style="" for="description">Description</label> 
                                    <textarea name="description" id="description" class="mf" style="height: 400px; width: 600px;"><?= $albumSingle->description ?></textarea>
                                </p>
                                <input type="hidden" name="action" id="action" value="edit_album"  />
                                <input type="hidden" name="album_id" id="album_id" value="<?= $albumSingle->id ?>"  />
                                <input type="submit" name="save_button" id="save_button" value="Save" style="" class="button"  />
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