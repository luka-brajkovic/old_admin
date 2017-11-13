<?php
require("../library/config.php");
$f->checkLogedAdmin("module");
$infomsg = $f->getValue("infomsg");
$module_name = "users";

$user_id = $f->getValue("user_id");
$user = new View("users", $user_id);
$filesTableName = "users"
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
                        $status_array = array("1" => "Approved", "0" => "Not approved", "2" => "Banned");
                        ?>
                        <h1>Edit user - <?= $user->fullname; ?></h1>
                        <form method="POST" id="edit_user" name="edit_user" action="work.php" enctype="multipart/form-data">
                            <fieldset>
                                <legend>Edit user</legend>
                                <p>
                                    <label for="fullname">Fullname *</label>
                                    <input type="text" class="lf" value="<?= $user->fullname ?>" name="fullname" id="fullname" required="required" />
                                </p>
                                <p>
                                    <label for="email">Email *</label>
                                    <input type="text" class="mf" name="email" id="email" value="<?= $user->email ?>" required="required"/>
                                </p>
                                <p>
                                    <label for="code">Code</label>
                                    <input type="text" class="lf" name="code" id="code" value="<?= $user->code ?>" />
                                </p>
                                <p>
                                    <label for="password">Password</label>
                                    <input type="password" class="lf" name="password" id="password" value="" />
                                </p>
                                <p>
                                    <label for="conf_password">Confirm password</label>
                                    <input type="password" class="lf" name="conf_password" id="conf_password" value="" />
                                </p>
                                <p>
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="lf">
                                        <?php
                                        for ($i = 0; $i < count($status_array); $i++)
                                            if ($user->status == $i)
                                                echo '<option value="' . $i . '" selected="selected"' . '>' . $status_array[$i] . '</option>';
                                            else
                                                echo '<option value="' . $i . '" >' . $status_array[$i] . '</option>';
                                        ?>
                                    </select>
                                </p>
                                <p>
                                    <label for="fbuser">Facabook</label>
                                    <select name="fbuser" id="fbuser" class="lf">
                                        <option <?php if ($user->fbuser == 1) echo "selected=selected" ?> value="1">Yes</option>
                                        <option <?php if ($user->fbuser == 0) echo "selected=selected" ?> value="0">No</option>
                                    </select>
                                </p>


                                <?php
                                $usersGlobalFields = new Collection("fields");
                                $usersGlobalFieldsCollection = $usersGlobalFields->getCollection("WHERE table_name = 'users'");

                                foreach ($usersGlobalFieldsCollection as $field) {
                                    //Get variables
                                    $columnName = $field->column_name;
                                    $fieldValue = $user->$columnName;
                                    $defaultValue = $field->default_value;

                                    switch ($field->field_type) {
                                        case "text":
                                            ?>
                                            <p>
                                                <label for="<?= $columnName; ?>"><?= $field->title; ?></label>
                                                <input type="text" class="lf" name="<?= $columnName; ?>" id="<?= $columnName; ?>" value="<?= $fieldValue; ?>" />
                                            </p>
                                            <?php
                                            break;

                                        case "textarea":
                                            ?>
                                            <p>
                                                <label for="<?= $columnName; ?>"><?= $field->title; ?></label>
                                                <textarea style="width: 600px; height: 80px;" class="lf" id="<?= $columnName; ?>" name="<?= $columnName; ?>"><?= $fieldValue; ?></textarea>

                                            </p>
                                            <?php
                                            break;

                                        case "wysiwyg":
                                            ?>
                                            <p>
                                                <label for="<?= $columnName; ?>"><?= $field->title; ?></label>
                                                <textarea style="width: 600px; height: 80px;" class="tinymceControl" id="<?= $columnName; ?>" name="<?= $columnName; ?> "><?= $fieldValue; ?></textarea>

                                            </p>
                                            <?php
                                            break;

                                        case "select":
                                            ?>
                                            <p>
                                                <label for="<?= $columnName; ?> "><?= $field->title; ?></label>
                                                <select name="<?= $columnName; ?>" id="<?= $columnName; ?>" class="w120">
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
                                                <label for="<?= $columnName; ?>"><?= $field->title; ?></label>
                                                <select name="<?= $columnName; ?>" id="<?= $columnName; ?>" class="w120">
                                                    <?php
                                                    $options = explode(",", $defaultValue);
                                                    if ($user->$columnName == $option) {
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

                                            $selectedArray = explode(",", $user->$columnName);
                                            ?>
                                            <p>
                                                <label for="<?= $columnName; ?>"><?= $field->title; ?></label>
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
                                                                    <input <?= $checked; ?> type="checkbox" value="<?= trim($options[$k]); ?>" name="<?= $columnName; ?>[]" id="<?= $columnName; ?>_<?= $f->generateUrlFromText($options[$k]); ?>"><?= trim($options[$k]); ?>
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
                                                <label for="<?= $columnName; ?>"><?= $field->title; ?></label>
                                                <?php
                                                $k = 0;
                                                for ($i = 0; $i < $colNumber; $i++) {
                                                    ?>
                                                    <div class="inpcol">
                                                        <?php
                                                        for ($j = 0; $j < $numPerColumn; $j++) {
                                                            if (isset($options[$k])) {
                                                                if ($user->$columnName == $options[$k]) {
                                                                    $checked = "checked='checked'";
                                                                } else {
                                                                    $checked = "";
                                                                }
                                                                ?>
                                                                <p>
                                                                    <input <?= $checked; ?> type="radio" value="<?= trim($options[$k]); ?>" name="<?= $columnName; ?>" id="<?= $columnName; ?> <?= $f->generateUrlFromText($options[$k]); ?>"><?= trim($options[$k]); ?>
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
                                                <label for="<?= $columnName; ?>"><?= $field->title; ?></label>
                                                <input type="text" class="lf datepicker" name="<?= $columnName; ?>" id="<?= $columnName; ?>" value="<?= $fieldValue; ?>" />
                                            </p>
                                            <?php
                                            break;

                                        case "colorpicker":
                                            ?>
                                            <p>
                                                <label for="<?= $columnName; ?> "><?= $field->title; ?></label>
                                                <input type="text" size="6" class="lf colorpickerHolder" name="<?= $columnName; ?>" id="<?= $columnName; ?>" value="<?= $fieldValue; ?>" />
                                            </p>
                                            <?php
                                            break;

                                        case "image":
                                            ?>
                                            <p>
                                                <label for="<?= $columnName; ?> "><?= $field->title; ?></label>
                                                <input type="file" class="lf" name="<?= $columnName; ?>" id="<?= $columnName; ?>" />
                                                <?php
                                                if (isset($user->$columnName) && $user->$columnName != "" && $user->$columnName != "0") {
                                                    $dimensions = new Collection("dimensions");
                                                    $dimensionCollection = $dimensions->getCollection("WHERE table_name = '$filesTableName'");
                                                    foreach ($dimensionCollection as $dimension) {
                                                        $image_name = $user->$columnName;
                                                        if (is_file("../../uploads/uploaded_pictures/$filesTableName/$dimension->url/$image_name")) {
                                                            $rand = rand(0, 999999);
                                                            echo "<a onclick='return hs.expand(this)' class='highslide' href='/uploads/uploaded_pictures/$filesTableName/$dimension->url/$image_name?$rand'>$dimension->title</a>  | ";
                                                        }
                                                    }


                                                    echo "<a href='work.php?action=remove_image&cat_id=$user->id&col_name=$columnName&global=1' onclick='return confirm(\'Are you sure you want to remove this image?\');'>Remove image</a>";
                                                }
                                                ?>
                                            </p>
                                            <?php
                                            break;

                                        case "file":
                                            ?>
                                            <p>
                                                <label for="<?= $columnName; ?>"><?= $field->title; ?></label>
                                                <input type="file" class="lf" name="<?= $columnName; ?>" id="<?= $columnName; ?>" />
                                                <?php
                                                if (isset($user->$columnName) && $user->$columnName != "" && $user->$columnName != "0") {
                                                    $file_name = $user->$columnName;
                                                    if (is_file("../../uploads/uploaded_files/$filesTableName/$file_name")) {
                                                        $rand = rand(0, 999999);
                                                        echo "<a target='_blank' href='/uploads/uploaded_files/$filesTableName/$file_name?$rand'>View file</a>  | ";
                                                        echo "<a href='work.php?action=remove_file&cat_id=$user->id&col_name=$columnName&global=1' onclick='return confirm(\'Are you sure you want to remove this file?\');'>Remove file</a>";
                                                    }
                                                }
                                                ?>
                                            </p>
                                            <?php
                                            break;
                                    }
                                }
                                ?>
                                <input type="hidden" id="action" name="action" value="edit_user" />
                                <input type="hidden" id="user_id" name="user_id" value="<?= $user_id ?>" />
                                <input type="submit" class="button" value="Save" /> 
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