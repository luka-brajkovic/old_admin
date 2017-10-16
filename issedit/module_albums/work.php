<?php

require("../library/config.php");
$resizeImage = new ResizeImage();

$values = $f->getRequestValues();
$action = $values['action'];

$cropDimension = new View("dimensions", 1);
$resizeDimension = new View("dimensions", 2);

switch ($action) {

    case "add_album"://sređeno

        $album = new View("albums");
        $album->extend($_POST);

        $album2 = new View("albums", $album->title, "title");
        if ($album->title == $album2->title)
            $album->title .= "-" . rand(1, 99999);
        $album->system_date = date("Y-m-d H:i:s");
        $album->Save();

        //Možemo sve slike za sve albume u iste direktorijume jer sad imamo tabelu pictures

        $f->redirect("index.php");

        break;

    case "edit_album"://sređeno

        $album = new View("albums", $values['album_id']);
        $album->extend($_POST);
        $album->Save();
        $f->redirect("index.php");

        break;

    case "delete_album"://sređeno

        $album_id = $f->getValue("album_id");
        $album = new View("albums", $album_id);
        $album->remove();
        $f->redirect("index.php");
        break;

    case "edit_picture"://sređeno

        $picture_id = $f->getValue("picture_id");
        $picture = new View("pictures", $picture_id);
        $picture->picture_name = $f->getValue("picture_name");
        $picture->Save();

        $f->redirect("album.php?album_id=$picture->album_id");

    case "add_picture"://sređeno

        $album_id = $f->getValue("album_id");
        $picture = new View("pictures");
        $album = new View("albums", $album_id);
        $picture->extend($_POST);

        $pic_name = $_FILES["picture"]["name"];

        $extension = strtolower(substr("$pic_name", -3));
        $pic_name = strtolower(str_replace(".$extension", "", $pic_name));
        if ($picture->picture_name == "")
            $picture->picture_name = $pic_name;

        //uvek se dodaje random
        do {
            $random = rand(1, 99999);
            $pic_name = $pic_name . "-$random";
        } while (is_file("../../uploads/uploaded_pictures/albums/$pic_name"));
        $picture->file_name = $pic_name . "." . $extension;
        //$picture->ordering = $album->num_of_pic;

        /* $albums_crop = ALBUMS_CROP;
          $albums_resize = ALBUMS_RESIZE;
          list($targ_w, $targ_h) = explode("x", $albums_crop); */

        $targ_w = $cropDimension->width;
        $targ_h = $cropDimension->height;
        $albums_resize = $resizeDimension->width;

        $image_name = $f->fileUpload("picture", "../../uploads/uploaded_pictures/albums/", "$pic_name", PIC_EXT);
        $size = getimagesize("../../uploads/uploaded_pictures/albums/" . $picture->file_name);

        $arr = explode(".", $image_name);
        $num_of_arg = count($arr) - 1;
        $image_type = $arr[$num_of_arg];
        $f->newCropImage("../../uploads/uploaded_pictures/albums/$image_name", $targ_w, $targ_h, $image_type, "../../uploads/uploaded_pictures/albums/crop/", $image_name);

        $resizeImage->load("../../uploads/uploaded_pictures/albums/$image_name");
        if ($albums_resize > 0 && $albums_resize < $size[0])
            $resizeImage->resizeToWidth($albums_resize);
        $resizeImage->save("../../uploads/uploaded_pictures/albums/resize/$image_name");

        unlink("../../uploads/uploaded_pictures/albums/$image_name");

        $picture->ordering = $f->getMaxValue("pictures", "ordering") + 1;

        $picture->Save();
        $album->num_of_pic++;
        $album->Save();

        $f->redirect("album.php?album_id=" . $album_id);

        break;

    case "delete_picture"://sređeno

        $picture_id = $f->getValue("picture_id");
        $album_id = $f->getValue("album_id");

        $picture = new View("pictures", $picture_id);
        $albumSingle = new View("albums", $album_id);

        if (is_file("../../uploads/uploaded_pictures/albums/resize/$picture->file_name")) {
            unlink("../../uploads/uploaded_pictures/albums/resize/$picture->file_name");
        }

        if (is_file("../../uploads/uploaded_pictures/albums/crop/$picture->file_name")) {
            unlink("../../uploads/uploaded_pictures/albums/crop/$picture->file_name");
        }

        $picture->Remove();
        $albumSingle->num_of_pic--;
        $albumSingle->Save();

        $f->redirect("album.php?album_id=$album_id");
        break;

    case "add_gallery"://sređeno

        $album_id = $f->getValue("album_id");
        $album = new View("albums", $album_id);

        $rand = rand(0, 999);
        $temp_name = "temp" . $rand;
        $temp_name_pic = "temp" . $rand . "/pictures/";
        mkdir("../../uploads/uploaded_pictures/albums/$temp_name/");
        mkdir("../../uploads/uploaded_pictures/albums/$temp_name_pic");

        $zip_file = $f->fileUpload("zip_file", "../../uploads/uploaded_pictures/albums/$temp_name/", rand(0, 99999), GALLERY_EXT);

        $arr = explode(".", $zip_file);
        $num_of_arg = count($arr) - 1;
        $file_type = $arr[$num_of_arg];

        $zip = zip_open("../../uploads/uploaded_pictures/albums/$temp_name/$zip_file");
        if ($zip) {
            while ($zip_entry = zip_read($zip)) {
                $fp = fopen("../../uploads/uploaded_pictures/albums/$temp_name_pic/" . zip_entry_name($zip_entry), "w");
                if (zip_entry_open($zip, $zip_entry, "r")) {
                    $buf = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
                    fwrite($fp, "$buf");
                    zip_entry_close($zip_entry);
                    fclose($fp);
                }
            }
            zip_close($zip);
        }

        $dir = "../../uploads/uploaded_pictures/albums/$temp_name_pic";
        $dh = opendir($dir);

        $targ_w = $cropDimension->width;
        $targ_h = $cropDimension->height;
        $albums_resize = $resizeDimension->width;

        $i = 1;
        while ($file = readdir($dh)) {
            if ($file != "." && $file != "..") {

                $picture = new View("pictures");
                $picture->album_id = $album_id;

                $arr = explode(".", $file);
                $num_of_arg = count($arr) - 1;
                $image_type = $arr[$num_of_arg];


                $image_name = str_replace(".", "-" . rand(1, 99999) . ".", $file); //file_name.ext = file_name-rand.ext
                $picture->file_name = $image_name;
                $picture->picture_name = $image_name;
                $picture->ordering = $album->num_of_pic;
                $picture->Save();
                $album->num_of_pic++;

                $f->newCropImage($dir . $file, $targ_w, $targ_h, $image_type, "../../uploads/uploaded_pictures/albums/crop/", $image_name);
                $resizeImage->load($dir . $file);
                if ($albums_resize > 0)
                    $resizeImage->resizeToWidth($albums_resize);
                $resizeImage->save("../../uploads/uploaded_pictures/albums/resize/$image_name");

                unlink($dir . $file);
                $i++;
            }
        }

        rmdir("../../uploads/uploaded_pictures/albums/$temp_name_pic");
        unlink("../../uploads/uploaded_pictures/albums/$temp_name/$zip_file");
        rmdir("../../uploads/uploaded_pictures/albums/$temp_name");

        $album->Save();
        $f->redirect("album.php?album_id=$album_id");

        break;

    case "delete_selected_pictures"://Sređeno

        $album_id = $f->getValue("album_id");
        $album = new View("albums", $album_id);
        $picture_id = $f->getValue("pictures");


        for ($i = 0; $i < count($picture_id); $i++) {
            $picture = new View("pictures", $picture_id[$i]);

            if (is_file("../../uploads/uploaded_pictures/albums/crop/$picture->file_name")) {
                unlink("../../uploads/uploaded_pictures/albums/crop/$picture->file_name");
            }

            if (is_file("../../uploads/uploaded_pictures/albums/resize/$picture->file_name")) {
                unlink("../../uploads/uploaded_pictures/albums/resize/$picture->file_name");
            }

            $picture->Remove();
            $album->num_of_pic--;
        }

        $album->Save();
        $f->redirect("album.php?album_id=$album_id");
        break;

    case "order":
        $item = $f->getValue("item");
        for ($i = 0; $i < count($item); $i++) {
            $ordering = $i + 1;
            $db->execQuery("UPDATE pictures SET `ordering`='$ordering' WHERE id='$item[$i]'");
        }
        break;
}
?>