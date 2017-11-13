<?php
require("../library/config.php");
$f->checkLogedAdmin("module");

$module_name = "albums";
$albumsCollection = new Collection("albums");
$albums = $albumsCollection->getCollection();

$selected = $f->getValue("selected"); //za sidebar
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
                        <h1>Add Gallery from ZIP file</h1>

                        <form method="POST" action="work.php" class="" name="add_album" id="add_album" enctype="multipart/form-data" onsubmit="return add_albumCheck();">
                            <fieldset>
                                <legend>Add album</legend>
                                <p>
                                    <label for="zip_file">Zip file *</label>
                                    <input type="file" name="zip_file" id="zip_file" required="required" class="mf" style=""  />
                                    <span class="validate_error" id="zip_file_error">You must choose picture!</span>
                                </p>
                                <p>
                                    <label for="album_id">Album id *</label>
                                    <select name="album_id" id="album_id" required="required" class="lf">
                                        <option value="0">Choose album...</option>
                                        <?php
                                        foreach ($albums as $album) {
                                            echo '<option value="' . $album->id . '">' . $album->title . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <span class="validate_error" id="album_id_error">You need to select something!</span>
                                </p>
                                <input type="hidden" name="action" id="action" value="add_gallery"  />
                                <input type="submit" name="save_button" id="save_button" value="Add" style="" class="button"  />
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