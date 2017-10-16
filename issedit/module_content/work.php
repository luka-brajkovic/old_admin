<?php
require("../library/config.php");
$resizeImage = new ResizeImage();
//Here we have all values from POST and GET
$values = $f->getRequestValues();
$action = $f->getValue("action");

function getValuesForJoins($category, $lang, $resource_id) {
    /* HVATAS CHEKIRANE VALUE sa $_POST[{fv_id},{fh_id},{lang}] */
    $filterHeaders = new Collection("filter_headers");
    $filterHeadersArr = $filterHeaders->getCollection("WHERE cat_resource_id = '$category' AND lang = $lang");
    if (count($filterHeadersArr) > 0) {
        $filterValues = new Collection("filter_values");
        $filterJoins = new Collection("filter_joins");
        foreach ($filterHeadersArr as $fh) {

            $filterJoinsExisting = $filterJoins->getCollection("WHERE lang = $lang AND fh_id = $fh->id AND cat_rid = $category AND product_rid = $resource_id");
            if (count($filterJoinsExisting) > 0) {
                foreach ($filterJoinsExisting as $fJoin) {
                    if ($fJoin->fv_id != $_POST[$fJoin->fv_id . "_" . $fh->id . "_" . $lang] || $fJoin->fv_id == '') {
                        $fJoin->Remove();
                    }
                }
                $filterValuesToWrite = $filterValues->getCollection("WHERE fh_id = $fh->id AND lang = $lang");
                foreach ($filterValuesToWrite as $fValue) {
                    if ($_POST[$fValue->id . "_" . $fh->id . "_" . $lang] != '') {
                        $leftOverJoin = $filterJoins->getCollection("WHERE fv_id = $fValue->id AND fh_id = $fh->id AND product_rid = $resource_id AND cat_rid = $category AND lang = $lang");
                        if (count($leftOverJoin) == 0) {
                            $newJoin = new View("filter_joins");
                            $newJoin->lang = $lang;
                            $newJoin->product_rid = $resource_id;
                            $newJoin->fv_id = $fValue->id;
                            $newJoin->fh_id = $fh->id;
                            $newJoin->cat_rid = $category;
                            $newJoin->Save();
                        }
                    }
                }
            } else {
                $filterValuesToWrite = $filterValues->getCollection("WHERE fh_id = $fh->id AND lang = $lang");
                foreach ($filterValuesToWrite as $fValue) {
                    if ($_POST[$fValue->id . "_" . $fh->id . "_" . $lang] != '') {
                        $newJoin = new View("filter_joins");
                        $newJoin->lang = $lang;
                        $newJoin->product_rid = $resource_id;
                        $newJoin->fv_id = $fValue->id;
                        $newJoin->fh_id = $fh->id;
                        $newJoin->cat_rid = $category;
                        $newJoin->Save();
                    }
                }
            }
        }

        return;
    } else {
        $cats = new Collection("categories");
        $catsArr = $cats->getCollection("WHERE resource_id = $category AND lang = $lang");
        $catData = $catsArr[0];

        if (empty($catData->parent_id))
            return;
        $catsArr = $cats->getCollection("WHERE resource_id = $catData->parent_id AND lang = $lang");
        if (count($catsArr) > 0) {
            $catParent = $catsArr[0];
            getValuesForJoins($catParent->resource_id, $lang, $resource_id);
        }
    }
}

switch ($action) {

    case "obrisi_sliku":
			
			$slika = $_POST["slika"];
			
			$pathArr = explode("/",$slika);
			$dir = "";
			for ($i=0;$i<5;$i++) {
				$dir .=$pathArr[$i]."/";
			}
			if(isset($slika)) {
				unlink($slika);
				unlink(str_replace("thumbs","bigs", $slika));
			}
			if ($handle = opendir($dir)) {
				    while (false !== ($entry = readdir($handle))) {
				        if ($entry != "." && $entry != "..") {
				           ?>
				           <div style="text-align:center; width:120px; float:left; ">
				           <a title="" data-fancybox-group="gallery" href="<?php echo str_replace("thumbs","bigs",$dir)."".$entry; ?>" class="fancybox">
								<img src="<?php echo $dir."".$entry; ?>"/>
								
							</a>
							<a href="javascript:void(0);" onclick="obrisi_sliku('<?php echo $dir."/".$entry; ?>')">Obrisi sliku</a>
							</div>
				           <?php
				        }
				    }
				    closedir($handle);
				    ?>
			<div style="clear:both"></div>
			<?php
				}
			
			
			break;

    case "remove_image":



        $rid = $f->getValue("resource_id");
        $cid = $f->getValue("cid");
        $columnName = $f->getValue("col_name");

        $contentType = new View("content_types", $cid);

        $tableName = $contentType->table_name;



        $itemCol = new Collection($tableName);
        $langCol = new Collection("languages");
        $langArr = $langCol->getCollection("WHERE id !='' ");

        $dimensionCol = new Collection("content_type_dimensions");
        $dimsArr = $dimensionCol->getCollection("WHERE url NOT LIKE 'gt-%' OR url NOT LIKE 'gb-%' AND content_type_id = $cid");


        foreach ($langArr as $thisLang) {

            $itemArr = $itemCol->getCollection("WHERE resource_id = $rid AND lang = $thisLang->id");
            foreach ($itemArr as $item) {

                foreach ($dimsArr as $dim) {

                    $dim_url = $dim->url;

                    if (is_file("../../uploads/uploaded_pictures/$tableName/$dim_url/" . $item->$columnName)) {
                        unlink("../../uploads/uploaded_pictures/$tableName/$dim_url/" . $item->$columnName);
                    }
                }
                $item->$columnName = "";
                $item->Save();
            }
        }

        $f->redirect("edit.php?cid=$cid&rid=$rid");

        break;


    case "pomozi_boze":
        if (isset($_FILES['_content_usluge_sr']) && $_FILES['_content_usluge_sr']['size'] > 0) {
            echo "UBACIO SI";

            $rand = rand(0, 9999);

            $fieldName = "_content_usluge_sr";
            $filesTableName = "_content_usluge";

            $image_name = $f->fileUpload($fieldName, "../../uploads/uploaded_pictures/$filesTableName/", "img-url-img-rid-$rand", PIC_EXT);

            $arr = explode(".", $image_name);
            $num_of_arg = count($arr) - 1;
            $image_type = $arr[$num_of_arg];

            echo "<br><BR>Files table name je: $filesTableName";
            echo "<br>Image name je: $image_name";
            echo "<br>Image type je: $image_type";

            $dimensions = new Collection("content_type_dimensions");
            $dimensionCollection = $dimensions->getCollection("WHERE content_type_id = '13' AND title NOT LIKE 'G%'");
            foreach ($dimensionCollection as $dimension) {
                $targ_w = $dimension->width;
                $targ_h = $dimension->height;
                $url_dim = $dimension->url;

                if (is_file("../../uploads/uploaded_pictures/$filesTableName/$url_dim/$old_image_name")) {
                    unlink("../../uploads/uploaded_pictures/$filesTableName/$url_dim/$old_image_name");
                }

                if ($dimension->crop_resize == 1) {
                    $f->cropPictureISS("../../uploads/uploaded_pictures/$filesTableName/$image_name", $targ_w, $targ_h, $image_type, "../../uploads/uploaded_pictures/$filesTableName/$url_dim/", $image_name);
                } else {
                    $resizeImage->load("../../uploads/uploaded_pictures/$filesTableName/$image_name");
                    $resizeImage->resizeToWidth($targ_w);
                    $resizeImage->save("../../uploads/uploaded_pictures/$filesTableName/$url_dim/$image_name");
                }
            }
        } else {
            echo "nisi ubacio fajl some!";
        }
        break;

    case "add_content":

        $cid = $f->getValue("content_type_id");
        $contentType = new View("content_types", $cid);

        $resource = new View("resources");
        $resource->table_name = $contentType->table_name;
        $resource->Save();

        $numberOfItems = $db->numRows("SELECT * FROM resources WHERE table_name = '$contentType->table_name'");

        $langCollection = new Collection("languages");
        $languages = $langCollection->getCollection();
        foreach ($languages as $key => $language) {
            $lang_code = $language->code;
            $langValues = $f->getValue($lang_code);

            $content = new View($contentType->table_name);
            $content->lang = $language->id;
            $content->title = $langValues['title'];
            $content->url = $f->generateUrlFromText($content->title);
            $content->resource_id = $resource->id;
            $content->ordering = $numberOfItems + 1;
            $content->system_date = date("Y-m-d H:i:s");
            $content->status = 0;
            $content->Save();
        }

        $edit = $f->getValue("edit");
        if ($edit == 0) {
            $f->redirect("index.php?cid=$cid&infomsg=success_create_content");
        } else {
            $f->redirect("edit.php?rid=$resource->id&cid=$cid");
        }
        break;




    case "apply_content":
    case "edit_content":



        $resource_id = $f->getValue("resource_id");
        $content_type_id = $f->getValue("content_type_id");
        $langCollection = new Collection("languages");
        $languages = $langCollection->getCollection();
        $contentType = new View("content_types", $content_type_id);

        if ($contentType->category_type != 0) {
            $category = $f->getValue("categorySelected");

            $db->execQuery("DELETE FROM categories_content WHERE content_resource_id = '$resource_id'");
            if ($category != "") {
                $cat_content = new View("categories_content");
                $cat_content->category_resource_id = $category;
                $cat_content->content_resource_id = $resource_id;
                $cat_content->Save();




                if (is_file("../../issedit/module_filters/save_joins.php")) {


                    include("../../issedit/module_filters/save_joins.php");
                }
            } else {
                $categories = $f->getValue("categories");

                for ($i = 0; $i < count($categories); $i++) {
                    $cat_content = new View("categories_content");
                    $cat_content->category_resource_id = $categories[$i];
                    $cat_content->content_resource_id = $resource_id;
                    $cat_content->Save();

                    $category = $categories[$i];
                    if (is_file("../../issedit/module_filters/save_joins.php")) {

                        include("../../issedit/module_filters/save_joins.php");
                    }
                }
            }
        }

        $filesTableName = $contentType->table_name;
        $contentTypeFields = new Collection("content_type_fields");
        $content = new Collection($filesTableName);
        foreach ($languages as $key => $language) {

            $lang_code = $language->code;

            $contentCollection = $content->getCollection("WHERE resource_id = '$resource_id' AND lang = '$language->id'");
            $languageValues = $f->getValue($lang_code);

            foreach ($contentCollection as $item) {
                
                $languageValues["title"] = str_replace(array('"','\"', "'", "\'", "../.."), array('&#34;', '&#34;', "&#39;", "&#39;", ""), $languageValues["title"]);
                
                $item->title = $languageValues["title"];
                $item->status = $languageValues["status"];
                $item->url = $f->generateUrlFromText(str_replace(array('&#34;', '&#34;', "&#39;", "&#39;"),"",$languageValues["title"]));

                if ($content_type_id == 81) {
                    $emailSend = $languageValues["email"];
                    $proizvodRid = $languageValues["proizvod"];

                    $proizvod = mysql_query("SELECT "
                            . " cp.url, cp.title, cp.resource_id, c.url as cat_master_url, c1.url as cat_url, cb.title as b_title FROM _content_proizvodi cp "
                            . " LEFT JOIN categories_content cc ON cc.content_resource_id = cp.resource_id "
                            . " LEFT JOIN categories c1 ON c1.resource_id = cc.category_resource_id "
                            . " LEFT JOIN categories c ON c.resource_id = c1.parent_id "
                            . " LEFT JOIN _content_brend cb ON cb.resource_id = cp.brand "
                            . " WHERE cp.resource_id = $proizvodRid LIMIT 1") or die(mysql_error());
                    $proizvod = mysql_fetch_object($proizvod);

                    $bodySend = 'Odgovor na Vaše pitanje na proizvod "' . $proizvod->b_title . ' ' . $proizvod->title . '" možete pročitati na linku ispod:<br/><br/> <a href="' . $configSiteDomain . $proizvod->cat_master_url . '/' . $proizvod->cat_url . '/' . $proizvod->url . '/' . $proizvod->resource_id . '" title="' . $proizvod->b_title . ' ' . $proizvod->title . '">' . $configSiteDomain . $proizvod->cat_master_url . '/' . $proizvod->cat_url . '/' . $proizvod->url . '/' . $proizvod->resource_id . '</a>';
                }

                $contentTypeFieldsCollection = $contentTypeFields->getCollection("WHERE content_type_id = '$content_type_id' and all_languages != 1");

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
                            if ($content_type_id == 81 && $languageValues["odgovor"] == '' || $item->vreme_odgovora == "0000-00-00 00:00:00") {
                                $item->vreme_odgovora = date("Y-m-d H:i:s");

                                require_once("../../library/phpmailer/class.phpmailer.php");

                                $mail = new PHPMailer();
                                $mail->From = $configSiteEmail;
                                $mail->AddReplyTo($configSiteEmail, $configSiteFirm);
                                $mail->FromName = $configSiteFirm;
                                $mail->AddAddress($emailSend);
                                $mail->Subject = $configSiteFirm . " | Odgovor na pitanje";
                                $mail->Body = $bodySend;
                                $mail->Send();
                            }
                            $languageValues[$columnName] = str_replace(array('\"', "\'", "../.."), array('"', "&#39;", ""), $languageValues[$columnName]);
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

        //for all langiuages

        include("all_lang_files_work.php");



        if ($f->getValue("apply") != "Apply") {
            if ($_SESSION["return_url"] != '') {
                $f->redirect($_SESSION['return_url']);
            } else {
                $f->redirect("index.php?cid=$content_type_id");
            }
        } else {
            $f->redirect("edit.php?rid=$resource_id&cid=$content_type_id");
        }
        break;

    case "delete_content":



        $resource_id = $f->getValue("rid");

        $resource = new View("resources", $resource_id, "id");
        $ct = new View("content_types", $resource->table_name, "table_name");
        mysql_query("DELETE FROM $resource->table_name WHERE resource_id = $resource_id");
        mysql_query("DELETE FROM resources WHERE id = $resource_id");
        $f->redirect("index.php?cid=" . $ct->id . "&infomsg=success_delete_content");

        break;

    //Ordering of content
    case "order":

        $item = $f->getValue("item");

        for ($i = 0; $i < count($item); $i++) {
            $resource = new View("resources", $item[$i], "id");
            $ordering = $i + 1;
            $db->execQuery("UPDATE " . $resource->table_name . " SET `ordering`='$ordering' WHERE resource_id='$item[$i]'");
        }

        break;
    case "copy_content":
        $languageCollection = new Collection("languages");
        $languages = $languageCollection->getCollection();
        $resource_id = $f->getValue("rid");
        $content_type_id = $f->getValue("cid");
        $ct = new View("content_types", $content_type_id);
        $resource = new View("resources");
        $resource->table_name = $ct->table_name;
        $resource->Save();
        $cat_cont = new View("categories_content", $resource_id, "content_resource_id");
        if ($cat_cont->id) {
            $newCC = new View("categories_content");
            $newCC->content_resource_id = $resource->id;
            $newCC->category_resource_id = $cat_cont->category_resource_id;
            $newCC->Save();
            $categoriesCollection = new Collection("categories");
            $categories = $categoriesCollection->getCollection("WHERE resource_id = $newCC->category_resource_id AND lang = $currentLanguage");
            $category = $categories[0];
        }
        $contentCollection = new Collection($ct->table_name);
        foreach ($languages as $language) {
            $oldContent = $contentCollection->getCollection("WHERE resource_id = $resource_id AND lang = $language->id");
            $oldContent = $oldContent[0];
            $maxValuOrder = mysql_query("SELECT MAX(ordering) as max_order FROM $ct->table_name");
            $maxValuOrder = mysql_fetch_object($maxValuOrder);
            $order = $maxValuOrder->max_order + 1;
            $content = new View($ct->table_name);
            $content->resource_id = $resource->id;
            $content->title = "Copy of " . $oldContent->title;
            $content->url = "copy-of-" . $oldContent->url;
            $content->system_date = date("Y-m-d H:i:s");
            $content->parent_id = $oldContent->parent_id;
            $content->status = $oldContent->status;
            $content->description = $oldContent->description;
            $content->ordering = $order;
            $contentTypeFields = new Collection("content_type_fields");
            $contentTypeFieldsCollection = $contentTypeFields->getCollection("WHERE content_type_id = '$content_type_id'");
            foreach ($contentTypeFieldsCollection as $field) {
                $columnName = $field->column_name;
                $content->$columnName = $oldContent->$columnName;
            }
            $content->lang = $language->id;
            $content->Save();
        }
        if ($cat_cont->id)
            $f->redirect("index.php?category_id=$category->id&cid=$content_type_id");
        else
            $f->redirect("index.php?cid=$content_type_id");
        break;

    case "povecaj_cenu":

        $resIDs = $_POST['check_content'];
        $procenat = $_POST['procenat'];

        foreach ($resIDs as $rid) {
            mysql_query("UPDATE _content_proizvodi SET `price` = round((($procenat * price) / 100) + price), `old_price` = round((($procenat * old_price) / 100) + old_price) WHERE resource_id = $rid");
        }
        $f->redirect($_SERVER['HTTP_REFERER']);
        break;
    case "editable":
        print_r($_GET);

        list($cid, $rid, $columnName) = explode("_", $_GET['id']);
        $value = $_GET['value'];


        $ct = new View('content_types', $cid);
        $tableName = $ct->table_name;

        mysql_query("UPDATE $tableName SET $columnName = '$value' WHERE resource_id = $rid");

        break;
    case "save_title":
        $cid = $f->getValue("cid");
        $rid = $f->getValue("rid");
        $value = $f->getValue("vrednost");

        $url = $f->generateUrlFromText($value);

        $ct = new View('content_types', $cid);
        $tableName = $ct->table_name;
        mysql_query("UPDATE $tableName SET title = '$value', url = '$url' WHERE resource_id = $rid") or die(mysql_error());

        echo $value;


        break;

    case "save_status":
        $cid = $f->getValue("cid");
        $rid = $f->getValue("rid");
        $value = $f->getValue("vrednost");
        $ct = new View('content_types', $cid);
        $tableName = $ct->table_name;
        mysql_query("UPDATE $tableName SET status = '$value' WHERE resource_id = $rid") or die(mysql_error());

        if ($value == "1") {
            echo "active";
        } else {
            echo "inactive";
        }
        break;
}
?>