<?php

class Functions extends Database {

    function redirect($link, $time = 0) {
        echo "<meta http-equiv='refresh' content='$time;URL=$link' />";
        die();
    }

    function stringCleaner($string) {
        $string = trim($string);
        $string = mysql_real_escape_string($string);
        return $string;
    }

    function printData($string) {
        return stripslashes($string);
    }

    function makeFancyDate($date) {
        $dateA = explode(" ", $date);
        list($y, $m, $d) = explode("-", $dateA[0]);
        list($h, $i, $s) = explode(":", $dateA[1]);

        switch ($m) {
            case "01":
                $m = "jan";
                break;
            case "02":
                $m = "feb";
                break;
            case "03":
                $m = "mar";
                break;
            case "04":
                $m = "apr";
                break;
            case "05":
                $m = "maj";
                break;
            case "06":
                $m = "jun";
                break;
            case "07":
                $m = "jul";
                break;
            case "08":
                $m = "avg";
                break;
            case "09":
                $m = "sep";
                break;
            case "10":
                $m = "okt";
                break;
            case "11":
                $m = "nov";
                break;
            case "12":
                $m = "dec";
                break;
        }
        return $d . "." . $m . "." . $y . " - " . $h . ":" . $i;
    }

    function calculateDate($date) {

        $timeNow = time();
        $time = strtotime($date);

        $difference = $timeNow - $time;
        if ($difference < 60) {
            $seconds = $difference;
            return "pre " . $seconds . " sekund" . $this->lastChar($seconds);
        } else if ($difference < 3600) {
            $minutes = round($difference / 60);
            return "pre " . $minutes . " minut" . $this->lastChar($minutes);
        } else if ($difference < 86400) {
            $hours = round($difference / 3600);
            return "pre " . $hours . " sat" . $this->lastChar($hours);
        } else if ($difference < 2592000) {
            $days = round($difference / 86400);
            return "pre " . $days . " dan" . $this->lastChar($days);
        } else if ($difference < 31104000) {
            $months = round($difference / 2592000);
            return "pre " . $months . " mesec" . $this->lastChar($months);
        } else {
            $years = round($difference / 31104000);
            return "pre " . $years . " godina" . $this->lastChar($years);
        }
    }

    /**
     * Check if admin is logged in
     *
     */
    function checkLogedAdmin($type = "") {

        if (!$_SESSION['loged_admin']) {
            $redirect_link = $_SERVER['REQUEST_URI'];
            $_SESSION['redirect_link'] = $redirect_link;
            if ($type == "module")
                $this->redirect("../login.php");
            else
                $this->redirect("login.php");

            die();
            exit;
        } else {
            $_SESSION['redirect_link'] = "";
        }
    }

    //Returns informations of loged admin
    function logedAdmin() {
        $adminId = $_SESSION['loged_admin'];
        if (isset($adminId)) {
            return true;
        } else {
            return false;
        }
    }

    function printLanguages() {
        $query = Database::execQuery("SELECT * FROM languages ORDER BY id");
        $lang_id = $_SESSION['admin_lang'];
        ?>
        <form method="POST" action="/issedit/work.php">
            <select id="selectedLanguage" name="selectedLanguage">
                <?php
                while ($data = mysql_fetch_array($query)) {
                    if ($lang_id == "") {
                        ?>
                        <option value="<?= $data['id'] ?>" <?php if ($data['is_default'] == 1) echo 'selected="selected"'; ?> > <?= $data['title']; ?> </option>
                        <?php
                    } else {
                        ?>
                        <option value="<?= $data['id'] ?>" <?php if ($data['id'] == $lang_id) echo 'selected="selected"'; ?> > <?= $data['title']; ?> </option>
                        <?php
                    }
                }
                ?>
            </select>
            <input type="hidden" name="action" value="change_lang" />
            <input class="button" type="submit" value="Change" />
        </form>
        <?php
    }

    function adminName() {
        $admin_info = $_SESSION['admin_info'];
        return $admin_info['fullname'];
    }

    function adminEmail() {
        $admin_info = $_SESSION['admin_info'];
        return $admin_info['email'];
    }

    /**
     * Check if user is logged
     *
     * @return int
     */
    function logedUser() {
        $userId = $_COOKIE["svez_user"];
        $check_code = $_COOKIE["svez_code"];
        $dateNow = date("Y-m-d H:i:s");

        $query = Database::execQuery("SELECT * FROM users WHERE id = '$userId' AND code = '$check_code' AND approved = '1'", $link);
        $resultCount = mysql_num_rows($query);

        if ($resultCount == 1) {

            $_SESSION['svu'] = 1;

            return $userId;
        } else {

            $cookie = $this->get_facebook_cookie(YOUR_APP_ID, YOUR_APP_SECRET);
            $readData = @file_get_contents('https://graph.facebook.com/me?access_token=' . $cookie['access_token']);
            if ($readData) {

                $fbuser = json_decode($readData);

                if ($cookie) {

                    $_SESSION['fbu'] = 1;
                    $_SESSION['svu'] = 0;

                    $postoji = parent::numRows("SELECT * FROM users WHERE id = '" . $fbuser->id . "'");
                    if ($postoji == 0) {
                        $date_ins = date("Y-m-d");
                        $date_app = date("Y-m-d");
                        list(,, $birth_year) = explode("/", $fbuser->birthday);
                        $gender = ($fbuser->gender == "male") ? 0 : 1;

                        $check_code = $this->passwordGeneration();
                        $this->execQuery("INSERT INTO users (`id`, `email`, `name`, `password`, `username`, `country`, `city`, `birth_year`, `telephone`, `allow_inbox`, `allow_newsletter`, `approved`, `date_ins`, `date_app`, `code`, `description`, `fbuser`)
													   VALUES ('$fbuser->id', '$fbuser->email', '$fbuser->name', '', '$fbuser->name', 'Srbija', '" . $fbuser->hometown->name . "', '$birth_year', '', '1', '1', '1', '$date_ins', '$date_app', '$check_code', '$fbuser->bio', '1')");

                        $points = parent::getValue("points", "activity", "activity", "register");
                        $view_text = parent::getValue("view_text", "activity", "activity", "register");

                        $this->execQuery("INSERT INTO user_activity (`user`, `activity`, `date`) VALUES ('$fbuser->id', '$view_text', '$date_ins')");

                        $this->execQuery("INSERT INTO user_info (`user`, `points`) VALUES ('$fbuser->id', '$points')");
                    }
                    return $fbuser->id;
                    //$this->debug($fbuser);
                } else {
                    $_SESSION['fbu'] = 0;
                }
            }

            $_SESSION['fbu'] = 0;
            $_SESSION['svu'] = 0;
            return 0;
        }
    }

    function cropPictureISSFromURL($image, $targ_w, $targ_h, $imageType, $image_destination, $image_name) {

        switch ($imageType) {
            case 'jpg':
            case 'jpeg':
            case 'JPG':
            case 'JPEG':
                $source = imagecreatefromjpeg($image);
                break;
            case 'png':
            case 'PNG':
                $source = imagecreatefrompng($image);
                break;
            case 'GIF':
            case 'gif':
                $source = imagecreatefromgif($image);
                break;
        }
        list($width, $height) = getimagesize($image);



        $original_aspect = $width / $height;
        $thumb_aspect = $targ_w / $targ_h;

        if ($original_aspect == $thumb_aspect) {
            if ($width <= $targ_w && $height <= $targ_h) {
                $new_width = $width;
                $new_height = $height;
            } else {
                $new_height = $targ_h;
                $new_width = $targ_w;
            }
        } else if ($original_aspect > $thumb_aspect) {
            if ($width <= $targ_w && $height <= $targ_h) {
                $new_width = $width;
                $new_height = $height;
            } else {
                $new_width = $targ_w;
                $new_height = $height / ($width / $targ_w);
            }
        } else if ($original_aspect < $thumb_aspect) {
            if ($width <= $targ_w && $height <= $targ_h) {
                $new_width = $width;
                $new_height = $height;
            } else {
                $new_width = $width / ($height / $targ_h);
                $new_height = $targ_h;
            }
            // If the thumbnail is wider than the image
        }

        $thumb = imagecreatetruecolor($targ_w, $targ_h);

        imagefilledrectangle($thumb, 0, 0, $targ_w, $targ_h, imagecolorallocate($thumb, 255, 255, 255));

        // Resize and crop
        imagecopyresampled($thumb, $source, 0 - ($new_width - $targ_w) / 2, 0 - ($new_height - $targ_h) / 2, 0, 0, $new_width, $new_height, $width, $height);
        imagejpeg($thumb, $image_destination . $image_name, 75);

        return $image_name;
    }

    function kShuffle(&$array) {
        if (!is_array($array) || empty($array)) {
            return false;
        }
        $tmp = array();
        foreach ($array as $key => $value) {
            $tmp[] = array('k' => $key, 'v' => $value);
        }
        shuffle($tmp);
        $array = array();
        foreach ($tmp as $entry) {
            $array[$entry['k']] = $entry['v'];
        }
        return true;
    }

    function tagCloud() {
        $tmp = array();

        $tags = explode(",", parent::getValue("tags", "superadmin", "id", 1));
        $sql = "";
        foreach ($tags as $value) {
            $sql .= "lower(`tag`) != '" . strtolower(trim($value)) . "' AND ";
        }
        $sql = substr($sql, 0, strlen($sql) - 4);

        $query = $this->execQuery("SELECT question, tag, count(`tag`) AS br FROM tags WHERE $sql GROUP BY lower(`tag`) ORDER BY br DESC LIMIT 25");
        $i = 1;
        while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
            if ($i >= 1 && $i < 3)
                $font = 18;
            elseif ($i >= 3 && $i < 8)
                $font = 16;
            elseif ($i >= 8 && $i < 12)
                $font = 14;
            elseif ($i >= 12 && $i < 19)
                $font = 12;
            elseif ($i >= 19)
                $font = 10;

            $tmp[$data['tag']] = $font;
            $i++;
        }
        $this->kShuffle($tmp);

        foreach ($tmp as $key => $value) {
            echo "<a href=\"/tag/" . $key . "\" style=\"font-size: " . $value . "px;\">" . $key . "</a> ";
        }
    }

    function lastChar($int) {

        if ($int == 1)
            return "";
        else
            return "a";
    }

    function getValue($value, $content = '', $id = '', $data = '') {
        global $_POST, $_GET, $_SERVER;

        $REQUEST_METHOD = $_SERVER["REQUEST_METHOD"];

        if ($REQUEST_METHOD == 'POST') {
            $takenValue = $_POST["$value"];
        } else if ($REQUEST_METHOD == 'GET') {
            $takenValue = $_GET["$value"];
        }

        if (!is_array($takenValue)) {
            $takenValue = $this->stringCleaner($takenValue);
        }

        return $takenValue;
    }

    function getRequestValues() {
        $values = array();
        global $_POST, $_GET, $_SERVER;
        foreach ($_GET as $key => $value) {
            $values[$key] = $value;
        }

        foreach ($_POST as $key => $value) {
            $values[$key] = $value;
        }

        return $values;
    }

    function readLanguage() {

        if (isset($_GET['lang'])) {
            $lang = $_GET['lang'];
        } else {
            $lang = DEFAULT_LANG;
        }

        return $lang;
    }

    function mailValidator($email) {
        $result = true;
        if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email)) {
            $result = false;
        }
        return $result;
    }

    function passwordGeneration($length = 10, $possible = "0123456789abcdefghijklmnoprstuvwxyz") {
        $password = "";
        $i = 0;
        while ($i < $length) {
            $char = substr($possible, mt_rand(0, strlen($possible) - 1), 1);
            if (!strstr($password, $char)) {
                $password .= $char;
                $i++;
            }
        }
        return $password;
    }

    function stampa($string) {

        $string = str_replace("е", "e", $string);
        $string = str_replace("Е", "E", $string);

        $string = str_replace("р", "r", $string);
        $string = str_replace("Р", "R", $string);

        $string = str_replace("т", "t", $string);
        $string = str_replace("Т", "T", $string);

        $string = str_replace("з", "z", $string);
        $string = str_replace("З", "Z", $string);

        $string = str_replace("у", "u", $string);
        $string = str_replace("У", "U", $string);

        $string = str_replace("и", "i", $string);
        $string = str_replace("И", "I", $string);

        $string = str_replace("о", "o", $string);
        $string = str_replace("О", "O", $string);

        $string = str_replace("п", "p", $string);
        $string = str_replace("П", "P", $string);

        $string = str_replace("а", "a", $string);
        $string = str_replace("А", "A", $string);

        $string = str_replace("с", "s", $string);
        $string = str_replace("С", "S", $string);

        $string = str_replace("д", "d", $string);
        $string = str_replace("Д", "D", $string);

        $string = str_replace("ф", "f", $string);
        $string = str_replace("Ф", "F", $string);

        $string = str_replace("г", "g", $string);
        $string = str_replace("Г", "G", $string);

        $string = str_replace("х", "h", $string);
        $string = str_replace("Х", "H", $string);

        $string = str_replace("ј", "j", $string);
        $string = str_replace("Ј", "J", $string);

        $string = str_replace("к", "k", $string);
        $string = str_replace("К", "K", $string);

        $string = str_replace("л", "l", $string);
        $string = str_replace("Л", "L", $string);

        $string = str_replace("ц", "c", $string);
        $string = str_replace("Ц", "C", $string);

        $string = str_replace("в", "v", $string);
        $string = str_replace("В", "V", $string);

        $string = str_replace("б", "b", $string);
        $string = str_replace("Б", "B", $string);

        $string = str_replace("н", "n", $string);
        $string = str_replace("Н", "N", $string);

        $string = str_replace("м", "m", $string);
        $string = str_replace("М", "M", $string);

        $string = str_replace("ђ", "đ", $string);
        $string = str_replace("ж", "ž", $string);
        $string = str_replace("ш", "š", $string);

        $string = str_replace("ћ", "ć", $string);
        $string = str_replace("ч", "č", $string);

        $string = str_replace("Ђ", "Đ", $string);
        $string = str_replace("Ж", "Ž", $string);
        $string = str_replace("Ш", "Š", $string);
        $string = str_replace("Ћ", "Ć", $string);
        $string = str_replace("Ч", "Č", $string);

        $string = str_replace("љ", 'lj', $string);
        $string = str_replace("Љ", 'Lj', $string);

        $string = str_replace("Ђ", 'Đ', $string);
        $string = str_replace("ђ", 'đ', $string);

        $string = str_replace("њ", 'nj', $string);
        $string = str_replace("Њ", 'Nj', $string);

        $string = str_replace("џ", 'dž', $string);
        $string = str_replace("Џ", 'Dž', $string);



        return $string;
    }

    function generateUrlFromText($strText) {

        $strText = $this->stampa($strText);

        // RUSKI
        $strText = str_replace("Ё", "E", $strText);
        $strText = str_replace("ё", "e", $strText);
        $strText = str_replace("Й", "I", $strText);
        $strText = str_replace("й", "i", $strText);
        $strText = str_replace("Ъ", "ie", $strText);
        $strText = str_replace("ъ", "Y", $strText);
        $strText = str_replace("ь", "s", $strText);
        $strText = str_replace("Ы", "y", $strText);
        $strText = str_replace("ы", "u", $strText);
        $strText = str_replace("Э", "E", $strText);
        $strText = str_replace("э", "e", $strText);
        $strText = str_replace("Ю", "IU", $strText);
        $strText = str_replace("ю", "iu", $strText);
        $strText = str_replace("Я", "IA", $strText);
        $strText = str_replace("я", "ia", $strText);
        $strText = str_replace("ß", "s", $strText);

        // NEMACKI
        $strText = str_replace("Ä", "A", $strText);
        $strText = str_replace("ä", "a", $strText);
        $strText = str_replace("Ö", "O", $strText);
        $strText = str_replace("ö", "o", $strText);
        $strText = str_replace("Ü", "U", $strText);
        $strText = str_replace("ü", "u", $strText);

        // MADJARSKI
        $strText = str_replace("Ó", "O", $strText);
        $strText = str_replace("ó", "o", $strText);
        $strText = str_replace("Ő", "O", $strText);
        $strText = str_replace("ő", "o", $strText);
        $strText = str_replace("ÿ", "y", $strText);
        $strText = str_replace("Á", "A", $strText);
        $strText = str_replace("á", "a", $strText);
        $strText = str_replace("É", "E", $strText);
        $strText = str_replace("é", "e", $strText);
        $strText = str_replace("Í", "I", $strText);
        $strText = str_replace("í", "i", $strText);
        $strText = str_replace("ú", "u", $strText);
        $strText = str_replace("Ű", "U", $strText);
        $strText = str_replace("ű", "u", $strText);
        $strText = str_replace("ē", "e", $strText);
        $strText = str_replace("è", "e", $strText);
        $strText = str_replace("ȅ", "e", $strText);
        $strText = str_replace("Õ", "O", $strText);
        $strText = str_replace("õ", "o", $strText);
        $strText = str_replace("Û", "U", $strText);
        $strText = str_replace("û", "u", $strText);
        $strText = str_replace("ȅ", "e", $strText);
        $strText = str_replace("Ӳ", "Y", $strText);
        $strText = str_replace("ӳ", "y", $strText);

        //RUMUNSKI
        $strText = str_replace("Ă", "A", $strText);
        $strText = str_replace("ă", "a", $strText);
        $strText = str_replace("Â", "A", $strText);
        $strText = str_replace("â", "a", $strText);
        $strText = str_replace("Ț", "T", $strText);
        $strText = str_replace("ț", "t", $strText);
        $strText = str_replace("Î", "I", $strText);
        $strText = str_replace("î", "i", $strText);
        $strText = str_replace("Ș", "S", $strText);
        $strText = str_replace("ș", "s", $strText);


        $strText = str_replace("š", "s", $strText);
        $strText = str_replace("Š", "S", $strText);
        $strText = str_replace("ž", "z", $strText);
        $strText = str_replace("Ž", "z", $strText);
        $strText = str_replace("Č", "c", $strText);
        $strText = str_replace("č", "c", $strText);
        $strText = str_replace("Ć", "c", $strText);
        $strText = str_replace("ć", "c", $strText);
        $strText = str_replace("Đ", "Dj", $strText);
        $strText = str_replace("đ", "dj", $strText);
        $strText = preg_replace('/[^A-Za-z0-9-]/', ' ', $strText);
        $strText = preg_replace('/ +/', ' ', $strText);
        $strText = trim($strText);
        $strText = str_replace(' ', '-', $strText);
        $strText = preg_replace('/-+/', '-', $strText);
        $strText = strtolower($strText);
        return $strText;
    }

    function generateUrlFromTextFilter($strText) {
        $strText = urlencode($strText);
        return $strText;
    }

    function generateXmlTag($strText) {
        $strText = str_replace("š", "s", $strText);
        $strText = str_replace("Š", "s", $strText);
        $strText = str_replace("ž", "z", $strText);
        $strText = str_replace("Ž", "z", $strText);
        $strText = str_replace("Č", "c", $strText);
        $strText = str_replace("č", "c", $strText);
        $strText = str_replace("Ć", "c", $strText);
        $strText = str_replace("ć", "c", $strText);
        $strText = str_replace("Đ", "dj", $strText);
        $strText = str_replace("đ", "dj", $strText);
        $strText = preg_replace('/[^A-Za-z0-9_]/', ' ', $strText);
        $strText = preg_replace('/ +/', '_', $strText);
        $strText = trim($strText);
        $strText = str_replace(' ', '_', $strText);
        $strText = preg_replace('/_+/', '_', $strText);
        $strText = strtolower($strText);
        return $strText;
    }

    function generateTags($strText) {
        $strText = preg_replace('/[\[\]?!\.,\/\\"\'\(\)]/', ' ', $strText);
        $strText = preg_replace('/[ ]+/', ' ', $strText);
        $array = explode(" ", $strText);

        $tmpArray = array();
        for ($i = 0; $i < count($array); $i++) {
            if (strlen($array[$i]) > 4) {
                array_push($tmpArray, $array[$i]);
            }
        }

        return $tmpArray;
    }

    function debug($input) {
        echo "<pre>";
        var_dump($input);
        echo "</pre>";
    }

    function recognizeURL($text) {
        $regEx = "((www\.|http\.|(www|http|https|ftp|news|file)+\:\/\/)([_.a-z0-9-]+\.[a-zA-Z0-9\/_:@=.+?,##%&~-]*[^.|\'|\# |!|\(|?|,| |>|< |;|\)]))";
        $text = preg_replace($regEx, "<a href='$0'>$0</a>", $text);

        return $text;
    }

    function recognizeEmoticons($text) {
        $regEx = "((\:\))|(\;\))|(\:P)|(\:D)|(\:\*)|(\:\()|";
        $regEx .= "(\:\-\))|(\;\-\))|(\:\-P)|(\:\-D)|(\:\-\*)|(\:\-\())";
        $text = preg_replace($regEx, "<img src=\"/images/emoticons/smiley_$0.jpg\" />", $text);
        $text = str_replace("/smiley_:).jpg", "/smiley_1.jpg", $text);
        $text = str_replace("/smiley_;).jpg", "/smiley_2.jpg", $text);
        $text = str_replace("/smiley_:P.jpg", "/smiley_3.jpg", $text);
        $text = str_replace("/smiley_:D.jpg", "/smiley_4.jpg", $text);
        $text = str_replace("/smiley_:*.jpg", "/smiley_5.jpg", $text);
        $text = str_replace("/smiley_:(.jpg", "/smiley_7.jpg", $text);
        $text = str_replace("/smiley_:-).jpg", "/smiley_1.jpg", $text);
        $text = str_replace("/smiley_;-).jpg", "/smiley_2.jpg", $text);
        $text = str_replace("/smiley_:-P.jpg", "/smiley_3.jpg", $text);
        $text = str_replace("/smiley_:-D.jpg", "/smiley_4.jpg", $text);
        $text = str_replace("/smiley_:-*.jpg", "/smiley_5.jpg", $text);
        $text = str_replace("/smiley_:-(.jpg", "/smiley_7.jpg", $text);
        return $text;
    }

    function setMessage($string, $type = "success") {
        $_SESSION["message"] = $string;
        $_SESSION["messageType"] = $type;
    }

    function getMessage() {
        $string = $_SESSION["message"];
        $type = $_SESSION["messageType"];

        if (strlen($string) > 0) {
            unset($_SESSION["message"]);
            unset($_SESSION["messageType"]);
            ?>
            <div class="notification <?= $type; ?>" style="cursor: auto;"> 
                <div class="text"> 
                    <p>
                        <strong><?= ucfirst($type); ?>!</strong> 
                        <?= $string; ?>
                    </p> 
                </div> 
            </div>
            <?php
        }
    }

    function listLanguagesSelect($selected = 0) {
        $query = Database::execQuery("SELECT * FROM languages WHERE active = '1' ORDER BY name ASC");
        while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
            ?>
            <option value="<?= $data['id']; ?>" <?= ($data['id'] == $selected) ? "selected=\"selected\"" : ""; ?>><?= $data['name']; ?></option>
            <?php
        }
    }

    function getLanguageName($lang_id) {
        return Database::getValue("name", "languages", "id", $lang_id);
    }

    function recursive_remove_directory($directory, $empty = FALSE) {
        // if the path has a slash at the end we remove it here
        if (substr($directory, -1) == '/') {
            $directory = substr($directory, 0, -1);
        }

        // if the path is not valid or is not a directory ...
        if (!file_exists($directory) || !is_dir($directory)) {
            // ... we return false and exit the function
            return FALSE;

            // ... if the path is not readable
        } elseif (!is_readable($directory)) {
            // ... we return false and exit the function
            return FALSE;

            // ... else if the path is readable
        } else {

            // we open the directory
            $handle = opendir($directory);

            // and scan through the items inside
            while (FALSE !== ($item = readdir($handle))) {
                // if the filepointer is not the current directory
                // or the parent directory
                if ($item != '.' && $item != '..') {
                    // we build the new path to delete
                    $path = $directory . '/' . $item;

                    // if the new path is a directory
                    if (is_dir($path)) {
                        // we call this function with the new path
                        $this->recursive_remove_directory($path);

                        // if the new path is a file
                    } else {
                        // we remove the file
                        unlink($path);
                    }
                }
            }
            // close the directory
            closedir($handle);

            // if the option to empty is not set to true
            if ($empty == FALSE) {
                // try to delete the now empty directory
                if (!rmdir($directory)) {
                    // return false if not possible
                    return FALSE;
                }
            }
            // return success
            return TRUE;
        }
    }

    function fileUpload($field_name, $targetDir, $fileName, $exstensions) {

        if (isset($_FILES["$field_name"]) && $_FILES["$field_name"]['size'] > 0) {
            $tmp_name = $_FILES["$field_name"]["tmp_name"];
            $file_type = $_FILES["$field_name"]["type"];

            $getExt = explode('.', $_FILES["$field_name"]['name']);
            $file_ext = $getExt[count($getExt) - 1];

            $file_ext = strtolower($file_ext);

            $file_size = $_FILES["$field_name"]['size'];
            $niz = explode(",", $exstensions);

            if (!in_array($file_ext, $niz))
                die("Error: Only these picture extensions are allowed <strong>" . $exstensions . "</strong>");


            $name = $fileName . "." . $file_ext;
            $n = $targetDir . $name;

            move_uploaded_file($tmp_name, $n);

            return $name;
        }
    }

    function cropPictureISS($image, $targ_w, $targ_h, $imageType, $image_destination, $image_name) {
        switch ($imageType) {
            case 'jpg':
            case 'jpeg':
            case 'JPG':
            case 'JPEG':
                $source = imagecreatefromjpeg($image);
                break;
            case 'png':
            case 'PNG':
                $source = imagecreatefrompng($image);
                break;
            case 'GIF':
            case 'gif':
                $source = imagecreatefromgif($image);
                break;
        }
        list($width, $height) = getimagesize($image);

        $original_aspect = $width / $height;
        $thumb_aspect = $targ_w / $targ_h;

        if ($original_aspect >= $thumb_aspect) {
            // If image is wider than thumbnail (in aspect ratio sense)
            $new_height = $targ_h;
            $new_width = $width / ($height / $targ_h);
        } else {
            // If the thumbnail is wider than the image
            $new_width = $targ_w;
            $new_height = $height / ($width / $targ_w);
        }

        $thumb = imagecreatetruecolor($targ_w, $targ_h);
        imagefilledrectangle($thumb, 0, 0, $targ_w, $targ_h, imagecolorallocate($thumb, 255, 255, 255));

        // Resize and crop
        imagecopyresampled($thumb, $source, 0 - ($new_width - $targ_w) / 2, 0 - ($new_height - $targ_h) / 2, 0, 0, $new_width, $new_height, $width, $height);
        imagejpeg($thumb, $image_destination . $image_name, 75);

        return $image_name;
    }

    function scalePicture($image, $image_destination, $crop_resize, $targ_w, $targ_h, $image_name, $imageType) {
        /* IF ITS RESIZE */
        if ($crop_resize == 2) {

            switch ($imageType) {
                case 'jpg':
                case 'jpeg':
                case 'JPG':
                case 'JPEG':
                    $source = imagecreatefromjpeg($image);
                    break;
                case 'png':
                case 'PNG':
                    $source = imagecreatefrompng($image);
                    break;
                case 'GIF':
                case 'gif':
                    $source = imagecreatefromgif($image);
                    break;
            }

            // Get new sizes
            list($width, $height) = getimagesize($image);
            /* IF THE PICTURE IS WIDER THAN A TALLER */
            if ($width > $height) {
                $newwidth = $targ_w;
                $newheight = round($targ_w * $height / $width);
            } elseif ($height > $width) {
                $newheight = $targ_h;
                $newwidth = round($targ_w * $width / $height);
            } elseif ($width == $height) {
                $newheight = $targ_w;
                $newwidth = $targ_w;
            }


            // Load
            $thumb = imagecreatetruecolor($newwidth, $newheight);
            imagefilledrectangle($thumb, 0, 0, $newwidth, $newheight, imagecolorallocate($thumb, 255, 255, 255));

            // Resize
            imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

            // Output
            imagejpeg($thumb, $image_destination . $image_name, 75);
        } else {
            switch ($imageType) {
                case 'jpg':
                case 'jpeg':
                case 'JPG':
                case 'JPEG':
                    $source = imagecreatefromjpeg($image);
                    break;
                case 'png':
                case 'PNG':
                    $source = imagecreatefrompng($image);
                    break;
                case 'GIF':
                case 'gif':
                    $source = imagecreatefromgif($image);
                    break;
            }
            list($width, $height) = getimagesize($image);

            $original_aspect = $width / $height;
            $thumb_aspect = $targ_w / $targ_h;

            if ($original_aspect >= $thumb_aspect) {
                // If image is wider than thumbnail (in aspect ratio sense)
                $new_height = $targ_h;
                $new_width = $width / ($height / $targ_h);
            } else {
                // If the thumbnail is wider than the image
                $new_width = $targ_w;
                $new_height = $height / ($width / $targ_w);
            }

            $thumb = imagecreatetruecolor($targ_w, $targ_h);
            imagefilledrectangle($thumb, 0, 0, $targ_w, $targ_h, imagecolorallocate($thumb, 255, 255, 255));

            // Resize and crop
            imagecopyresampled($thumb, $source, 0 - ($new_width - $targ_w) / 2, 0 - ($new_height - $targ_h) / 2, 0, 0, $new_width, $new_height, $width, $height);
            imagejpeg($thumb, $image_destination . $image_name, 75);
        }
    }

    function cropPictureAddWhiteSpace($image, $targ_w, $targ_h, $imageType, $destinationFolder, $newImageName) {

        $width = $this->getImageWidth($image);
        $height = $this->getImageHeight($image);

        $jpeg_quality = 75;

        switch ($imageType) {
            case 'jpg':
            case 'jpeg':
            case 'JPG':
            case 'JPEG':
                $source = imagecreatefromjpeg($image);
                break;
            case 'png':
            case 'PNG':
                $source = imagecreatefrompng($image);
                break;
            case 'GIF':
            case 'gif':
                $source = imagecreatefromgif($image);
                break;
        }

        $resizeImage = new ResizeImage();

        if ($width == $targ_w && $height == $targ_h) {
            copy($image, $destinationFolder . $newImageName) or die("eror");
        } else {

            if ($targ_w == $targ_h) {

                if ($width > $height) {

                    $ratio = $targ_w / $width;
                    $new_height = $height * $ratio;

                    $resizeImage->load($image);
                    $resizeImage->resizeToWidth($targ_w);
                    $resizeImage->save($destinationFolder . $newImageName);

                    $difference = $width - $height;
                    $half = $difference / 2;
                    $half = round($half);

                    $difference = $targ_w - $new_height;
                    $half = $difference / 2;
                    $half = round($half);

                    $cropX = 0;
                    $cropY = $half;

                    $originalFile = $destinationFolder . $newImageName;
                    $im = imagecreatefromjpeg($originalFile);

                    $dst_r = ImageCreateTrueColor($targ_w, $targ_h);
                    imagefilledrectangle($dst_r, 0, 0, $targ_w, $targ_h, imagecolorallocate($dst_r, 255, 255, 255));

                    imagecopy($dst_r, $im, $cropX, $cropY, 0, 0, imagesx($im), imagesy($im));
                    imagejpeg($dst_r, $originalFile, $jpeg_quality);
                } else if ($width < $height) {

                    $ratio = $targ_h / $height;
                    $new_width = $width * $ratio;

                    $resizeImage->load($image);
                    $resizeImage->resizeToHeight($targ_h);
                    $resizeImage->save($destinationFolder . $newImageName);

                    $difference = $targ_h - $new_width;
                    $half = $difference / 2;
                    $half = round($half);

                    $cropX = $half;
                    $cropY = 0;

                    $originalFile = $destinationFolder . $newImageName;
                    $im = imagecreatefromjpeg($originalFile);

                    $dst_r = ImageCreateTrueColor($targ_w, $targ_h);
                    imagefilledrectangle($dst_r, 0, 0, $targ_w, $targ_h, imagecolorallocate($dst_r, 255, 255, 255));

                    imagecopy($dst_r, $im, $cropX, $cropY, 0, 0, imagesx($im), imagesy($im));
                    imagejpeg($dst_r, $originalFile, $jpeg_quality);
                } else if ($width == $height) {

                    $resizeImage->load($image);
                    $resizeImage->resize($targ_w, $targ_h);
                    $resizeImage->save($destinationFolder . $newImageName);
                }
            } else if ($targ_w > $targ_h) {

                if ($width > $height) {

                    $scale = ($targ_w / $width) * 100;
                    $new_height = $height * $scale / 100;
                    $new_width = $width * $scale / 100;

                    if ($new_height > $targ_h) {
                        $scale = ($targ_h / $height) * 100;
                        $new_height = $height * $scale / 100;
                        $new_width = $width * $scale / 100;
                    }

                    $difference_x = $targ_w - $new_width;
                    $half_x = $difference_x / 2;
                    $half_x = round($half_x);

                    $difference_y = $targ_h - $new_height;
                    $half_y = $difference_y / 2;
                    $half_y = round($half_y);

                    $cropX = $half_x;
                    $cropY = $half_y;
           
                    $resizeImage->load($image);
                    $resizeImage->scale($scale);
                    //$resizeImage->resize($targ_w, $targ_h);
                    $resizeImage->save($destinationFolder . $newImageName);

                    $originalFile = $destinationFolder . $newImageName;
                    $im = imagecreatefromjpeg($originalFile);

                    $dst_r = ImageCreateTrueColor($targ_w, $targ_h);
                    imagefilledrectangle($dst_r, 0, 0, $targ_w, $targ_h, imagecolorallocate($dst_r, 255, 255, 255));

                    imagecopy($dst_r, $im, $cropX, $cropY, 0, 0, imagesx($im), imagesy($im));
                    imagejpeg($dst_r, $originalFile, $jpeg_quality);
                } else if ($height > $width) {

                    $scale = ($targ_h / $height) * 100;
                    $new_height = $height * $scale / 100;
                    $new_width = $width * $scale / 100;

                    if ($new_width > $targ_w) {
                        $scale = ($targ_w / $width) * 100;
                        $new_height = $height * $scale / 100;
                        $new_width = $width * $scale / 100;
                    }

                    $difference_x = $targ_w - $new_width;
                    $half_x = $difference_x / 2;
                    $half_x = round($half_x);

                    $difference_y = $targ_h - $new_height;
                    $half_y = $difference_y / 2;
                    $half_y = round($half_y);

                    $cropX = $half_x;
                    $cropY = $half_y;

                    $resizeImage->load($image);
                    $resizeImage->scale($scale);
                    //$resizeImage->resize($targ_w, $targ_h);
                    $resizeImage->save($destinationFolder . $newImageName);

                    $originalFile = $destinationFolder . $newImageName;
                    $im = imagecreatefromjpeg($originalFile);

                    $dst_r = ImageCreateTrueColor($targ_w, $targ_h);
                    imagefilledrectangle($dst_r, 0, 0, $targ_w, $targ_h, imagecolorallocate($dst_r, 255, 255, 255));

                    imagecopy($dst_r, $im, $cropX, $cropY, 0, 0, imagesx($im), imagesy($im));
                    imagejpeg($dst_r, $originalFile, $jpeg_quality);
                } else if ($width == $height) {

                    $scale = ($targ_h / $height) * 100;
                    $new_height = $height * $scale / 100;
                    $new_width = $width * $scale / 100;

                    $difference_x = $targ_w - $new_width;
                    $half_x = $difference_x / 2;
                    $half_x = round($half_x);

                    $difference_y = $targ_h - $new_height;
                    $half_y = $difference_y / 2;
                    $half_y = round($half_y);

                    $cropX = $half_x;
                    $cropY = $half_y;

                    $resizeImage->load($image);
                    $resizeImage->scale($scale);
                    //$resizeImage->resize($targ_w, $targ_h);
                    $resizeImage->save($destinationFolder . $newImageName);

                    $originalFile = $destinationFolder . $newImageName;
                    $im = imagecreatefromjpeg($originalFile);

                    $dst_r = ImageCreateTrueColor($targ_w, $targ_h);
                    imagefilledrectangle($dst_r, 0, 0, $targ_w, $targ_h, imagecolorallocate($dst_r, 255, 255, 255));

                    imagecopy($dst_r, $im, $cropX, $cropY, 0, 0, imagesx($im), imagesy($im));
                    imagejpeg($dst_r, $originalFile, $jpeg_quality);
                }
            } else if ($targ_w < $targ_h) {

                if ($width > $height) {

                    $new_w = ($height * $targ_w) / $targ_h;
                    $difference = $width - $new_w;
                    $half = $difference / 2;
                    $half = round($half);

                    $cropWidth = $new_w;
                    $cropHeight = $height;
                    $cropX = 0;
                    $cropY = 0;
                } else if ($width < $height) {

                    $new_h = ($width * $targ_h) / $targ_w;
                    $difference = $height - $new_h;
                    $half = $difference / 2;
                    $half = round($half);

                    $cropWidth = $width;
                    $cropHeight = $new_h;
                    $cropX = 0;
                    $cropY = 0;
                } else if ($width == $height) {

                    $new_w = ($height * $targ_w) / $targ_h;
                    $difference = $width - $new_w;
                    $half = $difference / 2;
                    $half = round($half);

                    $cropWidth = $new_w;
                    $cropHeight = $height;
                    $cropX = 0;
                    $cropY = 0;
                }
            }
        }

        $stamp = "../../uploaded_pictures/watermarks/" . $targ_w . "x" . $targ_h . ".png";
        if (is_file($stamp)) {
            $stamp = imagecreatefrompng($stamp);
            $originalFile = $destinationFolder . $newImageName;
            $im = imagecreatefromjpeg($originalFile);
            imagecopy($im, $stamp, 0, 0, 0, 0, imagesx($stamp), imagesy($stamp));
            imagejpeg($im, $originalFile, $jpeg_quality);
            imagedestroy($im);
        }

        return $newImageName;
    }

    function newCropImage($image, $targ_w, $targ_h, $imageType, $destinationFolder, $newImageName) {

        $width = $this->getImageWidth($image);
        $height = $this->getImageHeight($image);

        $jpeg_quality = 100;

        switch ($imageType) {
            case 'jpg':
            case 'jpeg':
            case 'JPG':
            case 'JPEG':
                $source = imagecreatefromjpeg($image);
                break;
            case 'png':
            case 'PNG':
                $source = imagecreatefrompng($image);
                break;
            case 'GIF':
            case 'gif':
                $source = imagecreatefromgif($image);
                break;
        }

        if ($width == $targ_w && $height == $targ_h) {
            copy($image, $destinationFolder . $newImageName) or die("eror");
        } else {

            if ($targ_w == $targ_h) {

                if ($width > $height) {

                    $difference = $width - $height;
                    $half = $difference / 2;
                    $half = round($half);

                    $cropX = $half;
                    $cropY = 0;
                    $cropWidth = $height;
                    $cropHeight = $height;
                } else if ($width < $height) {

                    $difference = $height - $width;
                    $half = $difference / 2;
                    $half = round($half);

                    $cropX = 0;
                    $cropY = $half;
                    $cropWidth = $width;
                    $cropHeight = $width;
                } else if ($width == $height) {

                    $cropWidth = $width;
                    $cropHeight = $height;
                    $cropX = 0;
                    $cropY = 0;
                }
            } else if ($targ_w > $targ_h) {

                if ($width > $height) {

                    $new_h = ($width * $targ_h) / $targ_w;
                    $difference = $height - $new_h;
                    $half = $difference / 2;
                    $half = round($half);

                    $cropWidth = $width;
                    $cropHeight = $new_h;
                    $cropX = 0;
                    $cropY = $half;
                } else if ($height > $width) {

                    $new_h = ($width * $targ_h) / $targ_w;
                    $difference = $height - $new_h;
                    $half = $difference / 2;
                    $half = round($half);

                    $cropWidth = $width;
                    $cropHeight = $new_h;
                    $cropX = 0;
                    $cropY = $half;
                } else if ($width == $height) {

                    $new_h = ($width * $targ_h) / $targ_w;
                    $difference = $height - $new_h;
                    $half = $difference / 2;
                    $half = round($half);

                    $cropWidth = $width;
                    $cropHeight = $new_h;
                    $cropX = 0;
                    $cropY = $half;
                }
            } else if ($targ_w < $targ_h) {

                if ($width > $height) {

                    $new_w = ($height * $targ_w) / $targ_h;
                    $difference = $width - $new_w;
                    $half = $difference / 2;
                    $half = round($half);

                    $cropWidth = $new_w;
                    $cropHeight = $height;
                    $cropX = $half;
                    $cropY = 0;
                } else if ($width < $height) {

                    $new_h = ($width * $targ_h) / $targ_w;
                    $difference = $height - $new_h;
                    $half = $difference / 2;
                    $half = round($half);

                    $cropWidth = $width;
                    $cropHeight = $new_h;
                    $cropX = 0;
                    $cropY = $half;
                } else if ($width == $height) {

                    $new_w = ($height * $targ_w) / $targ_h;
                    $difference = $width - $new_w;
                    $half = $difference / 2;
                    $half = round($half);

                    $cropWidth = $new_w;
                    $cropHeight = $height;
                    $cropX = $half;
                    $cropY = 0;
                }
            }

            $dst_r = ImageCreateTrueColor($targ_w, $targ_h);
            imagefilledrectangle($dst_r, 0, 0, $targ_w, $targ_h, imagecolorallocate($dst_r, 255, 255, 255));

            imagecopyresampled($dst_r, $simg, 0, 0, $cropX, $cropY, $targ_w, $targ_h, $cropWidth, $cropHeight);

            imagejpeg($dst_r, $destinationFolder . $newImageName, $jpeg_quality);
        }

        return $newImageName;
    }

    function sizeOfFile($file_size) {
        if ($file_size >= 1048576) {
            $show_filesize = number_format(($file_size / 1048576), 2) . " MB";
        } elseif ($file_size >= 1024) {
            $show_filesize = number_format(($file_size / 1024), 2) . " KB";
        } elseif ($file_size >= 0) {
            $show_filesize = $file_size . " b";
        } else {
            $show_filesize = "0 b";
        }
        return $show_filesize;
    }

    /**
     * Returns the list of all categories
     *
     */
    function listCategoriesSelect() {

        $query = Database::execQuery("SELECT * FROM categories ORDER BY `ordering` ASC");
        while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
            ?>
            <option value="<?= $data['id']; ?>"><?= $data['name']; ?></option>
            <?php
        }
    }

    /**
     * Returns the list of all categories for right box
     *
     */
    function listCategoriesBox() {

        $query = Database::execQuery("SELECT * FROM categories ORDER BY `ordering` ASC");
        while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
            ?>
            <a href="/<?= $data['url']; ?>"><?= $data['name']; ?></a>
            <?php
        }
    }

    function createPagination($url_link, $page, $resultCount, $limit) {

        echo "<div id=\"pagination\" align=\"center\">";

        $totalPages = ceil($resultCount / $limit);


        $firstPage = ($page == 1) ? 0 : 1;
        $lastPage = ($page == $totalPages) ? 0 : $totalPages;
        $prevPage = ($page - 1 <= 0) ? 0 : $page - 1;
        $nextPage = ($page + 1 <= $totalPages) ? $page + 1 : 0;

        if ($page < 6) {
            $i = 1;
            $countTill = ($totalPages > 10) ? 10 : $totalPages;
        } else {
            $i = $page - 5;
            $countTill = ($page + 5 <= $totalPages) ? $page + 5 : $totalPages;
        }

        // printing first page link
        echo "<div>";
        if ($firstPage != 0) {
            echo "<a href=\"" . $url_link . "strana/" . $firstPage . "\">Prva</a> ";
        } else {
            echo "Prva ";
        }
        echo "</div>";

        // printing previous page link
        echo "<div>";
        if ($prevPage != 0) {
            echo "<a href=\"" . $url_link . "strana/" . $prevPage . "\">Prethodna</a> ";
        } else {
            echo "Prethodna ";
        }
        echo "</div>";

        // printing middle pages
        echo "<div>";
        for ($i; $i <= $countTill; $i++) {
            if ($page == $i)
                $additionalClass = " active";
            else
                $additionalClass = "";
            echo "<a href=\"" . $url_link . "strana/" . $i . "\" class=\"number $additionalClass\">" . $i . "</a> ";
        }
        echo "</div>";

        // printing next page link
        echo "<div>";
        if ($nextPage != 0) {
            echo "<a href=\"" . $url_link . "strana/" . $nextPage . "\">Sledeća</a> ";
        } else {
            echo "Sledeća ";
        }
        echo "</div>";

        // printing last page link
        echo "<div>";
        if ($lastPage != 0) {
            echo "<a href=\"" . $url_link . "strana/" . $lastPage . "\">Poslednja</a> ";
        } else {
            echo "Poslednja ";
        }
        echo "</div>";

        echo "</div>";
    }

    function createAdminPagination($url_link, $page, $resultCount, $limit) {

        echo "<div id=\"pagination\" align=\"left\">";

        $totalPages = ceil($resultCount / $limit);


        $firstPage = ($page == 1) ? 0 : 1;
        $lastPage = ($page == $totalPages) ? 0 : $totalPages;
        $prevPage = ($page - 1 <= 0) ? 0 : $page - 1;
        $nextPage = ($page + 1 <= $totalPages) ? $page + 1 : 0;

        if ($page < 6) {
            $i = 1;
            $countTill = ($totalPages > 10) ? 10 : $totalPages;
        } else {
            $i = $page - 5;
            $countTill = ($page + 5 <= $totalPages) ? $page + 5 : $totalPages;
        }

        // printing first page link
        echo "<div>";
        if ($firstPage != 0) {
            echo "<a href=\"" . $url_link . "page=" . $firstPage . "\">First</a> ";
        } else {
            echo "First ";
        }
        echo "</div>";

        // printing previous page link
        echo "<div>";
        if ($prevPage != 0) {
            echo "<a href=\"" . $url_link . "page=" . $prevPage . "\">Previous</a> ";
        } else {
            echo "Previous ";
        }
        echo "</div>";

        // printing middle pages
        echo "<div>";
        for ($i; $i <= $countTill; $i++) {
            if ($page == $i)
                $additionalClass = " active";
            else
                $additionalClass = "";
            echo "<a href=\"" . $url_link . "page=" . $i . "\" class=\"number $additionalClass\">" . $i . "</a> ";
        }
        echo "</div>";

        // printing next page link
        echo "<div>";
        if ($nextPage != 0) {
            echo "<a href=\"" . $url_link . "page=" . $nextPage . "\">Next</a> ";
        } else {
            echo "Next ";
        }
        echo "</div>";

        // printing last page link
        echo "<div>";
        if ($lastPage != 0) {
            echo "<a href=\"" . $url_link . "page=" . $lastPage . "\">Last</a> ";
        } else {
            echo "Last ";
        }
        echo "</div>";

        echo "</div>";
    }

    function listMembers($filter) {
        global $categoryId;

        if ($filter == "new") {
            $sql = "SELECT users.*, user_info.id AS uiid FROM `users` JOIN user_info ON users.id = user_info.user WHERE `approved` = '1' ORDER BY `uiid` DESC LIMIT " . LIST_MEMBERS_LIMIT;
        } elseif ($filter == "best") {
            $sql = "SELECT users.*, user_info.points FROM `users` JOIN user_info ON users.id = user_info.user WHERE `approved` = '1' ORDER BY `points` DESC LIMIT 5";
        } elseif ($filter == "promote") {
            $sql = "SELECT users.* FROM `users` WHERE `approved` = '1' AND `sponsor` = '1' ORDER BY RAND() LIMIT 1";
        } elseif ($filter == "category") {

            $newQ = parent::getValue("points", "activity", "activity", "new_question");
            $newA = parent::getValue("points", "activity", "activity", "new_answer");
            $setLD = parent::getValue("points", "activity", "activity", "set_like");

            $sql = "SELECT *, SUM(poeni1) as poeni FROM (
							SELECT users.*, count(users.id) * $newQ AS poeni1 FROM users JOIN questions ON questions.author = users.id WHERE category = '$categoryId' GROUP BY users.id
							UNION
							SELECT users.*, count(users.id) * $newA AS poeni1 FROM users JOIN answers ON answers.author = users.id WHERE category = '$categoryId' GROUP BY users.id
							UNION
							SELECT users.*, count(users.id) * $setLD AS poeni1 FROM users JOIN answer_likes_dislikes ON answer_likes_dislikes.user = users.id JOIN answers ON answers.id = answer_likes_dislikes.answer WHERE answers.category = '$categoryId'
							) AS pom GROUP BY id ORDER BY poeni DESC LIMIT 5";
        }

        $query = Database::execQuery($sql);
        $i = 1;
        while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
            $tmpU = new User($data['id']);
            $avatar = $tmpU->getAvatar();
            if ($filter == "promote") {
                ?>
                <a href="/profil/<?= $data['id']; ?>" class="tooltip" title="<?= $data['username']; ?>">
                    <img src="<?= $avatar; ?>" border="0" alt="" width="36" height="36">
                </a>
                <h4><a href="/profil/<?= $data['id']; ?>"><?= $this->printData($data['name']); ?></a></h4>
                <p>
                    <?= $this->printData(nl2br($data['description'])); ?>
                </p>
                <?php
            } elseif ($filter == "category") {
                if ($i == 1)
                    $topclass = " class=\"top\"";
                else
                    $topclass = "";

                echo "<div class=\"najbolji\">";
                echo "<span class=\"rb\">" . $i . ".</span> <a href=\"/profil/" . $data['id'] . "\" class=\"tooltip\" title=\"" . $data['username'] . "\">" .
                "<img $topclass src=\"" . $avatar . "\" border=\"0\" alt=\"\" width=\"24\" height=\"24\" />" .
                "</a>";
                echo "<span class=\"username\">" . $this->printData($data['username']) . "</span>";
                echo "<span class=\"poena\"><strong>" . $data['poeni'] . "</strong>p</span>";
                echo "</div>";
                $i++;
            } elseif ($filter == "best") {
                if ($i == 1)
                    $topclass = " class=\"top\"";
                else
                    $topclass = "";

                echo "<div class=\"najbolji\">";
                echo "<span class=\"rb\">" . $i . ".</span> <a href=\"/profil/" . $data['id'] . "\" class=\"tooltip\" title=\"" . $data['username'] . "\">" .
                "<img $topclass src=\"" . $avatar . "\" border=\"0\" alt=\"\" width=\"24\" height=\"24\" />" .
                "</a>";
                echo "<span class=\"username\">" . $this->printData($data['username']) . "</span>";
                echo "<span class=\"poena\"><strong>" . $data['points'] . "</strong>p</span>";
                echo "</div>";
                $i++;
            } else
                echo "<a href=\"/profil/" . $data['id'] . "\" class=\"tooltip\" title=\"" . $data['username'] . "\"><img src=\"" . $avatar . "\" border=\"0\" alt=\"\" width=\"36\" height=\"36\" /></a>";
        }

        if ($filter == "category" || $filter == "best")
            echo "<a style='float: right;' href=\"/najbolji-korisnici/" . $categoryId . "\">Kompletna lista</a>";
    }

    function listBestUsers() {
        global $page, $limit, $categoryId;

        $newQ = parent::getValue("points", "activity", "activity", "new_question");
        $newA = parent::getValue("points", "activity", "activity", "new_answer");
        $setLD = parent::getValue("points", "activity", "activity", "set_like");

        $sql = "SELECT *, SUM(poeni1) as poeni FROM (
						SELECT users.*, count(users.id) * $newQ AS poeni1 FROM users JOIN questions ON questions.author = users.id WHERE category = '$categoryId' GROUP BY users.id
						UNION
						SELECT users.*, count(users.id) * $newA AS poeni1 FROM users JOIN answers ON answers.author = users.id WHERE category = '$categoryId' GROUP BY users.id
						UNION
						SELECT users.*, count(users.id) * $setLD AS poeni1 FROM users JOIN answer_likes_dislikes ON answer_likes_dislikes.user = users.id JOIN answers ON answers.id = answer_likes_dislikes.answer WHERE answers.category = '$categoryId'
						) AS pom GROUP BY id ORDER BY poeni DESC";

        $resultCount = parent::numRows($sql); /* "SELECT users.* FROM user_info JOIN users ON user_info.user = users.id
          WHERE approved = '1' ORDER BY points DESC");
         */
        $offset = $page * $limit - $limit;
        $query = Database::execQuery($sql . " LIMIT $offset, $limit"); /* "SELECT users.*, points FROM user_info JOIN users ON user_info.user = users.id
          WHERE approved = '1' ORDER BY points DESC LIMIT $offset, $limit");
         */
        $i = $offset + 1;
        while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {

            if ($i == 1)
                $topclass = " sponsor";
            else
                $topclass = "";

            if ($data['id'] != 0) {
                $tmpU = new User($data['id']);
                ?>
                <div class="item <?= $topclass; ?> profile">
                    <div class="rb">
                        <?= $i; ?>
                    </div>
                    <a href="/profil/<?= $data['id']; ?>" class="avatar">
                        <img src="<?= $tmpU->getAvatar(); ?>" alt="<?= $this->printData($data['username']); ?>" />
                    </a>

                    <div class="quest-div">
                        <h3><a href="/profil/<?= $data['id']; ?>"><?= $this->printData($data['username']); ?></a></h3>
                    </div>

                    <div class="points"><big><?= $data['poeni']; ?></big> poena</div>
                </div>
                <?php
                $i++;
            }
        }

        $this->createPagination("/najbolji-korisnici/" . $categoryId . "/", $page, $resultCount, $limit);
    }

    function getPageTitle($pageURL) {
        return Database::getValue("title", "pages", "url", $pageURL);
    }

    function getPageBody($pageURL) {
        return Database::getValue("text", "pages", "url", $pageURL);
    }

    function sendEmail($emailFrom, $fromName, $emailTo, $subject, $body) {

        require_once("../../library/phpmailer/class.phpmailer.php");

        $mail = new PHPMailer();
        $mail->From = $emailFrom;
        $mail->FromName = $fromName;
        $mail->AddAddress($emailTo);
        $mail->Subject = $subject;
        $mail->Body = $body;

        if (!$mail->Send()) {
            echo 'Poruka nije poslata na adresu: ' . $mail->ErrorInfo . '<br />';
        }
    }

    function countInbox($i_user) {

        $total = Database::numRows("SELECT * FROM messages WHERE receiver = '$i_user' AND deleted_receiver = '0' AND child_of = '0'");
        $unread = Database::numRows("SELECT * FROM messages WHERE receiver = '$i_user' AND deleted_receiver = '0' AND `read` = '0' AND child_of = '0'");

        return $total . "::" . $unread;
    }

    function countSent($i_user) {

        $total = Database::numRows("SELECT * FROM messages WHERE author = '$i_user' AND deleted_author = '0' AND child_of = '0'");

        return $total;
    }

    function listInbox($SQL, $limit, $page) {
        global $userId, $resultCount;

        $upit = Database::execQuery($SQL);
        $resultCount = mysql_num_rows($upit);

        $offset = $page * $limit - $limit;

        $upit = Database::execQuery($SQL . " LIMIT " . $offset . ", " . $limit);
        while ($data = mysql_fetch_array($upit, MYSQL_ASSOC)) {
            $tmpU = new User($data['author']);
            $avatar = $tmpU->getAvatar();
            $author_name = $tmpU->getUsername();
            $profile_link = "/profil/" . $data['author'];
            ?>
            <div style="background-color:#FAFAFA; width: 690px; margin-bottom:5px; padding:5px;">
                <div style="width:55px; float:left; padding:3px;">
                    <a href="<?= $profile_link; ?>" class="left tooltip" title="<?= $author_name; ?>">
                        <img src="<?= $avatar; ?>" />
                    </a>
                </div>
                <div style="width:120px; float:left; padding:12px 2px;">
                    <a href="<?= $profile_link; ?>">
                        <?= ($data['read'] != 1) ? "<strong>" . $this->printData($author_name) . "</strong>" : $this->printData($author_name); ?>
                    </a> 
                </div>
                <div style="width:320px; float:left; padding:12px 2px;">
                    <a href="/inbox/message/<?= $data['i_message']; ?>#read">
                        <?php
                        $subject = ($data['has_reply'] != 0) ? "Re: " . $data['subject'] : $data['subject'];
                        echo ($data['read'] != 1) ? "<strong>" . $this->printData($subject) . "</strong>" : $this->printData($subject);
                        ?>
                    </a>
                </div>
                <div style="width:100px; font-size: 10px; float:left; padding:12px 2px;">
                    <?= date(DATE_FORMAT_LONG, strtotime($data['date'])); ?>
                </div>
                <div style="width:70px; float:left; text-align: center; margin-top:10px;"><a href="javascript:void(0);" id="delete_link_<?= $data['i_message']; ?>" onclick="deleteMessage('<?= $data['i_message']; ?>');">Izbriši</a></div>
                <div class="clear"></div>
            </div>

            <?php
        }
    }

    function listSent($SQL, $limit, $page) {
        global $userId, $resultCount;

        $upit = Database::execQuery($SQL);

        $resultCount = mysql_num_rows($upit);

        $offset = $page * $limit - $limit;

        $upit = Database::execQuery($SQL . " LIMIT " . $offset . ", " . $limit);
        while ($data = mysql_fetch_array($upit, MYSQL_ASSOC)) {
            $tmpU = new User($data['author']);
            $avatar = $tmpU->getAvatar();
            $author_name = $tmpU->getUsername();
            $profile_link = "/profil/" . $data['author'];
            ?>
            <div style="background-color:#fafafa; margin-bottom:5px; padding:5px;">
                <div style="width:54px; float:left; padding:3px;">

                    <a href="<?= $profile_link; ?>" class="left tooltip" title="<?= $author_name; ?>">
                        <img src="<?= $avatar; ?>" />
                    </a>
                </div>
                <div style="width:120px; float:left; padding:12px 2px;">
                    <a href="<?= $profile_link; ?>">
                        <?= $this->printData($author_name); ?>
                    </a> 
                </div>
                <div style="width:320px; float:left; padding:12px 2px;">
                    <a href="/inbox/sentmessage/<?= $data['i_message']; ?>#read">
                        <?= $this->printData($data['subject']); ?>
                    </a>
                </div>
                <div style="width:100px; font-size: 10px; float:left; padding:12px 2px;">
                    <?= date(DATE_FORMAT_LONG, strtotime($data['date'])); ?>
                </div>
                <div style="width:70px; float:left; text-align: center; margin-top:10px;"><a href="javascript:void(0);" id="delete_link_<?= $data['i_message']; ?>" onclick="deleteMessage('<?= $data['i_message']; ?>');">Izbriši</a></div>
                <div class="clear"></div>
            </div>

            <?php
        }
    }

    function formatBytes($bytes, $precision = 2) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    function printSelectOption($table, $valueField, $nameField, $lang_id = "") {
        $array[$table] = array();
        if ($lang_id != "") {
            $query = Database::execQuery("SELECT $valueField, $nameField FROM $table WHERE lang_id='$lang_id' ORDER BY $nameField");
        } else {
            $query = Database::execQuery("SELECT $valueField, $nameField FROM $table ORDER BY $nameField");
        }
        while ($data = mysql_fetch_array($query)) {
            $help = array($data[$valueField] => "$data[$nameField]");
            array_push($array[$table], $help);
        }

        return $array;
    }

    function printlanguageOptions($selected) {
        $query = Database::execQuery("SELECT * FROM languages ORDER BY ordering");
        while ($data = mysql_fetch_array($query)) {
            ?>
            <option value="<?= $data['id']; ?>" <?php if ($data['id'] == $selected) echo 'selected="selected"'; ?>><?= $data['title']; ?></option>
            <?php
        }
    }

    function makeNormalDate($date) {
        list($y, $m, $d) = explode("-", $date);
        return $d . "." . $m . "." . $y . ".";
    }

    function makeNormalDateTime($datetime) {
        list($date, $time) = explode(" ", $datetime);
        list($h, $min, $s) = explode(":", $time);
        list($y, $m, $d) = explode("-", $date);
        return $d . "." . $m . "." . $y . ". | $h:$min";
    }

    function getImageHeight($image) {
        $size = getimagesize($image);
        $height = $size[1];
        return $height;
    }

    function getImageWidth($image) {
        $size = getimagesize($image);
        $width = $size[0];
        return $width;
    }

    function printStatistic($lang_id) {

        $lang_name = Database::getValue("title", "languages", "id", $lang_id);
        ?>
        <h2>Statistics for <?= $lang_name; ?></h2>
        <?php
        $num_categories = Database::numRows("SELECT * FROM categories WHERE lang_id='$lang_id'");
        ?>
        <p><b>Categories:</b> <?= $num_categories; ?></p>
        <?php
        $query_t = Database::execQuery("SELECT * FROM content_type ORDER BY id");
        while ($data_t = mysql_fetch_array($query_t)) {
            $num_content_active = Database::numRows("SELECT * FROM content WHERE type='$data_t[id]' AND status='1' AND lang_id = '$lang_id'");
            $num_content_inactive = Database::numRows("SELECT * FROM content WHERE type='$data_t[id]' AND status='0' AND lang_id = '$lang_id'");
            ?>
            <p><b><?= $data_t['title']; ?>:</b> Active: <?= $num_content_active; ?>  / Inactive: <?= $num_content_inactive; ?></p>
            <?php
        }

        $num_comments = Database::numRows("SELECT * FROM comments");
        ?>
        <p><b>Comments:</b> <?= $num_comments; ?></p>
        <?php
        $num_users = Database::numRows("SELECT * FROM users");
        ?>
        <p><b>Users:</b> <?= $num_users; ?></p>
        <?php
        $num_newsletter = Database::numRows("SELECT * FROM users WHERE newsletter='1'");
        ?>
        <p><b>Newsletter users:</b> <?= $num_newsletter; ?></p>


        <?php
        $num_albums = Database::numRows("SELECT * FROM albums");
        ?>
        <p><b>Albums:</b> <?= $num_albums; ?></p>                
        <?php
    }

    function printCategoryPath($parent_id, $type) {

        $niz = array();
        if ($parent_id != 0) {
            $query = Database::execQuery("SELECT * FROM categories WHERE resource_id='$parent_id' AND content_type_id='$type'");
            $data = mysql_fetch_array($query);
            if ($data['title'] != "") {
                $niz[] = "<a href='index.php?parent_id=$data[resource_id]&cid=$type'>$data[title]</a>";
            }

            $parent = $data['parent_id'];

            while ($parent != 0) {
                $query = Database::execQuery("SELECT * FROM categories WHERE resource_id='$parent' AND content_type_id='$type'");
                $data = mysql_fetch_array($query);

                $niz[] = "<a href='index.php?parent_id=$data[resource_id]&cid=$type'>$data[title]</a>";
                $parent = $data['parent_id'];
            }


            $type_name = Database::getValue("title", "content_types", "id", $type);
            $niz[] = "<a href='index.php?cid=$type'>$type_name</a> ";

            $niz = array_reverse($niz);

            if ($niz[1] != "") {
                $string = implode(" > ", $niz);
            }
        } else {
            $type_name = Database::getValue("title", "content_types", "id", $type);
            $niz[] = "<a href='index.php?cid=$type'>$type_name</a> ";
            $string = implode(" > ", $niz);
        }

        return $string;
    }

    function getCategoriesOptions($cid, $selected, $parent_id = 0, $tab = 0) {
        $categories = new Collection("categories");
        $categoriesCollection = $categories->getCollection("WHERE content_type_id = '$cid' AND parent_id = '$parent_id' AND lang IN (SELECT id FROM languages WHERE is_default = '1') ORDER BY title ASC");
        foreach ($categoriesCollection as $category) {
            $grupa = '';
            if ($category->pripada != '') {

                $grupaCol = new Collection("_content_grupe");
                $grupaArr = $grupaCol->getCollection("WHERE resource_id = $category->pripada AND lang = 1");
                $grupa = $grupaArr[0];
            }
            $numChildren = Database::numRows("SELECT * FROM categories WHERE parent_id = '$category->resource_id' AND content_type_id = '$cid' AND lang IN (SELECT id FROM languages WHERE is_default = '1')");
            if ($numChildren > 0) {
                ?>
                <option value="<?= $category->resource_id; ?>" disabled="disabled" <?php if ($selected == $category->resource_id) echo 'selected="selected"'; ?>>
                    <?php
                    for ($i = 0; $i <= $tab; $i++)
                        echo "&nbsp;";
                    echo $category->title;
                    ?>
                    <?= ($grupa->title != '') ? " ( $grupa->title )" : ""; ?>
                </option>
                <?php
                $this->getCategoriesOptions($cid, $selected, $category->resource_id, $tab + 4);
            } else {
                ?>
                <option value="<?= $category->resource_id; ?>" <?php if ($selected == $category->resource_id) echo 'selected="selected"'; ?>>
                    <?php
                    for ($i = 0; $i <= $tab; $i++)
                        echo "&nbsp;";
                    echo $category->title;
                    ?>
                    <?= ($grupa->title != '') ? " ( $grupa->title )" : ""; ?>
                </option>
                <?php
            }
        }
    }

    function getMultiCategories($cid, $selected, $parent_id = 0) {
        $categories = new Collection("categories");
        $categoriesCollection = $categories->getCollection("WHERE content_type_id = '$cid' AND parent_id = '$parent_id' AND lang IN (SELECT id FROM languages WHERE is_default = '1') ORDER BY ordering");
        foreach ($categoriesCollection as $category) {
            $numChildren = Database::numRows("SELECT * FROM categories WHERE parent_id = '$category->resource_id' AND content_type_id = '$cid' AND lang IN (SELECT id FROM languages WHERE is_default = '1')");
            if ($numChildren > 0) {
                echo "<h3>" . $category->title . "</h3>";
                echo "<div class='multicategories'>";
                $this->getMultiCategories($cid, $selected, $category->resource_id);
                echo "</div>";
                echo "<br clear='all'>";
            } else {
                if (in_array($category->resource_id, $selected)) {
                    $checked = "checked='checked'";
                } else {
                    $checked = "";
                }
                echo "<input type='checkbox' $checked name='categories[]' value='" . $category->resource_id . "'> " . $category->title;
            }
        }
    }

    function printCategoriesNavigation($parent_id, $type, $lang_id, $selected = "") {
        ?>
        <ul>
            <?php
            $query = Database::execQuery("SELECT * FROM categories WHERE parent_id='$parent_id' AND content_type_id='$type' AND lang='$lang_id' ORDER BY ordering");
            while ($data = mysql_fetch_array($query)) {
                $num = Database::numRows("SELECT DISTINCT content_resource_id FROM categories_content WHERE category_resource_id = '$data[resource_id]'");
                ?>
                <li <?php if ($selected == $data['id']) echo "class='active'"; ?>>
                    <a href="/issedit/module_content/index.php?sort_category=<?= $data['resource_id']; ?>&cid=<?= $type; ?>" class="tooltip" title="Category id=<?= $data['id']; ?>"><?= $data['title']; ?> (<?= $num; ?>)</a>
                    <?php $this->printCategoriesNavigation($data['resource_id'], $type, $lang_id, $selected); ?>
                </li>
                <?php
            }
            ?>
        </ul>
        <?php
    }

    function printMenuLinksListing($menuId, $parent_id) {
        ?>
        <ol>
            <?php
            $linksCollection = new Collection("menu_items");
            $links = $linksCollection->getCollection("WHERE menu_id = '$menuId' AND parent_id = '$parent_id'", "ORDER BY ordering");
            foreach ($links as $link) {
                ?>
                <li id="list_<?= $link->id; ?>">
                    <div>
                        <span style="width: auto; float: left; font-size: 12px; padding-top: 4px;"><?= $link->title; ?> | <?= $link->url; ?></span>
                        <a style="float: right;" class="button_table tooltip" title="Delete" onclick="return confirm('Are you sure?');" href="work.php?action=delete_menu_link&link_id=<?= $link->id; ?>">
                            <span class="ui-icon ui-icon-trash"></span>
                        </a>
                        <a style="float: right;" class="button_table tooltip" title="Edit" href="edit_item.php?item_id=<?= $link->id; ?>">
                            <span class="ui-icon ui-icon-pencil"></span>
                        </a>                                        
                        <br clear="all" />
                    </div>
                    <?php
                    if (Database::numRows("SELECT * FROM menu_items WHERE parent_id = '$link->id'") > 0) {
                        $this->printMenuLinksListing($menuId, $link->id);
                    }
                    ?>
                </li>
                <?php
            }
            ?>
        </ol>
        <?php
    }

    //returns max value for columnName in tableName
    function getMaxValue($tableName, $columnName) {
        $fieldsCollection = new Collection($tableName);
        $fields = $fieldsCollection->getCollection();
        $max = 0;
        foreach ($fields as $field)
            if ($field->$columnName > max)
                $max = $field->$columnName;
        return $max;
    }

    //ako ima potkategorije onda i njih podesi, u suprotnom podesi kontekte
    function setCategorySatus($category_id, $status) {
        $category = new View("categories", $category_id);
        $category->status = $status;
        $category->Save();

        $subCategoriesCollection = new Collection("categories");
        $subCategories = $subCategoriesCollection->getCollection("WHERE '$category->resource_id' = parent_id AND '$category->lang' = lang");

        if ($subCategoriesCollection->resultCount > 0) {
            foreach ($subCategories as $subCategory)
                $this->setCategorySatus($subCategory->id, $status);
        } else if ($status == 2) {

            $contentType = new View("content_types", $category->content_type_id);
            $cat_cont_Collection = new Collection("categories_content");
            $ccs = $cat_cont_Collection->getCollection("WHERE '$category->resource_id' = category_resource_id");
            foreach ($ccs as $cc) {
                $contentsCollection = new Collection($contentType->table_name);
                $contents = $contentsCollection->getCollection("WHERE '$cc->content_resource_id' = resource_id AND lang = '$category->lang'");
                foreach ($contents as $content)
                    $this->setContentStatus($content->id, $contentType->table_name, $status);
            }
        }
    }

    function setContentStatus($content_id, $table_name, $status) {
        $content = new View($table_name, $content_id);
        $content->status = $status;
        $content->Save();
    }

    function setContentsForCategory($category, $status) {
        $contentType = new View("content_types", $category->content_type_id);
        $cat_cont_Collection = new Collection("categories_content");
        $ccs = $cat_cont_Collection->getCollection("WHERE '$category->resource_id' = category_resource_id");
        foreach ($ccs as $cc) {
            $contentsCollection = new Collection($contentType->table_name);
            $contents = $contentsCollection->getCollection("WHERE '$cc->content_resource_id' = resource_id AND lang = '$category->lang'");
            foreach ($contents as $content) {
                $content->status = $status;
                $content->Save();
            }
        }
    }

    function printContentsForSidebar($content_type) {
        $contentsCollection = new Collection($content_type->table_name);
        $contents = $contentsCollection->getCollectionCustom("SELECT DISTINCT resource_id FROM " . $content_type->table_name);
        ?>
        <li <?php if ($module_name == "content" && $cid == $ct->id) echo "class='active'"; ?>>
            <a href="/issedit/module_content/index.php?cid=<?= $content_type->id; ?>" class="tooltip" title="Manage <?= $content_type->title; ?>"><?= $content_type->title; ?>&nbsp;(<?= ($contentsCollection->resultCount > 0) ? $contentsCollection->resultCount : "empty"; ?>)</a>

            <?php
            $categoriesCollection = new Collection("categories");
            $language = new View("languages", 1, "is_default");
            $categories = $categoriesCollection->getCollection("WHERE content_type_id = " . $content_type->id . " AND parent_id = 0 and lang = " . $language->id);
            foreach ($categories as $category) {
                $this->printCategoryRec($category);
            }
            echo "</li>";
        }

        /* function printCategoryContentRec($category){
          echo "<ul>";
          $content_type = new View("content_types", $category->content_type_id);
          $cat_contCollection = new Collection("categories_content");
          $cat_conts = $cat_contCollection->getCollection("WHERE category_resource_id = " . $category->resource_id);
          $language = new View("languages", 1, "is_default");
          $categoryCollection = new Collection("categories");
          $categories = $categoryCollection->getCollection("WHERE parent_id = " . $category->resource_id . " AND lang = " . $language->id);
          ?>
          <li <?php if($module_name == "content" && $cid == $ct->id) echo "class='active'"; ?>>
          <?php
          if($categoryCollection->resultCount == 0) {
          ?>
          <a href="/issedit/module_content/index.php?cid=<?= $content_type->id; ?>&category_id=<?= $category->id; ?>" class="tooltip" title="Manage <?= $content_type->title; ?>"><?= $category->title; ?> <?php if($cat_contCollection->resultCount > 0)  echo "(" . $cat_contCollection->resultCount . ")"; ?></a>
          <?php
          } else {
          ?>
          <a href="/issedit/module_categories/index.php?cid=<?= $content_type->id; ?>&parent_id=<?= $category->resource_id; ?>" class="tooltip" title="Manage <?= $content_type->title; ?>"><?= $category->title; ?> <?php if($cat_contCollection->resultCount > 0)  echo "(" . $cat_contCollection->resultCount . ")"; ?></a>
          <?php
          }

          foreach($categories as $categorySingle)
          $this->printCategoryContentRec($categorySingle);
          echo "</li>";
          echo "</ul>";
          } */

        function printCategoryLinks($category) {
            echo "<ul>";
            $cat_contCollection = new Collection("categories_content");
            $cat_cont = $cat_contCollection->getCollection("WHERE category_resource_id = " . $category->resource_id);

            if ($cat_contCollection->resultCount == 0) {
                ?>
            <li <?php if ($module_name == "content" && $cid == $ct->id) echo "class='active'"; ?>>	
                <a href="/issedit/module_categories/index.php?parent_id=<?= $category->resource_id ?>&cid=<?= $category->content_type_id ?>"><?= $category->title ?></a>	  		
                <?php
            } else {
                ?>
            <li <?php if ($module_name == "content" && $cid == $ct->id) echo "class='active'"; ?>>	
                <a href="/issedit/module_content/index.php?category_id=<?= $category->id ?>&cid=<?= $category->content_type_id ?>"><?= $category->title ?></a>
                <?php
            }


            $language = new View("languages", 1, "is_default");
            $categoriesCollection = new Collection("categories");
            $categories = $categoriesCollection->getCollection("WHERE parent_id = " . $category->resource_id . " AND lang = " . $language->id);
            foreach ($categories as $cat)
                $this->printCategoryLinks($cat);
            echo "</li>";
            echo "</ul>";
        }

        function printCategoryRec($category) {
            echo "<ul>";
            echo "<li>";
            $language = new View("languages", 1, "is_default");
            $ct = new View("content_types", $category->content_type_id);
            $categoriesCollection = new Collection("categories");
            $categories = $categoriesCollection->getCollection("WHERE parent_id = " . $category->resource_id . " AND lang = " . $language->id, "ORDER BY ordering");

            if ($categoriesCollection->resultCount == 0) {
                $cat_contCollection = new Collection("categories_content");
                $cat_cont = $cat_contCollection->getCollection("WHERE category_resource_id = " . $category->resource_id);
                if ($cat_contCollection->resultCount > 0) {
                    ?>	
                    <a href="/issedit/module_content/index.php?category_id=<?= $category->id ?>&cid=<?= $category->content_type_id ?>"><?= $category->title . " (" . $cat_contCollection->resultCount . " content)"; ?></a>
                    <?php
                } else {
                    ?>
                    <a href="/issedit/module_categories/index.php?parent_id=<?= $category->resource_id ?>&cid=<?= $category->content_type_id ?>"><?= $category->title . " (empty)" ?></a>
                    <?php
                }
            } else if ($categoriesCollection->resultCount > 0) {
                ?>
                <a href="/issedit/module_categories/index.php?parent_id=<?= $category->resource_id ?>&cid=<?= $category->content_type_id ?>"><?= $category->title . " (" . $categoriesCollection->resultCount . " subcat)" ?></a>
                <?php
                foreach ($categories as $categorySingle)
                    $this->printCategoryRec($categorySingle);
            }
            echo "</li>";
            echo "</ul>";
        }

        function printParentSelectOptions($parent_id, $categorySingle, $level = 0) {
            $categoriesCollection = new Collection("categories");
            $categories = $categoriesCollection->getCollection("WHERE parent_id = " . $parent_id . " AND resource_id <> " . $categorySingle->resource_id . " AND lang = " . $categorySingle->lang);
            foreach ($categories as $category) {
                $cat_contCollection = new Collection("categories_content");
                $cat_cont = $cat_contCollection->getCollection("WHERE category_resource_id = " . $category->resource_id);

                if ($cat_contCollection->resultCount == 0) {
                    if ($categorySingle->parent_id == $category->resource_id)
                        $selected = 'selected="selected"';
                    else
                        $selected = "";
                    echo '<option value="' . $category->resource_id . '" ' . $selected . '>';
                    for ($i = 0; $i < $level; $i++)
                        echo "&nbsp;";
                    echo $category->title . '</option>';
                    $this->printParentSelectOptions($category->resource_id, $categorySingle, $level + 4);
                }
            }
        }

        function categoryLevel($category) {
            if ($category->parent_id == 0)
                return 1;
            $categoryCollection = new Collection("categories");
            $parentCategory = $categoryCollection->getCollection("WHERE resource_id = $category->parent_id AND lang = $category->lang");
            return 1 + $this->categoryLevel($parentCategory[0]);
        }

        function printCleanString($string) {
            $string = str_replace("../../", "/", $string);
            //$string = str_replace('"', '\"', $string);
            $string = stripslashes($string);
            $string = htmlspecialchars($string);
            return $string;
        }

    }

    // end of class
    ?>