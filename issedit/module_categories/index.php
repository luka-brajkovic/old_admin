<?php
require("../library/config.php");
$f->checkLogedAdmin("module");
$infomsg = $f->getValue("infomsg");
$cid = $f->getValue("cid");
$parent_id = $f->getValue("parent_id");
if ($parent_id == "") {
    $parent_id = 0;
}

if ($parent_id != 0 && $parent_id == $resource_id) {
    $parentName = $db->getValue("title", "categories", "resource_id", $parent_id);
}


$tablePrint = new Tableprint();
$module_name = "categories";

$langCollection = new Collection("languages");
$languages = $langCollection->getCollection();
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
                                case "success_add_category":
                                    ?>
                                    <div class="message success close">
                                        <h2>Success!</h2>
                                        <p>New category successfuly added.</p>
                                    </div>
                                    <?php
                                    break;

                                case "success_delete_category":
                                    ?>
                                    <div class="message success close">
                                        <h2>Success!</h2>
                                        <p>Category successfuly deleted.</p>
                                    </div>
                                    <?php
                                    break;

                                case "success_edit_category":
                                    ?>
                                    <div class="message success close">
                                        <h2>Success!</h2>
                                        <p>Category successfuly updated.</p>
                                    </div>
                                    <?php
                                    break;
                            }
                        }
                        ?>

                        <h1>Categories <?php if ($parent_id != 0) echo "under $parentName"; ?>
                            <?php
                            //$category = new View("categories", $cid);
                            if ($parent_id != 0) {
                                ?>
                                <a title="Edit category" class="button_table tooltip" href="edit_category.php?id=<?= $parent_id; ?>">
                                    <span class="ui-icon ui-icon-pencil"></span>
                                </a>
                                <?php
                            }
                            ?>
                        </h1>



                        <p>

                            <input type="text" name="search_input" id="search_input" class="sf right_input" onblur="if (this.value == '')
                                        this.value = this.defaultValue;" onfocus="if (this.value == this.defaultValue)
                                                    this.value = '';" value="Live search..." />
                            Category path: <span class="category_path"><?= $f->printCategoryPath($parent_id, $cid); ?></span>
                            <br clear="all" />
                        </p>

                        <hr />
                        <p>
                            <?php $tablePrint->printCategoriesTable($cid, $parent_id); ?>
                        </p>

                        <hr />

                        <form method="POST" id="add_category" name="add_category" action="work.php">
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
                                            <legend>Add new category for language: <?= $language->title; ?></legend>
                                            <p>
                                                <label for="title">Title</label>
                                                <input type="text" class="lf" name="<?= $language->code; ?>[title]" id="title" />
                                            </p>

                                            <input type="hidden" name="action" value="add_category" />
                                            <input type="hidden" name="<?= $language->code; ?>[content_type_id]" value="<?= $cid; ?>" />
                                            <input type="hidden" name="<?= $language->code; ?>[parent_id]" value="<?= $parent_id; ?>" />

                                            <input type="button" class="button save_edit" value="Save and edit" /> <input type="submit" class="button" value="Save" /> 
                                        </fieldset>
                                    </div>
                                    <?php
                                }
                                ?>
                                <input type="hidden" name="edit" id="edit" value="0" />
                            </div>
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