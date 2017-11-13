<?php

require("../library/config.php");
$resizeImage = new ResizeImage();

//Here we have all values from POST and GET
$values = $f->getRequestValues();
$action = $values["action"];



switch ($action) {

    case "add_category":

        $langCollection = new Collection("languages");
        $languages = $langCollection->getCollection();

        //Creating new resource
        $resurce = new View("resources");
        $resurce->table_name = "categories";
        $resurce->Save();

        $edit = $f->getValue("edit");

        foreach ($languages as $lang) {

            //Creating new category for each language
            $langValues = $f->getValue($lang->code);
            $category = new View("categories");
            $category->extend($langValues);

            //Get number of categories for ordering
            $orderQuery = $db->execQuery("SELECT ordering FROM categories WHERE content_type_id='$category->content_type_id' AND parent_id = '$category->parent_id' AND resource_id != '$resurce->id' ORDER BY ordering DESC");
            $dataOrdering = mysqli_fetch_array($orderQuery);
            $ordering = $dataOrdering["ordering"] + 1;


            $category->ordering = $ordering;
            $category->resource_id = $resurce->id;
            $category->lang = $lang->id;
            $category->url = $f->generateUrlFromText($category->title);
            $category->level = $f->categoryLevel($category);
            $category->status = 2; //inactive by default
            $category->Save();
        }

        if ($edit) {
            $f->redirect("edit_category.php?id=$resurce->id");
        } else {
            $f->redirect("index.php?cid=$category->content_type_id&parent_id=$category->parent_id&infomsg=success_add_category");
        }

        break;

    case "delete_category":

        $id = $f->getValue("id");
        $category = new View("categories", $id);

        //categories custom fields
        $categoriesFields = new Collection("categories_fields");
        $categoriesFieldsCollection = $categoriesFields->getCollection("WHERE content_type_id = '$category->content_type_id' AND (field_type='image' OR field_type = 'file')");

        //deleteing all files and images for this category from custom fields
        //deleteing all files and images for this category from global fields
        $categoriesGlobalFields = new Collection("fields");
        $categoriesGlobalFieldsCollection = $categoriesGlobalFields->getCollection("WHERE table_name = 'categories' AND (field_type='image' OR field_type = 'file')");



        $resurce = new View("resources", $category->resource_id);
        $resurce->Remove();

        $f->redirect("index.php?cid=$category->content_type_id&parent_id=$category->parent_id&infomsg=success_delete_category");

        break;

    //Ordering of categories. Order by resource id    
    case "order":

        $item = $f->getValue("item");
        for ($i = 0; $i < count($item); $i++) {
            $ordering = $i + 1;
            $db->execQuery("UPDATE categories SET `ordering`='$ordering' WHERE resource_id='$item[$i]'");
        }

        break;

    case "edit_category":

        $resource_id = $f->getValue("resource_id");
        $parent_id = $f->getValue("parent_id");
        $content_type_id = $f->getValue("content_type_id");
        $langCollection = new Collection("languages");
        $languages = $langCollection->getCollection();

        $filesTableName = "categories";

        foreach ($languages as $key => $language) {

            $lang_code = $language->code;
            $category = new Collection("categories");
            $categoryCollection = $category->getCollection("WHERE resource_id = '$resource_id' AND lang = '$language->id'");
            $languageValues = $f->getValue($lang_code);

            foreach ($categoryCollection as $categorySingle) {

                $status = $f->getValue("status_" . $language->code);

                //$opt_cat = $f->getValue("opt_cat_" . $language->code);
                $opt_cont = $f->getValue("opt_cont_" . $language->code);


                $categorySingle->parent_id = $f->getValue("parent_" . $language->code);

                if ($categorySingle->status != $status && $status != 1)
                    $f->setCategorySatus($categorySingle->id, $status);

                $categorySingle->status = $status;

                /* if($opt_cat != 0){
                  // Activiram/deaktiviram categorije
                  $subCategoriesCollection = new Collection("categories");
                  $subCategories = $subCategoriesCollection->getCollection("WHERE '$categorySingle->resource_id' = parent_id AND '$language->id' = lang");
                  foreach($subCategories as $subCategory)
                  if($opt_cat == 1)
                  $subCategory->status = 1;
                  else if($opt_cat == 2)
                  $subCategory->status = 2;
                  echo $subCategory->status;
                  $subCategory->Save();
                  } */

                if ($opt_cont != 0) {
                    $f->setContentsForCategory($categorySingle, $opt_cont);
                }

                $categorySingle->title = $languageValues["title"];
                $categorySingle->url = $f->generateUrlFromText($languageValues["title"]);
                $categorySingle->description = $languageValues["description"];

                $categoriesGlobalFields = new Collection("fields");
                $categoriesGlobalFieldsCollection = $categoriesGlobalFields->getCollection("WHERE table_name = 'categories'");

                foreach ($categoriesGlobalFieldsCollection as $field) {
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
                            $languageValues[$columnName] = str_replace(array('\"', "\'", "../.."), array('"', "&#39;", ""), $languageValues[$columnName]);
                            $categorySingle->$columnName = $languageValues[$columnName];
                            break;

                        case "checkbox":
                            if (isset($languageValues[$columnName]) && count($languageValues[$columnName]) > 0) {
                                $categorySingle->$columnName = implode(",", $languageValues[$columnName]);
                            }
                            break;

                        case "image":
                            $fieldName = $columnName . "_" . $lang_code;
                            if (isset($_FILES[$fieldName]) && $_FILES[$fieldName]['size'] > 0) {
                                $rand = rand(0, 999999);
                                $old_image_name = $categorySingle->$columnName;
                                if (is_file("../../uploads/uploaded_pictures/$filesTableName/$old_image_name")) {
                                    unlink("../../uploads/uploaded_pictures/$filesTableName/$old_image_name");
                                }

                                $image_name = $f->fileUpload($fieldName, "../../uploads/uploaded_pictures/$filesTableName/", "$categorySingle->url-$categorySingle->resource_id-$rand", PIC_EXT);
                                $categorySingle->$columnName = $image_name;

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
                            $fieldName = $columnName . "_" . $lang_code;
                            if (isset($_FILES[$fieldName]) && $_FILES[$fieldName]['size'] > 0) {
                                $rand = rand(0, 999999);
                                $old_file_name = $categorySingle->$columnName;
                                if (is_file("../../uploads/uploaded_files/$filesTableName/$old_file_name")) {
                                    unlink("../../uploads/uploaded_files/$filesTableName/$old_file_name");
                                }

                                $file_name = $f->fileUpload($fieldName, "../../uploads/uploaded_files/$filesTableName/", "$categorySingle->url-$categorySingle->resource_id-$rand", FILE_EXT);
                                $categorySingle->$columnName = $file_name;
                            }
                            break;
                    }
                }

                $categoriesFields = new Collection("categories_fields");
                $categoriesFieldsCollection = $categoriesFields->getCollection("WHERE content_type_id = '$categorySingle->content_type_id'");

                foreach ($categoriesFieldsCollection as $field) {
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
                            $categorySingle->$columnName = $languageValues[$columnName];
                            break;

                        case "checkbox":
                            $categorySingle->$columnName = implode(",", $languageValues[$columnName]);
                            break;

                        case "image":
                            $fieldName = $columnName . "_" . $lang_code;
                            if (isset($_FILES[$fieldName]) && $_FILES[$fieldName]['size'] > 0) {
                                $rand = rand(0, 999999);
                                $old_image_name = $categorySingle->$columnName;
                                if (is_file("../../uploads/uploaded_pictures/$filesTableName/$old_image_name")) {
                                    unlink("../../uploads/uploaded_pictures/$filesTableName/$old_image_name");
                                }

                                $image_name = $f->fileUpload($fieldName, "../../uploads/uploaded_pictures/$filesTableName/", "$categorySingle->url-$categorySingle->resource_id-$rand", PIC_EXT);
                                $categorySingle->$columnName = $image_name;

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
                            $fieldName = $columnName . "_" . $lang_code;
                            if (isset($_FILES[$fieldName]) && $_FILES[$fieldName]['size'] > 0) {
                                $rand = rand(0, 999999);
                                $old_file_name = $categorySingle->$columnName;
                                if (is_file("../../uploads/uploaded_files/$filesTableName/$old_file_name")) {
                                    unlink("../../uploads/uploaded_files/$filesTableName/$old_file_name");
                                }

                                $file_name = $f->fileUpload($fieldName, "../../uploads/uploaded_files/$filesTableName/", "$categorySingle->url-$categorySingle->resource_id-$rand", FILE_EXT);
                                $categorySingle->$columnName = $file_name;
                            }
                            break;
                    }
                }

                $categorySingle->Save();
            }
        }
        if ($f->getValue("Apply") == "Apply") {
            $f->redirect("edit_category.php?id=$categorySingle->resource_id");
        } else {
            $f->redirect("index.php?parent_id=$parent_id&cid=$content_type_id");
        }
        break;
    case "copy_category":
        $languageCollection = new Collection("languages");
        $languages = $languageCollection->getCollection();
        $resource_id = $f->getValue("resource_id");
        $resource = new View("resources");
        $resource->table_name = "categories";
        $resource->Save();
        $categoriesCollection = new Collection("categories");
        foreach ($languages as $language) {
            $category = new View("categories");
            $oldCategories = $categoriesCollection->getCollection("WHERE resource_id = $resource_id AND lang = $currentLanguage");
            $oldCategory = $oldCategories[0];
            $category->resource_id = $resource->id;
            $category->title = "Copy of " . $oldCategory->title;
            $category->url = "copy-of-" . $oldCategory->url;
            $category->system_date = date("Y-m-d H:i:s");
            $category->parent_id = $oldCategory->parent_id;
            $category->status = $oldCategory->status;
            $category->description = $oldCategory->description;
            $category->ordering = $oldCategory->ordering;
            $categoriesGlobalFields = new Collection("fields");
            $categoriesGlobalFieldsCollection = $categoriesGlobalFields->getCollection("WHERE table_name = 'categories'");
            foreach ($categoriesGlobalFieldsCollection as $field) {
                $columnName = $field->column_name;
                $category->$columnName = $oldCategory->$columnName;
            }
            $categoriesFields = new Collection("categories_fields");
            $categoriesFieldsCollection = $categoriesFields->getCollection("WHERE content_type_id = '$oldCategory->content_type_id'");

            foreach ($categoriesFieldsCollection as $field) {
                $columnName = $field->column_name;
                $category->$columnName = $oldCategory->$columnName;
            }
            $category->content_type_id = $oldCategory->content_type_id;
            $category->lang = $language->id;
            $category->Save();
        }
        $f->redirect("index.php?parent_id=$category->parent_id&cid=$category->content_type_id");
        break;
}
?>