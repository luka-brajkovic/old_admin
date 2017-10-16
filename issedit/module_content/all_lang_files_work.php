<?php

$languageValues = $f->getValue('all');
foreach ($languages as $key => $language) {
    $lang_code = $language->code;

    $contentCollection = $content->getCollection("WHERE resource_id = '$resource_id' AND lang = '$language->id'");
    $languageValues = $f->getValue('all');

    foreach ($contentCollection as $item) {

        $contentTypeFieldsCollection = $contentTypeFields->getCollection("WHERE content_type_id = '$content_type_id' and all_languages = 1");

        foreach ($contentTypeFieldsCollection as $field) {
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
                    $item->$columnName = $languageValues[$columnName];
                    break;

                case "checkbox":
                    if (isset($languageValues[$columnName]) && count($languageValues[$columnName]) > 0) {
                        $item->$columnName = implode(",", $languageValues[$columnName]);
                    }
                    break;

                case "image":
                    $fieldName = $columnName . "_" . $lang_code;
                    if (isset($_FILES[$fieldName]) && $_FILES[$fieldName]['size'] > 0) {
                        $rand = rand(0, 999999);
                        $old_image_name = $item->$columnName;
                        if (is_file("../../uploads/uploaded_pictures/$filesTableName/$old_image_name")) {
                            unlink("../../uploads/uploaded_pictures/$filesTableName/$old_image_name");
                        }

                        $image_name = $f->fileUpload($fieldName, "../../uploads/uploaded_pictures/$filesTableName/", "$item->url-$item->resource_id-$rand", PIC_EXT);
                        $item->$columnName = $image_name;

                        $arr = explode(".", $image_name);
                        $num_of_arg = count($arr) - 1;
                        $image_type = $arr[$num_of_arg];

                        $dimensions = new Collection("content_type_dimensions");
                        $dimensionCollection = $dimensions->getCollection("WHERE content_type_id = '$content_type_id' AND title NOT LIKE 'G%'");
                        foreach ($dimensionCollection as $dimension) {
                            $targ_w = $dimension->width;
                            $targ_h = $dimension->height;
                            $url_dim = $dimension->url;

                            if (is_file("../../uploads/uploaded_pictures/$filesTableName/$url_dim/$old_image_name")) {
                                unlink("../../uploads/uploaded_pictures/$filesTableName/$url_dim/$old_image_name");
                            }

                            if ($dimension->crop_resize == 1) {
                                if ($dimension->crop_type == 3) {
                                    $f->cropPictureAddWhiteSpace("../../uploads/uploaded_pictures/$filesTableName/$image_name", $targ_w, $targ_h, $image_type, "../../uploads/uploaded_pictures/$filesTableName/$url_dim/", $image_name);
                                } else {
                                    $f->cropPictureISS("../../uploads/uploaded_pictures/$filesTableName/$image_name", $targ_w, $targ_h, $image_type, "../../uploads/uploaded_pictures/$filesTableName/$url_dim/", $image_name);
                                }
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
                    $fieldName = $columnName . "_" . $lang_code;
                    if (isset($_FILES[$fieldName]) && $_FILES[$fieldName]['size'] > 0) {
                        $rand = rand(0, 999999);
                        $old_file_name = $item->$columnName;
                        if (is_file("../../uploads/uploaded_files/$filesTableName/$old_file_name")) {
                            unlink("../../uploads/uploaded_files/$filesTableName/$old_file_name");
                        }

                        $file_name = $f->fileUpload($fieldName, "../../uploads/uploaded_files/$filesTableName/", "$item->url-$item->resource_id-$rand", FILE_EXT);
                        $item->$columnName = $file_name;
                    }
                    break;

                case "gallery":
                    $fieldName = $columnName . "_" . $lang_code;
                    if (isset($_FILES[$fieldName]) && $_FILES[$fieldName]['size'] > 0) {

                        $rand = rand(0, 999999);
                        $old_file_name = $item->$columnName;
                        if (is_file("../../zips/$old_file_name")) {
                            unlink("../../zips/$old_file_name");
                        }
                        $zipName = $item->url . "-" . $item->resource_id . "-" . $rand;
                        $file_name = $f->fileUpload($fieldName, "../../zips/", "$zipName", FILE_EXT);
                        $item->$columnName = $file_name;
                        $zip = new ZipArchive;
                        if ($zip->open("../../zips/$zipName.zip") === TRUE) {

                            if (is_dir("../../zips/$item->url-gallery") === TRUE) {

                                /* AKO IMA DIR THUMBS ONDA IMA I BIGS */
                                if (is_dir("../../zips/$item->url-gallery/thumbs")) {
                                    /* BRISEM SVE IZ THUMBS DA BI MOGAO NJEGA DA OBRISEM */
                                    if ($handle = opendir("../../zips/$item->url-gallery/thumbs/")) {
                                        while (false !== ($entry = readdir($handle))) {
                                            if ($entry != "." && $entry != "..") {
                                                unlink("../../zips/$item->url-gallery/thumbs/$entry");
                                            }
                                        }
                                        closedir($handle);
                                        rmdir("../../zips/$item->url-gallery/thumbs");
                                    }
                                    /* BRISEM SVE IZ BIGS DA BI MOGAO NJEGA DA OBRISEM */
                                    if ($handle = opendir("../../zips/$item->url-gallery/bigs")) {
                                        while (false !== ($entry = readdir($handle))) {
                                            if ($entry != "." && $entry != "..") {
                                                unlink("../../zips/$item->url-gallery/bigs/$entry");
                                            }
                                        }
                                        closedir($handle);
                                        rmdir("../../zips/$item->url-gallery/bigs");
                                    }
                                }

                                if ($handle = opendir("../../zips/$item->url-gallery")) {
                                    while (false !== ($entry = readdir($handle))) {
                                        if ($entry != "." && $entry != "..") {
                                            unlink("../../zips/$item->url-gallery/$entry");
                                        }
                                    }
                                    closedir($handle);
                                    rmdir("../../zips/$item->url-gallery");
                                }
                            }


                            $zip->extractTo("../../zips/$item->url-gallery");
                            $zip->close();

                            mkdir("../../zips/$item->url-gallery/thumbs");
                            mkdir("../../zips/$item->url-gallery/bigs");

                            $dimensions = new Collection("content_type_dimensions");
                            $dimensionCollection = $dimensions->getCollection("WHERE content_type_id = '$content_type_id' AND title LIKE 'G%'");


                            $counter = 0;
                            if ($handle = opendir("../../zips/$item->url-gallery")) {
                                while (false !== ($entry = readdir($handle))) {
                                    if ($entry != "." && $entry != "..") {
                                        if (!is_dir("../../zips/$item->url-gallery/$entry")) {
                                            foreach ($dimensionCollection as $dimData) {
                                                if (substr($dimData->title, 0, 2) == "GT") {
                                                    list($name, $ext) = explode(".", $entry);
                                                    $imageName = $name . "." . $ext;
                                                    $f->scalePicture("../../zips/$item->url-gallery/$entry", "../../zips/$item->url-gallery/thumbs/", $dimData->crop_resize, $dimData->width, $dimData->height, $imageName, $ext);
                                                } else if (substr($dimData->title, 0, 2) == "GB") {
                                                    list($name, $ext) = explode(".", $entry);
                                                    $imageName = $name . "." . $ext;
                                                    $f->scalePicture("../../zips/$item->url-gallery/$entry", "../../zips/$item->url-gallery/bigs/", $dimData->crop_resize, $dimData->width, $dimData->height, $imageName, $ext);
                                                }
                                            }

                                            unlink("../../zips/$item->url-gallery/$entry");
                                        }
                                    }
                                }
                                closedir($handle);
                            }



                            if (is_file("../../zips/$zipName.zip")) {
                                unlink("../../zips/$zipName.zip");
                            }
                            $item->$columnName = "/zips/$item->url-gallery/";
                        } else {
                            echo "../../zips/" . $zipName . "<br>";
                            echo 'failed';
                        }
                    }

                    break;
            }
        }

        $item->Save();
    }
}