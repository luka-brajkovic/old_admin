<?php
$contentTypeIDUsers = "69";
$contentType = new View("content_types", $contentTypeIDUsers);
$table_name = $contentType->table_name;
$user_fields = mysqli_query($conn, "SELECT * FROM content_type_fields WHERE content_type_id = $contentTypeIDUsers");

$greske = array();

if ($f->verifyFormToken('form1')) {
    while ($field = mysqli_fetch_object($user_fields)) {
        $columnName = $field->column_name;
        switch ($columnName) {
            case "ime":
            case "prezime":
            case "ulica_i_broj":
            case "grad":
                if (strlen($f->getValue($columnName)) < 3 || trim($f->getValue($columnName)) == '') {
                    array_push($greske, $columnName);
                } break;
            case "e-mail":
                if (!filter_var($f->getValue($columnName), FILTER_VALIDATE_EMAIL)) {
                    array_push($greske, $columnName);
                }
                break;
            case "lozinka":
                if (strlen($f->getValue($columnName)) < 3) {
                    array_push($greske, $columnName);
                }
                if ($f->getValue($columnName) != $f->getValue($columnName . "_equal") || $f->getValue($columnName) == '') {
                    array_push($greske, $columnName . "_equal");
                }
                break;
            case "mobilni_telefon":
                $mobilni = preg_replace('/\D/', '', $f->getValue("mobilni_telefon"));
                if ($mobilni == '') {
                    array_push($greske, $columnName);
                }
                if (strlen($mobilni) < 8) {
                    array_push($greske, $columnName);
                }
                break;
        }
    }
    if (($_POST["agree"]) == "") {
        array_push($greske, "agree");
    }
    if (empty($greske)) {
        $email = $f->getValue("e-mail");
        $newUser = new View($table_name, $email, "e-mail");
        if ($newUser->id != '') {
            switch ($newUser->status) {
                case "1":
                    array_push($greske, "email_postoji");
                    break;
                case "2":
                    array_push($greske, "email_neaktivan");
                    break;
            }
        } else {
            $postanski = $db->getValue("postanski_broj", "_content_cities", resource_id, $f->getValue('grad'));

            $nameAndSurname = $f->getValue("ime") . " " . $f->getValue("prezime");
            
            $resource = new View("resources");
            $resource->table_name = $table_name;
            $resource->Save();
            $newUser = new View($table_name);
            $newUser->resource_id = $resource->id;
            $newUser->extend($_POST);
            $newUser->postanski_broj = $postanski;
            $newUser->title = $nameAndSurname;
            $newUser->url = $f->generateUrlFromText($f->getValue("ime") . " " . $f->getValue("prezime"));
            $newUser->system_date = date("Y-m-d H:i:s");
            $newUser->lang = 1;
            $newUser->ordering = 0;
            $newUser->num_views = 0;
            $newUser->poslat_email = "0000-00-00";
            $newUser->status = 2;
            $newUser->lozinka = md5($f->getValue("lozinka"));
            $newUser->Save();

            if ($f->getValue("newsletter") == "Da") {
                $postojiEmail = $db->getValue("resource_id", "_content_newsletter", title, $email);
                if ($postojiEmail == "") {
                    $paketNewsletter = new View("resources");
                    $paketNewsletter->table_name = "_content_newsletter";
                    $paketNewsletter->Save();
                    $newEmail = new View("_content_newsletter");
                    $newEmail->resource_id = $paketNewsletter->id;
                    $newEmail->title = $email;
                    $newEmail->url = $f->generateUrlFromText($email);
                    $newEmail->system_date = date("Y-m-d H:i:s");
                    $newEmail->lang = 1;
                    $newEmail->status = 1;
                    $newEmail->Save();
                } else {
                    $maili = new Collection("_content_newsletter");
                    $mailArr = $maili->getCollection("WHERE title = '$email'");
                    $newEmail = $mailArr[0];
                    $newEmail->status = 1;
                    $newEmail->Save();
                }
            }
            $fieldMail = "e-mail";
            $md5_email = md5($newUser->$fieldMail);

            $body = "<p>Poštovani/a " . $f->getValue('ime') . ",</p>";
            $body .= "<p>Vaš nalog će te aktivirati klikom na <a href='" . $csDomain . "aktivacija-naloga/$md5_email'>Aktivacioni link</a></p>";
            $body .= "<p>Ukoliko ne možete klikom da aktivirate nalog, kopirajte i otvorite u vašem pretraživaču sledeći link:<br /> <a href='" . $csDomain . "aktivacija-naloga/$md5_email'>" . $csDomain . "aktivacija-naloga/$md5_email</a></p>";
            $body .= "<p>Još jednom koristimo priliku da Vam se zahvalimo na korišćenju naših usluga.</p><p>Vaš " . $csName . " tim</p>";

            $f->sendMail($csEmail, $csName, $newUser->$fieldMail, $nameAndSurname, "Aktivacija naloga - $csName", $body, $currentLanguage);

            $f->redirect("/strana/aktivacija-naloga");
        }
    }
}
?><div class="container"><?php
if (!empty($greske)) {
    if (in_array("email_postoji", $greske)) {
        ?>
            <div class="error">
                <h5>Greška</h5>
                <p>Nalog sa e-mailom <strong>"<?= $f->getValue("e-mail"); ?>"</strong> već postoji i aktivan je.</p>
                <ul>
                    <li>Ukoliko je ovo Vaš e-mail, a zaboravili ste lozinku kliknite na <a  id="forgotPass" href="javascript:void(0);"> link za zaboravljenu lozinku </a></li>
                    <li>Ukoliko ovo nije Vaš e-mail, pokušajte registraciju sa drugim e-mailom.</li>
                </ul>
            </div>  
            <?php
        }
        if (in_array("email_neaktivan", $greske)) {
            ?>
            <div class="error">
                <h5>Greška</h5>
                <p>Nalog sa e-mailom <strong>"<?= $f->getValue("e-mail"); ?>"</strong> već postoji, ali nije aktivan</p>
                <ul>
                    <li>Ukoliko je ovo Vaš e-mail, pogledajte u Vašem inboxu, poslali smo Vam aktivacioni e-mail preko kojeg će te aktivirati Vaš nalog</li>
                    <li>Ukoliko je ovo Vaš e-mail, a ne možete pronaći aktivacioni e-mail, kliknite na link <a href='/posalji-aktivaciju/<?= md5($f->getValue("e-mail")); ?>'>posalji aktivaciju</a></li>
                    <li>Ukoliko ovo nije Vaš e-mail, pokušajte registraciju sa drugim e-mailom.</li>
                </ul>
            </div>  
            <?php
        }
    }
    ?>
    <ul class="pagePosition clear" itemtype="http://data-vocabulary.org/BreadcrumbList">
        <li>
            <span>Vi ste ovde:</span>
        </li>
        <li itemprop="itemListElement" itemscope="itemscope" itemtype="http://schema.org/ListItem">
            <a title="Početna" href="/" itemprop="url">
                <span itemprop="title">Početna</span>
            </a>
        </li>
        <li>
            <span">Registracija</span>
        </li>
    </ul>
    <h1>Pridruži se na <?= $csName; ?></h1>
    <div class="logCont">
        <h4>Osnovni podaci:</h4>
        <form action="<?= $REQUEST; ?>" method="post" class="clear">
            <div class="half">
                <div class="gray row"> 
                    <?php
                    $newToken = $f->generateFormToken('form1');

                    $user_fields = mysqli_query($conn, "SELECT * FROM content_type_fields WHERE content_type_id = $contentTypeIDUsers ORDER BY ordering");
                    /*                     * **** CUSTOM FORMA - pravim counter da bi polja rasporedjivao levo ili desno kako hocu ***** */
                    $counter = 0;
                    while ($field = mysqli_fetch_object($user_fields)) {
                        $counter++;
                        /* PRILAGODJENO */
                        if ($counter == 7) {
                            echo "<br clear='all'/>";
                            echo "<div class='full'><h4>Podaci o mestu dostave</h4></div><p class='full'>";
                        } if ($field->column_name == "newsletter") {
                            echo "<br clear='all'/>";
                        } if ($field->column_name == "lozinka") {
                            echo "<br clear='all'/>";
                            echo "<p class='half'>";
                        } if ($field->column_name == "ime" || $field->column_name == "e-mail" || $field->column_name == "mobilni_telefon" || $field->column_name == "newsletter" || $field->column_name == "grad" || $field->column_name == "sprat") {
                            echo "<p class='half'>";
                        }
                        if ($field->column_name == "prezime" || $field->column_name == "fiksni_telefon" || $field->column_name == "postanski_broj" || $field->column_name == "broj_stana") {
                            echo "<p class='half'>";
                        } /* END PRILAGODJENO */
                        switch ($field->field_type) {
                            case "text":
                                if ($field->column_name == "lozinka") {
                                    ?>
                                    <label><?= $field->title; ?></label>
                                    <input type="password" name='<?= $field->column_name; ?>' id="<?= $field->column_name; ?>" value='' placeholder="" />
                                    <em <?= (in_array($field->column_name, $greske)) ? "style='display:block;'" : ""; ?>><?= "* Neispravno uneseno polje "; ?> <?= $field->title; ?></em>
                                    <?php
                                } elseif ($field->column_name == "postanski_broj") {
                                    
                                } else {
                                    ?>
                                    <label><?= $field->title; ?></label>
                                    <input type="text" name='<?= $field->column_name; ?>' id="<?= $field->column_name; ?>" value='<?= $f->getValue($field->column_name); ?>' placeholder="" />
                                    <em <?= (in_array($field->column_name, $greske)) ? "style='display:block;'" : ""; ?>><?= "* Neispravno uneseno polje "; ?> <?= $field->title; ?></em>
                                    <?php
                                }
                                break;
                            case "select":
                                $list = explode(",", $field->default_value);
                                ?>
                                <strong>Najnovije akcije, popusti i obaveštenja na tvoj e-mail?</strong>
                                <label><?= $field->title; ?></label>
                                <select name='<?= $field->column_name; ?>' id='<?= $field->column_name; ?>' >
                                    <?php
                                    foreach ($list as $option) {
                                        echo "<option value='$option'>$option</option>";
                                    }
                                    ?>
                                </select>                
                                <?php
                                break;
                            case "select_table":
                                ?>
                                <label><?= $field->title; ?></label>
                                <?php
                                list($tableExtract, $key, $show) = explode(",", $field->default_value);
                                $queryExtract = mysqli_query($conn, "SELECT * FROM $tableExtract WHERE status = 1 AND lang = $currentLanguage ORDER BY title ASC");
                                ?>
                                <select name='<?= $field->column_name; ?>' id='<?= $field->column_name; ?>'>
                                    <option value=''>Odaberi <?= $field->title; ?></option>
                                    <?php
                                    $fildic = $f->getValue($field->column_name);
                                    while ($value = mysqli_fetch_object($queryExtract)) {
                                        ?>
                                        <option <?= ($fildic == $value->$key) ? " selected='selected' " : ""; ?> value='<?= $value->$key; ?>'><?= $value->$show . " (" . $value->postanski_broj . ")"; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                <em <?= (in_array($field->column_name, $greske)) ? "style='display:block;'" : ""; ?>><?= "* Neispravno uneseno polje "; ?> <?= $field->title; ?></em>									<?php
                                break;
                        }
                        if ($field->column_name == "postanski_broj") {
                            
                        } else {
                            echo "</p>";
                        }
                        if ($field->column_name == "lozinka") {
                            echo "<p class='half'>";
                            ?>
                            <label><?= $field->title; ?> ponovo</label>
                            <input type="password" name='<?= $field->column_name; ?>_equal' id="<?= $field->column_name; ?>_equal" value='' placeholder="" />
                            <em <?= (in_array($field->column_name . "_equal", $greske)) ? "style='display:block;'" : ""; ?>><?= "* Lozinke nisu iste"; ?> </em>    
                            <?php
                            echo "</p>";
                        }
                    }
                    ?>
                    <p class="half right">
                        <strong class="mustClick">
                            Čekiranjem check boxa ispod podrazumeva se da ste pročitali i složili se sa 
                            <a href="/strana/uslovi-koriscenja" target="_blank">uslovima korišćenja</a>
                            <input class="right" style="width:auto;" <?= ($f->getValue("agree") != "") ? 'checked=""' : ''; ?> type="checkbox" name="agree" value="agree" />
                        </strong>
                        <em <?= (in_array("agree", $greske)) ? "style='display:block;'" : ""; ?>><?= "* Morate se složiti sa uslovima korišćenja"; ?> </em> 
                    </p>
                    <br clear='all'/>
                    <p class="full">
                        <input class='more' type="submit" name="hitted" value="Registruj se" />
                    </p>
                </div>
            </div>			
            <div class="half picture">
                <div style="padding-left:15px;">
                    <?= $db->getValue("text", "_content_html_blocks", "resource_id", "1"); ?>
                </div>    
            </div>   
            <input type="hidden" name="token" value="<?= $newToken; ?>">   
        </form>
    </div>
</div>