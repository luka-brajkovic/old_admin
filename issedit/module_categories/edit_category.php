<?php
require("../library/config.php");
$f->checkLogedAdmin("module");
$infomsg = $f->getValue("infomsg");
$rid = $f->getValue("id");

$langCollection = new Collection("languages");
$languages = $langCollection->getCollection();
$module_name = "categories";

$filesTableName = "categories";
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

                        <h1>Edit category</h1>

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
                                foreach ($languages as $key => $language) {

                                    $lang_code = $language->code;
                                    $category = new Collection("categories");
                                    $categoryCollection = $category->getCollection("WHERE resource_id = '$rid' AND lang = '$language->id'");
                                    if ($category->resultCount == 0) {
                                        $categoryDefault = $category->getCollection("WHERE resource_id = '$rid' AND lang = '$currentLanguage'");
                                        $categoryCollection[0] = new View("categories");
                                        $categoryCollection[0]->resource_id = $categoryDefault[0]->resource_id;
                                        $categoryCollection[0]->ordering = $categoryDefault[0]->ordering;
                                        $categoryCollection[0]->parent_id = $categoryDefault[0]->parent_id;
                                        $categoryCollection[0]->level = $categoryDefault[0]->level;
                                        $categoryCollection[0]->content_type_id = $categoryDefault[0]->content_type_id;
                                        $categoryCollection[0]->status = $categoryDefault[0]->status;
                                        $categoryCollection[0]->lang = $language->id;
                                        $categoryCollection[0]->Save();
                                    }

                                    foreach ($categoryCollection as $categorySingle) {
                                        $parent_id = $categorySingle->parent_id;
                                        $resource_id = $categorySingle->resource_id;
                                        $content_type_id = $categorySingle->content_type_id;
                                        $level = $categorySingle->level;
                                        ?>
                                        <div id="tab<?= $language->code; ?>">
                                            <fieldset>
                                                <legend>Edit category for language: <?= $language->title; ?></legend>
                                                <p>
                                                    <label for="title_<?= $language->code; ?>">Title</label>
                                                    <input type="text" class="lf" name="<?= $language->code; ?>[title]" id="title_<?= $language->code; ?>" value="<?= $categorySingle->title; ?>" />
                                                </p>
                                                <p>
                                                    <label for="status_<?= $language->code; ?>">Status</label>
                                                    <select name="status_<?= $language->code; ?>">
                                                        c                      						    				<?php
                                                        if ($categorySingle->status == 1) {
                                                            ?>
                                                            <option <?php if ($categorySingle->status == 1) echo 'selected="selected"' ?> value="1">Active</option>
                                                            <option <?php if ($categorySingle->status == 2) echo 'selected="selected"' ?> value="2">Inactive: this will deactivate all subcategories and content</option>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <option <?php if ($categorySingle->status == 1) echo 'selected="selected"' ?> value="1">Active only this category</option>
                                                            <option <?php if ($categorySingle->status == 2) echo 'selected="selected"' ?> value="2">Inactive</option>
                                                            <?php
                                                        }
                                                        ?>

                                                    </select>
                                                </p>
                                                <?php
                                                $contentsCollection = new Collection("categories_content");
                                                $contents = $contentsCollection->getCollection("WHERE category_resource_id = '$categorySingle->resource_id'");
                                                if ($contentsCollection->resultCount > 0) {
                                                    ?>
                                                    <p>
                                                        <label for="opt_cont_<?= $language->code; ?>">Content option</label>
                                                        <select name="opt_cont_<?= $language->code; ?>">
                                                            <option value="">Choose option...</option>
                                                            <option value="1">Activate contents</option>
                                                            <option value="2">Deactivate contents</option>
                                                        </select>																	
                                                    </p>
                                                    <?php
                                                }
                                                ?>
                                                <p>
                                                    <label for="parent_<?= $language->code; ?>">Change parent category</label>
                                                    <select name = "parent_<?= $language->code; ?>">
                                                        <?php
                                                        $ct = new View("content_types", $categorySingle->content_type_id);
                                                        if ($categorySingle->parent_id == 0)
                                                            echo '<option value="0" selected="selected">' . $ct->title . '</option>';
                                                        else
                                                            echo '<option value="0" >' . $ct->title . '</option>';
                                                        $f->printParentSelectOptions(0, $categorySingle);
                                                        ?>
                                                    </select>											
                                                </p>
                                        <!--<p>
                                            <input type = "checkbox" name = "empty_category" />Empty this category. WARNING: this will delete all subcategories and their contnent!
                                        </p>-->

                                                <?php
                                                $categoriesGlobalFields = new Collection("fields");
                                                $categoriesGlobalFieldsCollection = $categoriesGlobalFields->getCollection("WHERE table_name = 'categories'");

                                                foreach ($categoriesGlobalFieldsCollection as $field) {
                                                    //Get variables
                                                    $columnName = $field->column_name;
                                                    $fieldValue = $categorySingle->$columnName;
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
                                                            $selectTable = new Collection($table);
                                                            $selectTableCollection = $selectTable->getCollection();
                                                            ?>
                                                            <p>
                                                                <label for="<?= $columnName; ?>_<?= $language->code; ?>"><?= $field->title; ?></label>
                                                                <select name="<?= $language->code; ?>[<?= $columnName; ?>]" id="<?= $columnName; ?>_<?= $language->code; ?>" class="w120">
                                                                    <?php
                                                                    $options = explode(",", $defaultValue);
                                                                    if ($categorySingle->$columnName == $option) {
                                                                        $selected = "selected='selected'";
                                                                    } else {
                                                                        $selected = "";
                                                                    }
                                                                    foreach ($selectTableCollection as $option) {
                                                                        echo "<option $selected value='" . $option->$key_field . "'>" . $option->$value_field . "</option>";
                                                                    }
                                                                    ?>
                                                                </select>
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
                                                                if (isset($categorySingle->$columnName) && $categorySingle->$columnName != "" && $categorySingle->$columnName != "0") {
                                                                    $dimensions = new Collection("dimensions");
                                                                    $dimensionCollection = $dimensions->getCollection("WHERE table_name = '$filesTableName'");
                                                                    foreach ($dimensionCollection as $dimension) {
                                                                        $image_name = $categorySingle->$columnName;
                                                                        if (is_file("../../uploads/uploaded_pictures/$filesTableName/$dimension->url/$image_name")) {
                                                                            $rand = rand(0, 999999);
                                                                            echo "<a onclick='return hs.expand(this)' class='highslide' href='/uploads/uploaded_pictures/$filesTableName/$dimension->url/$image_name?$rand'>$dimension->title</a>  | ";
                                                                        }
                                                                    }


                                                                    echo "<a href='work.php?action=remove_image&cat_id=$categorySingle->id&col_name=$columnName&global=1' onclick='return confirm(\'Are you sure you want to remove this image?\');'>Remove image</a>";
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
                                                                if (isset($categorySingle->$columnName) && $categorySingle->$columnName != "" && $categorySingle->$columnName != "0") {
                                                                    $file_name = $categorySingle->$columnName;
                                                                    if (is_file("../../uploads/uploaded_files/$filesTableName/$file_name")) {
                                                                        $rand = rand(0, 999999);
                                                                        echo "<a target='_blank' href='/uploads/uploaded_files/$filesTableName/$file_name?$rand'>View file</a>  | ";
                                                                        echo "<a href='work.php?action=remove_file&cat_id=$categorySingle->id&col_name=$columnName&global=1' onclick='return confirm(\'Are you sure you want to remove this file?\');'>Remove file</a>";
                                                                    }
                                                                }
                                                                ?>
                                                            </p>
                                                            <?php
                                                            break;
                                                    }
                                                }
                                                ?>

                                                <?php
                                                $categoriesFields = new Collection("categories_fields");
                                                $categoriesFieldsCollection = $categoriesFields->getCollection("WHERE content_type_id = '$categorySingle->content_type_id'");

                                                foreach ($categoriesFieldsCollection as $field) {
                                                    //Get variables
                                                    $columnName = $field->column_name;
                                                    $fieldValue = $categorySingle->$columnName;
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
                                                            $selectTable = new Collection($table);
                                                            $selectTableCollection = $selectTable->getCollection();
                                                            ?>
                                                            <p>
                                                                <label for="<?= $columnName; ?>_<?= $language->code; ?>"><?= $field->title; ?></label>
                                                                <select name="<?= $language->code; ?>[<?= $columnName; ?>]" id="<?= $columnName; ?>_<?= $language->code; ?>" class="w120">
                                                                    <?php
                                                                    $options = explode(",", $defaultValue);
                                                                    if ($categorySingle->$columnName == $option) {
                                                                        $selected = "selected='selected'";
                                                                    } else {
                                                                        $selected = "";
                                                                    }
                                                                    foreach ($selectTableCollection as $option) {
                                                                        echo "<option $selected value='" . $option->$key_field . "'>" . $option->$value_field . "</option>";
                                                                    }
                                                                    ?>
                                                                </select>
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
                                                                if (isset($categorySingle->$columnName) && $categorySingle->$columnName != "" && $categorySingle->$columnName != "0") {
                                                                    $dimensions = new Collection("dimensions");
                                                                    $dimensionCollection = $dimensions->getCollection("WHERE table_name = '$filesTableName'");
                                                                    foreach ($dimensionCollection as $dimension) {
                                                                        $image_name = $categorySingle->$columnName;
                                                                        if (is_file("../../uploads/uploaded_pictures/$filesTableName/$dimension->url/$image_name")) {
                                                                            $rand = rand(0, 999999);
                                                                            echo "<a onclick='return hs.expand(this)' class='highslide' href='/uploads/uploaded_pictures/$filesTableName/$dimension->url/$image_name?$rand'>$dimension->title</a>  | ";
                                                                        }
                                                                    }


                                                                    echo "<a href='work.php?action=remove_image&cat_id=$categorySingle->id&col_name=$columnName&global=1' onclick='return confirm(\'Are you sure you want to remove this image?\');'>Remove image</a>";
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
                                                                if (isset($categorySingle->$columnName) && $categorySingle->$columnName != "" && $categorySingle->$columnName != "0") {
                                                                    $file_name = $categorySingle->$columnName;
                                                                    if (is_file("../../uploads/uploaded_files/$filesTableName/$file_name")) {
                                                                        $rand = rand(0, 999999);
                                                                        echo "<a target='_blank' href='/uploads/uploaded_files/$filesTableName/$file_name?$rand'>View file</a>  | ";
                                                                        echo "<a href='work.php?action=remove_file&cat_id=$categorySingle->id&col_name=$columnName&global=0' onclick='return confirm(\'Are you sure you want to remove this file?\');'>Remove file</a>";
                                                                    }
                                                                }
                                                                ?>
                                                            </p>
                                                            <?php
                                                            break;
                                                    }
                                                }



                                                /* INCLUDE FILTERA----------------------- */

                                                include("../module_filters/filters_category.php");
                                                ?>



                                                <input type="submit" class="button" value="Save" />

                                                <input type="submit" class="button" name="Apply" value="Apply" /> 
                                            </fieldset>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                                <input type="hidden" name="resource_id" id="resource_id" value="<?= $rid; ?>" />
                                <input type="hidden" name="content_type_id" id="content_type_id" value="<?= $content_type_id; ?>" />
                                <input type="hidden" name="parent_id" id="parent_id" value="<?= $parent_id; ?>" />
                                <input type="hidden" name="action" value="edit_category" />
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