<?php

require("../library/config.php");

$resizeImage = new ResizeImage();
$values = $f->getRequestValues();
$action = $values['action'];

switch ($action) {

    case "add_user":

        $user = new View("users");
        $user->extend($_POST);
        $user->date_added = date("Y-m-d H:i:s");
        $user->Save();
        $f->redirect("edit_user.php?user_id=" . $user->id);
        break;

    case "edit_user":

        $id = $f->getValue("user_id");
        $newPass = $f->getValue("password");
        $confPass = $f->getValue("conf_password");
        if ($confPass != $newPass || (strlen($newPass) < 4 && $newPass != ""))
            $f->redirect("edit_user.php?user_id=" . $id);

        $user = new View("users", $id);
        $oldPass = $user->password;

        $user->extend($_POST);

        if ($user->password == "")
            $user->password = $oldPass;
        else
            $user->password = md5($user->password);

        $usersGlobalFields = new Collection("fields");
        $usersGlobalFieldsCollection = $usersGlobalFields->getCollection("WHERE table_name = 'users'");
        $filesTableName = "users";

        foreach ($usersGlobalFieldsCollection as $field) {
            $columnName = $field->column_name;
            switch ($field->field_type) {
                case "text":
                case "textarea":
                case "wysiwyg":
                case "select":
                case "select_table":
                case "datepicker":
                case "colorpicker":
                case "radio":
                    $user->$columnName = $values[$columnName];
                    break;
                case "checkbox":
                    if (isset($values[$columnName]) && count($values[$columnName]) > 0) {
                        $user->$columnName = implode(",", $values[$columnName]);
                    }
                    break;

                case "image":
                    $fieldName = $columnName;
                    if (isset($_FILES[$fieldName]) && $_FILES[$fieldName]['size'] > 0) {
                        $rand = rand(0, 999999);
                        $old_image_name = $user->$columnName;
                        if (is_file("../../uploads/uploaded_pictures/$filesTableName/$old_image_name")) {
                            unlink("../../uploads/uploaded_pictures/$filesTableName/$old_image_name");
                        }

                        $image_name = $f->fileUpload($fieldName, "../../uploads/uploaded_pictures/$filesTableName/", "$user->fullname-$user->id-$rand", PIC_EXT);
                        $user->$columnName = $image_name;

                        $arr = explode(".", $image_name);
                        $num_of_arg = count($arr) - 1;
                        $image_type = $arr[$num_of_arg];

                        $dimensions = new Collection("dimensions");
                        $dimensionCollection = $dimensions->getCollection("WHERE table_name = '$filesTableName'");
                        foreach ($dimensionCollection as $dimension) {
                            $targ_w = $dimension->width;
                            $targ_h = $dimension->height;
                            $url_dim = $dimension->url;

                            if (is_file("../../uploads/uploaded_pictures/$filesTableName/$url_dim/$old_image_name")) {
                                unlink("../../uploads/uploaded_pictures/$filesTableName/$url_dim/$old_image_name");
                            }

                            if ($dimension->crop_resize == 1) {
                                $f->newCropImage("../../uploads/uploaded_pictures/$filesTableName/$image_name", $targ_w, $targ_h, $image_type, "../../uploads/uploaded_pictures/$filesTableName/$url_dim/", $image_name);
                            } else {
                                $resizeImage->load("../../uploads/uploaded_pictures/$filesTableName/$image_name");
                                $resizeImage->resizeToWidth($targ_w);
                                $resizeImage->save("../../uploads/uploaded_pictures/$filesTableName/$url_dim/$image_name");
                            }
                        }

                        unlink("../../uploads/uploaded_pictures/$filesTableName/$image_name");
                    }
                    break;

                case "file":
                    $fieldName = $columnName;
                    if (isset($_FILES[$fieldName]) && $_FILES[$fieldName]['size'] > 0) {
                        $rand = rand(0, 999999);
                        $old_file_name = $user->$columnName;
                        if (is_file("../../uploads/uploaded_files/$filesTableName/$old_file_name")) {
                            unlink("../../uploads/uploaded_files/$filesTableName/$old_file_name");
                        }

                        $file_name = $f->fileUpload($fieldName, "../../uploads/uploaded_files/$filesTableName/", "$user->fullname-$user->id-$rand", FILE_EXT);
                        $user->$columnName = $file_name;
                    }
                    break;
            }
        }

        $user->Save();
        $f->redirect("index.php?infomsg=success_edit_user");

        break;

    case "delete_user":

        $id = $f->getValue("user_id");

        foreach ($usersGlobalFieldsCollection as $field) {
            if ($field->field_type == "image") {
                $columnName = $field->column_name;
                if (is_file("../../uploads/uploaded_pictures/users/$user->$columnName")) {
                    unlink("../../uploads/uploaded_pictures/users/$user->$columnName");
                }
            }

            if ($field->field_type == "file") {
                $columnName = $field->column_name;
                if (is_file("../../uploads/uploaded_files/users/$user->$columnName")) {
                    unlink("../../uploads/uploaded_files/users/$user->$columnName");
                }
            }
        }

        $user = new View("users", $id);
        $user->Remove();
        $f->redirect("index.php?infomsg=success_delete_user");

        break;
}
?>