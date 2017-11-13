<?php
require("../library/config.php");
$f->checkLogedAdmin("module");
$infomsg = $f->getValue("infomsg");
$rid = $f->getValue("rid");
$cid = $f->getValue("cid");

$module_name = "content";

$contentTypeEdit = new View("content_types", $cid);

$langCollection = new Collection("languages");
$languages = $langCollection->getCollection();

$filesTableName = $contentTypeEdit->table_name;
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

                        <h1>Edit <?= $contentTypeEdit->title; ?></h1>

                        <form method="POST" id="edit_category" name="edit_category" action="work.php" enctype="multipart/form-data">
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
                                $contentTypeEditFields = new Collection("content_type_fields");
                                foreach ($languages as $key => $language) {

                                    $lang_code = $language->code;
                                    $content = new Collection($filesTableName);
                                    $contentCollection = $content->getCollection("WHERE resource_id = '$rid' AND lang = '$language->id'");

                                    if ($content->resultCount == 0) {
                                        $contentDefault = $content->getCollection("WHERE resource_id = '$rid' AND lang = '$currentLanguage'");
                                        $contentCollection[0] = new View("$filesTableName");
                                        $contentCollection[0]->resource_id = $contentDefault[0]->resource_id;
                                        $contentCollection[0]->ordering = $contentDefault[0]->ordering;
                                        $contentCollection[0]->system_date = $contentDefault[0]->system_date;
                                        $contentCollection[0]->status = $contentDefault[0]->status;
                                        $contentCollection[0]->lang = $language->id;
                                        $contentCollection[0]->Save();
                                    }


                                    foreach ($contentCollection as $item) {
                                        ?>
                                        <div id="tab<?= $language->code; ?>">
                                            <fieldset>
                                                <legend>Edit <?= $contentTypeEdit->title; ?> for language: <?= $language->title; ?></legend>
                                                <p>
                                                    <label for="title_<?= $language->code; ?>">Title</label>
                                                    <input type="text" class="lf" name="<?= $language->code; ?>[title]" id="title_<?= $language->code; ?>" value="<?= htmlspecialchars_decode($item->title); ?>" />
                                                </p>

                                                <p>
                                                    <label for="status_<?= $language->code; ?>">Status</label>
                                                    <select name="<?= $language->code; ?>[status]" id="status_<?= $language->code; ?>" class="w120">
                                                        <option value="2" <?php if ($item->status == 2) echo 'selected="selected"'; ?>>Inactive</option>
                                                        <option value="1" <?php if ($item->status == 1) echo 'selected="selected"'; ?>>Active</option>
                                                        <?php /*
                                                          <option value="3" <?php if ($item->status == 3) echo 'selected="selected"'; ?>>Draft</option>
                                                          <option value="4" <?php if ($item->status == 4) echo 'selected="selected"'; ?>>Trash</option>
                                                         * 
                                                         */ ?>
                                                    </select>
                                                </p>

                                                <?php
                                                $contentFieldsCollection = $contentTypeEditFields->getCollection("WHERE content_type_id = '$cid' and all_languages != 1 order by ordering");

                                                foreach ($contentFieldsCollection as $field) {
                                                    //Get variables
                                                    $columnName = $field->column_name;
                                                    $fieldValue = $item->$columnName;
                                                    $defaultValue = $field->default_value;

                                                    switch ($field->field_type) {
                                                        case "text":
                                                            ?>
                                                            <p>
                                                                <label for="<?= $columnName; ?>_<?= $language->code; ?>"><?= $field->title; ?></label>
                                                                <input type="text" class="lf" name="<?= $language->code; ?>[<?= $columnName; ?>]" id="<?= $columnName; ?>_<?= $language->code; ?>" value="<?= $fieldValue; ?>" />
                                                            </p>
                                                            <?php
                                                            break;

                                                        case "textarea":
                                                            ?>
                                                            <p>
                                                                <label for="<?= $columnName; ?>_<?= $language->code; ?>"><?= $field->title; ?></label>
                                                                <textarea style="width: 600px; height: 80px;" class="lf" id="<?= $columnName; ?>_<?= $language->code; ?>" name="<?= $language->code; ?>[<?= $columnName; ?>]"><?= $fieldValue; ?></textarea>
                                                                <?php
                                                                if ($columnName == "odgovor" && $item->odgovor != '' && $cid == 81) {
                                                                    $time = date('Y-m-d H:i', strtotime('+2 hour', strtotime($item->vreme_odgovora)));
                                                                    echo $f->makeFancyDate($time);
                                                                }
                                                                ?>
                                                                <?php
                                                                if ($columnName == "pitanje" && $item->pitanje != '' && $cid == 81) {
                                                                    $time = date('Y-m-d H:i', strtotime('+2 hour', strtotime($item->system_date)));
                                                                    echo $f->makeFancyDate($time) . "<br>";
                                                                }
                                                                ?>
                                                            </p>
                                                            <?php
                                                            break;

                                                        case "wysiwyg":
                                                            ?>
                                                            <p>
                                                                <label for="<?= $columnName; ?>_<?= $language->code; ?>"><?= $field->title; ?></label>
                                                                <textarea style="width: 600px; height: 80px;" class="tinymceControl" id="<?= $columnName; ?>_<?= $language->code; ?>" name="<?= $language->code; ?>[<?= $columnName; ?>]"><?= $fieldValue; ?></textarea>

                                                            </p>
                                                            <?php
                                                            break;

                                                        case "select":
                                                            ?>
                                                            <p>
                                                                <label for="<?= $columnName; ?>_<?= $language->code; ?>"><?= $field->title; ?></label>
                                                                <select name="<?= $language->code; ?>[<?= $columnName; ?>]" id="<?= $columnName; ?>_<?= $language->code; ?>" class="w120">
                                                                    <?php
                                                                    $options = explode(",", $defaultValue);
                                                                    foreach ($options as $option) {
                                                                        $option = trim($option);
                                                                        if ($fieldValue == $option) {
                                                                            $selected = "selected='selected'";
                                                                        } else {
                                                                            $selected = "";
                                                                        }
                                                                        echo "<option $selected value='" . $option . "'>" . $option . "</option>";
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </p>
                                                            <?php
                                                            break;

                                                        case "select_table":
                                                            list($table, $key_field, $value_field) = explode(",", $defaultValue, 3);
                                                            $query = mysqli_query($conn,"SELECT * FROM $table WHERE id !='' ");
                                                            ?>
                                                            <p>
                                                                <label for="<?= $columnName; ?>_<?= $language->code; ?>"><?= $field->title; ?></label>
                                                                <?php
                                                                if ($cid == '81' && $columnName == 'proizvod') {
                                                                    $query = mysqli_query($conn,"SELECT title FROM _content_products WHERE resource_id = '$item->proizvod' LIMIT 1");
                                                                    $optiTelefon = mysqli_fetch_object($query);

                                                                    echo $optiTelefon->title;
                                                                    ?>
                                                                    <input type='hidden' name="<?= $language->code; ?>[<?= $columnName; ?>]" id="<?= $columnName; ?>_<?= $language->code; ?>" value="<?= $item->proizvod; ?>" />  
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <select name="<?= $language->code; ?>[<?= $columnName; ?>]" id="<?= $columnName; ?>_<?= $language->code; ?>" class="w120">
                                                                        <?php
                                                                        $beenThere = array();
                                                                        $options = explode(",", $defaultValue);
                                                                        while ($option = mysqli_fetch_object($query)) {
                                                                            if ($item->$columnName == $option->$key_field) {
                                                                                $selected = "selected='selected'";
                                                                            } else {
                                                                                $selected = "";
                                                                            }
                                                                            if (!in_array($option->$key_field, $beenThere)) {
                                                                                echo "<option $selected value='" . $option->$key_field . "'>" . $option->$value_field . "</option>";
                                                                                $beenThere[] = $option->$key_field;
                                                                            }
                                                                        }
                                                                        $beenThere = array();
                                                                        ?>
                                                                    </select>
                                                                <?php } ?>
                                                            </p>
                                                            <?php
                                                            break;

                                                        case "checkbox":
                                                            $options = explode(",", $defaultValue);
                                                            $numOptions = count($options);
                                                            $numPerColumn = ceil($numOptions / 5);
                                                            if ($numOptions < 5) {
                                                                $colNumber = $numOptions;
                                                            } else {
                                                                $colNumber = 5;
                                                            }

                                                            $selectedArray = explode(",", $categorySingle->$columnName);
                                                            ?>
                                                            <p>
                                                                <label for="<?= $columnName; ?>_<?= $language->code; ?>"><?= $field->title; ?></label>
                                                                <?php
                                                                $k = 0;
                                                                for ($i = 0; $i < $colNumber; $i++) {
                                                                    ?>
                                                                    <div class="inpcol">
                                                                        <?php
                                                                        for ($j = 0; $j < $numPerColumn; $j++) {
                                                                            if (isset($options[$k])) {
                                                                                if (in_array($options[$k], $selectedArray)) {
                                                                                    $checked = "checked='checked'";
                                                                                } else {
                                                                                    $checked = "";
                                                                                }
                                                                                ?>
                                                                                <p>
                                                                                    <input <?= $checked; ?> type="checkbox" value="<?= trim($options[$k]); ?>" name="<?= $language->code; ?>[<?= $columnName; ?>][]" id="<?= $columnName; ?>_<?= $language->code; ?>_<?= $f->generateUrlFromText($options[$k]); ?>"><?= trim($options[$k]); ?>
                                                                                </p>
                                                                                <?php
                                                                                $k++;
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </p>
                                                            <?php
                                                            break;

                                                        case "radio":
                                                            $options = explode(",", $defaultValue);
                                                            $numOptions = count($options);
                                                            $numPerColumn = ceil($numOptions / 5);
                                                            if ($numOptions < 5) {
                                                                $colNumber = $numOptions;
                                                            } else {
                                                                $colNumber = 5;
                                                            }
                                                            ?>
                                                            <p>
                                                                <label for="<?= $columnName; ?>_<?= $language->code; ?>"><?= $field->title; ?></label>
                                                                <?php
                                                                $k = 0;
                                                                for ($i = 0; $i < $colNumber; $i++) {
                                                                    ?>
                                                                    <div class="inpcol">
                                                                        <?php
                                                                        for ($j = 0; $j < $numPerColumn; $j++) {
                                                                            if (isset($options[$k])) {
                                                                                if ($categorySingle->$columnName == $options[$k]) {
                                                                                    $checked = "checked='checked'";
                                                                                } else {
                                                                                    $checked = "";
                                                                                }
                                                                                ?>
                                                                                <p>
                                                                                    <input <?= $checked; ?> type="radio" value="<?= trim($options[$k]); ?>" name="<?= $language->code; ?>[<?= $columnName; ?>]" id="<?= $columnName; ?>_<?= $language->code; ?>_<?= $f->generateUrlFromText($options[$k]); ?>"><?= trim($options[$k]); ?>
                                                                                </p>
                                                                                <?php
                                                                                $k++;
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </p>
                                                            <?php
                                                            break;

                                                        case "datepicker":
                                                            ?>
                                                            <p>
                                                                <label for="<?= $columnName; ?>_<?= $language->code; ?>"><?= $field->title; ?></label>
                                                                <input type="text" class="lf datepicker" name="<?= $language->code; ?>[<?= $columnName; ?>]" id="<?= $columnName; ?>_<?= $language->code; ?>" value="<?= $fieldValue; ?>" />
                                                            </p>
                                                            <?php
                                                            break;

                                                        case "colorpicker":
                                                            ?>
                                                            <p>
                                                                <label for="<?= $columnName; ?>_<?= $language->code; ?>"><?= $field->title; ?></label>
                                                                <input type="text" size="6" class="lf colorpickerHolder" name="<?= $language->code; ?>[<?= $columnName; ?>]" id="<?= $columnName; ?>_<?= $language->code; ?>" value="<?= $fieldValue; ?>" />
                                                            </p>
                                                            <?php
                                                            break;

                                                        case "image":
                                                            ?>
                                                            <p>
                                                                <label for="<?= $columnName; ?>_<?= $language->code; ?>"><?= $field->title; ?></label>
                                                                <input type="file" class="lf" name="<?= $columnName . "_" . $language->code; ?>" id="<?= $columnName; ?>_<?= $language->code; ?>" />
                                                                <?php
                                                                if (isset($item->$columnName) && $item->$columnName != "" && $item->$columnName != "0") {
                                                                    $dimensions = new Collection("content_type_dimensions");
                                                                    $dimensionCollection = $dimensions->getCollection("WHERE content_type_id = '$cid'");
                                                                    foreach ($dimensionCollection as $dimension) {
                                                                        $image_name = $item->$columnName;
                                                                        if (is_file("../../uploads/uploaded_pictures/$filesTableName/$dimension->url/$image_name")) {
                                                                            $rand = rand(0, 999999);
                                                                            echo "<a onclick='return hs.expand(this)' class='highslide' href='/uploads/uploaded_pictures/$filesTableName/$dimension->url/$image_name?$rand'>$dimension->title</a>  | ";
                                                                        }
                                                                    }


                                                                    echo "<a href='work.php?action=remove_image&resource_id=$item->resource_id&col_name=$columnName&cid=$cid' onclick='return confirm(\'Are you sure you want to remove this image?\');'>Remove image</a>";
                                                                }
                                                                ?>
                                                            </p>
                                                            <?php
                                                            break;

                                                        case "file":
                                                            ?>
                                                            <p>
                                                                <label for="<?= $columnName; ?>_<?= $language->code; ?>"><?= $field->title; ?></label>
                                                                <input type="file" class="lf" name="<?= $columnName . "_" . $language->code; ?>" id="<?= $columnName; ?>_<?= $language->code; ?>" />
                                                                <?php
                                                                if (isset($item->$columnName) && $item->$columnName != "" && $item->$columnName != "0") {
                                                                    $file_name = $item->$columnName;
                                                                    if (is_file("../../uploads/uploaded_files/$filesTableName/$file_name")) {
                                                                        $rand = rand(0, 999999);
                                                                        echo "<a target='_blank' href='/uploads/uploaded_files/$filesTableName/$file_name?$rand'>View file</a>  | ";
                                                                        echo "<a href='work.php?action=remove_file&rid=$categorySingle->id&col_name=$columnName&cid=$cid' onclick='return confirm(\'Are you sure you want to remove this file?\');'>Remove file</a>";
                                                                    }
                                                                }
                                                                ?>
                                                            </p>
                                                            <?php
                                                            break;

                                                        case "gallery":
                                                            ?>
                                                            <p>
                                                                <label for="<?= $columnName; ?>_<?php echo $language->code; ?>"><?= $field->title; ?></label>
                                                                <input type="file" class="lf" name="<?php echo $columnName . "_" . $language->code; ?>" id="<?= $columnName; ?>_<?php echo $language->code; ?>" />
                                                                <?php
                                                                if (isset($item->$columnName) && $item->$columnName != "" && $item->$columnName != "0") {
                                                                    $file_name = $item->$columnName;
                                                                    if (is_file("../../uploads/uploaded_files/$filesTableName/$file_name")) {
                                                                        $rand = rand(0, 999999);
                                                                        echo "<a target='_blank' href='/uploads/uploaded_files/$filesTableName/$file_name?$rand'>View file</a>  | ";
                                                                        echo "<a href='work.php?action=remove_file&rid=$categorySingle->id&col_name=$columnName&cid=$cid' onclick='return confirm(\'Are you sure you want to remove this file?\');'>Remove file</a>";
                                                                    }
                                                                }
                                                                if ($item->$columnName != '') {
                                                                    $value = $item->$columnName;
                                                                    ?>
                                                                    <div id="galerija">
                                                                        <?php
                                                                        $dir = "../../" . substr($value, 1) . "thumbs";
                                                                        if ($handle = opendir($dir)) {
                                                                            while (false !== ($entry = readdir($handle))) {
                                                                                if ($entry != "." && $entry != "..") {
                                                                                    ?>
                                                                                    <div style="text-align:center; width:120px; float:left; ">
                                                                                        <a title="" data-fancybox-group="gallery" href="<?php echo $item->$columnName . "bigs/$entry"; ?>" class="fancybox">
                                                                                            <img src="<?php echo $item->$columnName . "thumbs/$entry"; ?>"/>

                                                                                        </a>
                                                                                        <a href="javascript:void(0);" onclick="obrisi_sliku('<?php echo $dir . "/" . $entry; ?>')">Obrisi sliku</a>
                                                                                    </div>
                                                                                    <?php
                                                                                }
                                                                            }
                                                                            closedir($handle);
                                                                        }
                                                                        ?>
                                                                        <div style="clear:both"></div>	
                                                                    </div>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </p>
                                                            <?php
                                                            break;
                                                    }
                                                }
                                                ?>
                                                <?php
                                                include('../../admin/module_filters/edit_filters.php');
                                                ?>                        						    

                                            </fieldset>
                                        </div>
                                        <?php
                                    }
                                }

                                //All languages same
                                $contentFieldsCollection = $contentTypeEditFields->getCollection("WHERE content_type_id = '$cid' and all_languages = 1 order by ordering");
                                include('all_lang_fields.php');
                                ?>

                                <?php
                                if ($contentTypeEdit->category_type == 1) {
                                    ?>
                                    <fieldset style='margin:0 15px 15px 15px;'>
                                        <legend>Category</legend>
                                        <?php
                                        $selectedCategory = $db->getValue("category_resource_id", "categories_content", "content_resource_id", $rid);
                                        $depth = 4;

                                        $fields = array("title", "resource_id");
                                        $selectsCat = "";
                                        $joinsCat = array();
                                        $ordersCat = array();
                                        $thisValues = array();
                                        for ($i = 1; $i <= $depth; $i++) {
                                            foreach ($fields as $field) {
                                                array_push($thisValues, " c" . $i . "." . $field . " as cat_" . $i . "_" . $field . " ");
                                            }
                                        }
                                        for ($i = 2; $i <= $depth; $i++) {
                                            array_push($joinsCat, "categories AS c" . $i . " ON c" . ($i) . ".parent_id = c" . ($i - 1) . ".resource_id");
                                        }
                                        for ($i = 2; $i <= $depth; $i++) {
                                            array_push($ordersCat, " cat_" . $i . "_title ");
                                        }
                                        $selectsCat .= implode(" , ", $thisValues);
                                        $joinsCat = " LEFT JOIN " . implode(" LEFT JOIN ", $joinsCat);
                                        $ordersCat = " ORDER BY " . implode(" , ", $ordersCat);

                                        $catsQ = mysqli_query($conn,"SELECT $selectsCat
                                        FROM categories AS c1 
                                        " . $joinsCat . "
                                        WHERE c1.parent_id = 0 AND c1.lang = 1 ");

                                        for ($i = 1; $i <= $depth; $i++) {
                                            ${"item_resource_id_" . $i} = "";
                                        }
                                        ?>
                                        <select name='categorySelected' id='categorySelected' required="">
                                            <option value="" disabled >All Categories</option>
                                            <?php
                                            $proso = array();
                                            while ($dataCat = mysqli_fetch_object($catsQ)) {
                                                for ($i = 1; $i <= $depth; $i++) {
                                                    ${"titlePrefix" . $i} = "cat_" . $i . "_title";
                                                    ${"prefix" . $i} = "cat_" . $i . "_resource_id";
                                                    $x = $i + 1;
                                                    ${"prefix" . $x} = "cat_" . $x . "_resource_id";
                                                    if (${"item_resource_id_" . $i} != $dataCat->${"prefix" . $i} && $dataCat->${"prefix" . $i} != '') {
                                                        if (!in_array($dataCat->${"prefix" . $i}, $proso)) {
                                                            if ($dataCat->${"prefix" . $x} != '') {
                                                                echo "<option disabled value=\"" . $dataCat->${"prefix" . $i} . "\">";
                                                            } else {
                                                                if ($selectedCategory == $dataCat->${"prefix" . $i}) {
                                                                    echo "<option selected='selected' value=\"" . $dataCat->${"prefix" . $i} . "\"> ";
                                                                } else {
                                                                    echo "<option  value=\"" . $dataCat->${"prefix" . $i} . "\"> ";
                                                                }
                                                            }
                                                            array_push($proso, $dataCat->${"prefix" . $i});
                                                        }
                                                        for ($j = 1; $j < $i; $j++) {
                                                            echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                                                        }
                                                        echo $dataCat->${"titlePrefix" . $i} . "</option>";
                                                        ${"item_resource_id_" . $i} = $dataCat->${"prefix" . $i};
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </fieldset>										
                                    <?php
                                } else if ($contentTypeEdit->category_type == 2) {
                                    ?>
                                    <fieldset>
                                        <legend>Categories</legend>        						      
                                        <?php
                                        $resCategories = new Collection("categories_content");
                                        $resCategoriesCollection = $resCategories->getCollection("WHERE content_resource_id = '$rid'");
                                        $selectedArray = array();
                                        foreach ($resCategoriesCollection as $resCat) {
                                            $selectedArray[] = $resCat->category_resource_id;
                                        }
                                        echo "<div class='multicategories'>";
                                        $f->getMultiCategories($cid, $selectedArray);
                                        echo "</div>";
                                        ?>
                                    </fieldset>
                                <?php } ?>

                                <input type="submit" name="save" id="save" class="button" value="Save" />
                                <input type="submit" name="apply" id="apply" class="button" value="Apply" />

                                <input type="hidden" name="resource_id" id="resource_id" value="<?= $rid; ?>" />
                                <input type="hidden" name="content_type_id" id="content_type_id" value="<?= $cid; ?>" />
                                <input type="hidden" name="action" value="edit_content" />
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