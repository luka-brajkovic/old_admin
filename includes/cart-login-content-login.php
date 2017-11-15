<?php
if ($isLoged) {
    $f->redirect("/korpa-placanje");
} else {

    $contentTypeIDUsers = "69";

    $contentType = new View("content_types", $contentTypeIDUsers);
    $table_name = $contentType->table_name;

    $user_fields = mysqli_query($conn,"SELECT * FROM content_type_fields WHERE content_type_id = $contentTypeIDUsers");

    $greske = array();
    if ($f->getValue("hitted") != '') {
        while ($field = mysqli_fetch_object($user_fields)) {
            $columnName = $field->column_name;
            switch ($columnName) {
                case "ime":
                case "prezime":
                case "ulica_i_broj":
                case "grad":

                    if (strlen($f->getValue($columnName)) < 3 || trim($f->getValue($columnName)) == '') {
                        array_push($greske, $columnName);
                    }

                    break;
                case "e-mail":
                    $email = $f->getValue($columnName);
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        array_push($greske, $columnName);
                    }

                    $emailCheck = mysqli_query($conn,"SELECT resource_id FROM _content_users WHERE `e-mail` = '$email' LIMIT 1");
                    $emailCheck = mysqli_fetch_object($emailCheck);
                    if ($emailCheck->resource_id != "") {
                        $f->redirect("/postojeci/prijava");
                    }
                    break;
                case "postanski_broj":
                    if (strlen($f->getValue($columnName)) < 5 || !is_numeric($f->getValue($columnName))) {
                        array_push($greske, $columnName);
                    }
                    break;
                case "mobilni_telefon":

                    $mobilni = preg_replace('/\D/', '', $f->getValue("mobilni_telefon"));

                    if (strlen($mobilni) < 8 && strlen($fixni) < 6) {
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
            }

            $resource = new View("resources");
            $resource->table_name = $table_name;
            $resource->Save();

            $newUser = new View($table_name);
            $newUser->resource_id = $resource->id;
            $newUser->extend($_POST);
            $newUser->title = $f->getValue("ime") . " " . $f->getValue("prezime");
            $newUser->url = $f->generateUrlFromText($f->getValue("ime") . " " . $f->getValue("prezime"));
            $newUser->system_date = date("Y-m-d H:i:s");
            $newUser->lang = 1;
            $newUser->status = 1;
            $passLoz = rand(1000000, 9999999);
            $newUser->lozinka = md5($passLoz);
            $newUser->Save();

            $body = "<p>Poštovani/a " . $f->getValue("ime").",</p>";
            $body .= "<p>Možete ponovo kupovati kod nas i pratiti Vaše porudžbine.<br><br>Vaša lozinka za prijavljivanje je: $passLoz<br>Lozinku možete promeniti u korisničkom nalogu pošto se prijavite na našu internet prodavnicu.</p>";
            $body .= "<p>Vaš <a href='".$csDomain."' title='$csName'>" . $csName . "</a> tim</p>";
            
            $f->sendMail($csEmail, "$csName", $email, $f->getValue("ime") . " " . $f->getValue("prezime"), "Dobrodošli", $body, $currentLanguage);

            $user = new View("_content_users", $resource->id, 'resource_id');
            $_SESSION["loged_user"] = $user->id;

            $f->redirect("/korpa-dostava");
        }
    }
    ?>

    <form action="" method="post">
        <div class="clear logCont">
            <div class="row">
                <div  class="full">
                    <h1>Prijava</h1>
                </div>
                <div class="half" style="padding-right: 20px; box-sizing: border-box;text-align: center;">
                    <h3 style="margin-bottom: 30px;">Ukoliko imate nalog na "<?= $csName; ?>" klikninite na dugme ispod i prijavite se zbog lakše kupovine.</h3>
                    <a class="moreic" style="margin-bottom: 50px;" href="/prijava" title="Prijavite se">Prijavite se</a>
                    <h4 class="lePri">Ukoliko nemate otvoren nalog, preporučujemo Vam da ga otvorite jer pogodnosti su višestruke:</h4>
                    <ul>
                        <li>Uvid u istoriju kupovina</li>
                        <li>Obaveštavanje o akcijama i popustima putem newsletter-a</li>
                        <li>Potpuna kontrola svake narudžbine</li>
                    </ul>
                    <h4>Nalog možete otvoriti klikom na dugme ispod</h4>
                    <a class="moreic" href="/registracija" title="Registrujte se">Registrujte se</a>
                </div>
                <div class="half">
                    <div class="gray">
                        <?php
                        $user_fields = mysqli_query($conn,"SELECT * FROM content_type_fields WHERE content_type_id = $contentTypeIDUsers AND column_name != 'lozinka' AND column_name != 'fiksni_telefon' ORDER BY ordering");
                        /*

                         * CUSTOM FORMA - pravim counter da bi polja rasporedjivao levo ili desno kako hocu
                         * 
                         *        */
                        $counter = 0;
                        echo "<br><h4 class='full'>Brza kupovina bez registracije</h4>";
                        while ($field = mysqli_fetch_object($user_fields)) {
                            $counter++;
                            /* PRILAGODJENO */



                            if ($field->column_name == "ime" || $field->column_name == "e-mail" || $field->column_name == "newsletter" || $field->column_name == "grad" || $field->column_name == "sprat" || $field->column_name == "prezime" || $field->column_name == "postanski_broj" || $field->column_name == "broj_stana" || $field->column_name == "mobilni_telefon") {
                                echo "<p class='half'>";
                            } else {
                                echo "<p class='full'>";
                            }

                            /* END PRILAGODJENO */


                            switch ($field->field_type) {
                                case "text":
                                    ?>
                                    <label><?= $field->title; ?></label>
                                    <input type="text" name='<?= $field->column_name; ?>' id="<?= $field->column_name; ?>" value='<?= $f->getValue($field->column_name); ?>' placeholder="" />
                                    <em <?= (in_array($field->column_name, $greske)) ? "style='display:block;'" : ""; ?>><?= "* Neispravno uneseno polje "; ?> <?= $field->title; ?></em>
                                    <?php
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
                                    $queryExtract = mysqli_query($conn,"SELECT * FROM $tableExtract WHERE status = 1 AND lang = $currentLanguage ORDER BY title ASC");
                                    ?>
                                    <select name='<?= $field->column_name; ?>' id='<?= $field->column_name; ?>'>
                                        <option value=''>Odaberi <?= $field->title; ?></option>
                                        <?php
                                        while ($value = mysqli_fetch_array($queryExtract)) {
                                            ?>
                                            <option <?= ($f->getValue($field->column_name) == $value[$key]) ? " selected='selected' " : ""; ?> value='<?= $value[$key]; ?>'><?= $value[$show]; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <em <?= (in_array($field->column_name, $greske)) ? "style='display:block;'" : ""; ?>><?= "* Neispravno uneseno polje "; ?> <?= $field->title; ?></em>

                                    <?php
                                    break;
                            }
                            echo "</p>";
                        }
                        ?>
                        <p class="half right">
                            <strong>
                                Klikom na dugme ispod se podrazumeva da ste pročitali i složili se sa 
                                <a href="/strana/uslovi-koriscenja" target="_blank">uslovima korišćenja</a>
                            </strong>
                            <br clear='all'/>
                            <input class="right" style="width:auto;" type="checkbox" name="agree" value="agree" />
                            <br />    
                            <em <?= (in_array("agree", $greske)) ? "style='display:block;'" : ""; ?>>* Morate se složiti sa uslovima korišćenja</em> 
                        </p>
                        <br clear='all'/>
                        <p>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="goBack margin-vertical clear">
            <div class="prGoL left">
                <i class="fa fa-caret-left prevGo transition"></i>
                <a class="left" href="/korpa" title="Vrati se nazad"> Vrati se nazad</a>
            </div>
            <div class="neGoR right">
                <input class="transition" type="submit" name="hitted" id="korpa-dostava" value="Dalje" />
                <i class="fa fa-caret-right next transition"></i>
            </div>
        </div>   
    </form>
<?php } ?>