<?php
require("../library/config.php");
$f->checkLogedAdmin("module");
$infomsg = $f->getValue("infomsg");
$cid = $f->getValue("cid");

$module_name = "content";
$tablePrint = new Tableprint();

$contentTypeContent = new View("content_types", $cid);
$langCollection = new Collection("languages");
$languages = $langCollection->getCollection();

$_SESSION["return_url"] = $_SERVER["REQUEST_URI"];
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
                        <?php
                        if ($infomsg != "") {

                            switch ($infomsg) {
                                case "success_create_content":
                                    ?>
                                    <div class="message success close">
                                        <h2>Success!</h2>
                                        <p>Content successfuly added.</p>
                                    </div>
                                    <?php
                                    break;

                                case "success_delete_content":
                                    ?>
                                    <div class="message success close">
                                        <h2>Success!</h2>
                                        <p>Content successfuly deleted.</p>
                                    </div>
                                    <?php
                                    break;

                                case "success_edit_content":
                                    ?>
                                    <div class="message success close">
                                        <h2>Success!</h2>
                                        <p>Content successfuly updated.</p>
                                    </div>
                                    <?php
                                    break;
                            }
                        }
                        ?>
                        <h1><?= $contentTypeContent->title; ?></h1>
                        <?php
                        if ($category_id) {
                            $category = new View("categories", $category_id);
                            echo '<h1>' . $category->title;
                            ?>
                            <a title="Edit category: <?= $category->title; ?>" class="button_table tooltip" href="../module_categories/edit_category.php?id=<?= $category->resource_id; ?>">
                                <span class="ui-icon ui-icon-pencil"></span>
                            </a>
                            </h1>
                            <?php
                        }
                        ?>
                        <!-- Add new contnet form -->
                        <form method="POST" id="add_content" name="add_content" action="work.php">
                            <div id="tabs">
                                <ul>
                                    <?php
                                    foreach ($languages as $key => $language) {
                                        ?>
                                        <li><a href="#tab<?= $language->code; ?>"><?= $language->title; ?></a></li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                                <?php
                                foreach ($languages as $key => $language) {
                                    $lang_code = $language->code;
                                    ?>
                                    <div id="tab<?= $language->code; ?>">
                                        <fieldset>
                                            <legend>Add new <?= $contentTypeContent->title; ?>  for language: <?= $language->title; ?></legend>
                                            <p>
                                                <label for="title">Title</label>
                                                <input type="text" class="lf" name="<?= $language->code; ?>[title]" id="title" />
                                            </p>

                                            <input type="button" class="button save_edit_content" value="Save and edit" /> <input type="submit" class="button" value="Save" /> 
                                        </fieldset>
                                    </div>
                                    <?php
                                }
                                ?>
                                <input type="hidden" name="action" value="add_content" />
                                <input type="hidden" name="content_type_id" value="<?= $cid; ?>" />
                                <input type="hidden" name="edit" id="edit" value="0" />
                            </div>
                        </form>

                        <br clear="all" />

                        <!-- KOPIRAJ OVO GDE TI TREBA -->

                        <p>	
                            <form method="GET" action="index.php" style="width:100%; border-bottom: 1px dotted #CCC; margin-bottom: 20px; padding-bottom: 20px;" class="left_form ">

                                <?php include("search_fields.php"); ?>

                            </form>

                        </p>

                        <p>

                            <input type="text" name="search_input" id="search_input" class="sf right_input" value="" placeholder="Live search..." />
                            <form method="GET" action="index.php" style="float:left">
                                <input type="hidden" name="cid" value="<?= $cid; ?>" />
                                <input style="width: 225px" type="text" name="phrase" value="<?= ($phrase != "") ? $phrase : ''; ?>" placeholder="Search in <?= $contentTypeContent->title; ?>" />
                                <input class="button" type="submit" value="Search" />
                            </form>
                        </p>

                        <br clear="all" />
                        <hr />

                        <p>
                            <?php /* ?>
                              <form method="POST" action="work.php">
                              <input type="text" name="procenat" value="" placeholder="Unsite procenat" />
                              <input class="button" type="submit" value="Povecaj/smanji cenu" />
                              <input type="hidden" name="action" value="povecaj_cenu" />
                              <br /><br /><?php */ ?>
                            <?php
                            $tablePrint->printContentTable($cid, $wholeQuery, $tableCustomFields, $start_from, $imgData->column_name);
                            /*
                              ?>
                              </form> <?php */
                            ?>
                        </p>

                        <!-- END KOPIRAJ OVO GDE TI TREBA -->
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