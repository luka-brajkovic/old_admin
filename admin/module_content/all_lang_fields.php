<?php
if (count($contentFieldsCollection) > 0) {
    ?>

    <fieldset>
        <?php
        foreach ($contentFieldsCollection as $field) {
            //Get variables
            $columnName = $field->column_name;
            $fieldValue = $item->$columnName;
            $defaultValue = $field->default_value;

            switch ($field->field_type) {
                case "text":
                    ?>
                    <p>
                        <label for="<?= $columnName; ?>_all"><?= $field->title; ?></label>
                        <input type="text" class="lf" name="all[<?= $columnName; ?>]" id="<?= $columnName; ?>_all" value="<?= $fieldValue; ?>" />
                    </p>
                    <?php
                    break;

                case "textarea":
                    ?>
                    <p>
                        <label for="<?= $columnName; ?>_all"><?= $field->title; ?></label>
                        <textarea style="width: 600px; height: 80px;" class="lf" id="<?= $columnName; ?>_all" name="all[<?= $columnName; ?>]"><?= $fieldValue; ?></textarea>

                    </p>
                    <?php
                    break;

                case "wysiwyg":
                    ?>
                    <p>
                        <label for="<?= $columnName; ?>_all"><?= $field->title; ?></label>
                        <textarea style="width: 600px; height: 80px;" class="tinymceControl" id="<?= $columnName; ?>_all" name="all[<?= $columnName; ?>]"><?= $fieldValue; ?></textarea>

                    </p>
                    <?php
                    break;

                case "select":
                    ?>
                    <p>
                        <label for="<?= $columnName; ?>_all"><?= $field->title; ?></label>
                        <select name="all[<?= $columnName; ?>]" id="<?= $columnName; ?>_all" class="w120">
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
                        <label for="<?= $columnName; ?>_all"><?= $field->title; ?></label>
                        <select name="all[<?= $columnName; ?>]" id="<?= $columnName; ?>_all" class="w120">
                            <?php
                            $beenThere = array();
                            $options = explode(",", $defaultValue);

                            foreach ($selectTableCollection as $option) {
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
                        <label for="<?= $columnName; ?>_all"><?= $field->title; ?></label>
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
                    <label for="<?= $columnName; ?>_all"><?= $field->title; ?></label>
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
                    <label for="<?= $columnName; ?>_all"><?= $field->title; ?></label>
                    <input type="text" class="lf datepicker" name="<?= $language->code; ?>[<?= $columnName; ?>]" id="<?= $columnName; ?>_<?= $language->code; ?>" value="<?= $fieldValue; ?>" />
                </p>
                <?php
                break;

            case "colorpicker":
                ?>
                <p>
                    <label for="<?= $columnName; ?>_all"><?= $field->title; ?></label>
                    <input type="text" size="6" class="lf colorpickerHolder" name="<?= $language->code; ?>[<?= $columnName; ?>]" id="<?= $columnName; ?>_<?= $language->code; ?>" value="<?= $fieldValue; ?>" />
                </p>
                <?php
                break;

            case "image":
                ?>
                <p>
                    <label for="<?= $columnName; ?>_all"><?= $field->title; ?></label>
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


                        echo "<a href='work.php?action=remove_image&cat_id=$categorySingle->id&col_name=$columnName&global=1' onclick='return confirm(\'Are you sure you want to remove this image?\');'>Remove image</a>";
                    }
                    ?>
                </p>
                <?php
                break;

            case "file":
                ?>
                <p>
                    <label for="<?= $columnName; ?>_all"><?= $field->title; ?></label>
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
                    <input type="file" class="lf" name="<?php echo $columnName . "_" . $language->code; ?>" id="<?= $columnName; ?>_all" />
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
    </fieldset>
    <?php
}
?>