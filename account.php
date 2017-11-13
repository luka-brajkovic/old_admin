<?php
include_once ("library/config.php");

if (!$isLoged) {
    
    $_SESSION['infoTitle'] = "<h1>Morate biti prijavljeni</h1>";
    $_SESSION['infoText'] = "<p>Za sadržaj koji tražite morate biti prijavljeni.</p>";
    $f->redirect("/prijava");
}
?>

<?php include_once ("head.php"); ?>

</head>
<body>
    <?php
    include_once ("header.php");
    ?>
    <div class="container">    
        <div class="content">
            <div class="nav_account clear">
                <?php include_once("includes/account_nav.php"); ?>
            </div>
            <h1>Vaš nalog (osnovni podaci)</h1>
            <?php
            $contentTypeIDUsers = "69";

            $contentType = new View("content_types", $contentTypeIDUsers);
            $table_name = $contentType->table_name;

            $user_fields = mysqli_query($conn,"SELECT * FROM content_type_fields WHERE content_type_id = $contentTypeIDUsers ");

            $arrayOvihKojiIdu = array("ime", "prezime", "fiksni_telefon", "mobilni_telefon", "newsletter");


            $greske = array();
            if ($f->getValue("hitted") != '') {



                while ($field = mysqli_fetch_object($user_fields)) {
                    $columnName = $field->column_name;
                    if (in_array($field->column_name, $arrayOvihKojiIdu)) {

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
                                if (!filter_var($f->getValue($columnName), FILTER_VALIDATE_EMAIL)) {
                                    array_push($greske, $columnName);
                                }
                                break;
                            case "postanski_broj":
                                if (strlen($f->getValue($columnName)) < 5 || !is_numeric($f->getValue($columnName))) {
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
                            case "fiksni_telefon":
                            case "mobilni_telefon":

                                $fixni = preg_replace('/\D/', '', $f->getValue("fiksni_telefon"));
                                $mobilni = preg_replace('/\D/', '', $f->getValue("mobilni_telefon"));

                                if ($fixni == '' && $mobilni == '') {
                                    array_push($greske, $columnName);
                                }
                                if (strlen($mobilni) < 8 && strlen($fixni) < 6) {
                                    array_push($greske, $columnName);
                                }

                                break;
                        }
                    }

                    if (empty($greske)) {
                        foreach ($arrayOvihKojiIdu as $fieldName) {
                            $userData->$fieldName = $f->getValue($fieldName);
                            if ($fieldName == "ime") {
                                $userData->url = $f->generateUrlFromText($f->getValue($fieldName));
                            }
                        }

                        $userData->title = $f->getValue("ime") . " " . $f->getValue("prezime");
                        $userData->url = $f->generateUrlFromText($f->getValue("ime") . "-" . $f->getValue("prezime"));

                        $userData->Save();

                        $urlBezPitanja = explode("?", $REQUEST);
                        $urlBezPitanja = $urlBezPitanja[0];

                        $f->redirect($urlBezPitanja . "?poruka=sacuvano");
                    }
                }
            }
            ?>

            <div class="container">

                <?php
                if (!empty($greske)) {

                    if (in_array("email_postoji", $greske)) {
                        ?>
                        <div class="error">
                            <h5>Greška</h5>
                            <p>Nalog sa e-mailom <strong>"<?= $f->getValue("e-mail"); ?>"</strong> već postoji i aktivan je.</p>
                            <ul>
                                <li>Ukoliko je ovo Vaš e-mail, a zaboravili ste lozinku kliknite na <a href='/zaboravljena-lozinka'> link za zaboravljenu lozinku </a></li>
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

                <div class="logCont row">

                    <form action="<?= $REQUEST; ?>" method="post" class="clear">
                        <div class="half left">
                            <div class="gray"> 
                                <?php
                                $user_fields = mysqli_query($conn,"SELECT * FROM content_type_fields WHERE content_type_id = $contentTypeIDUsers ORDER BY ordering ");
                                /*

                                 * CUSTOM FORMA - pravim counter da bi polja rasporedjivao levo ili desno kako hocu
                                 * 
                                 *        */
                                $counter = 0;

                                while ($field = mysqli_fetch_object($user_fields)) {
                                    $columnName = $field->column_name;
                                    $counter++;
                                    if (in_array($field->column_name, $arrayOvihKojiIdu)) {


                                        echo "<p>";

                                        /* END PRILAGODJENO */


                                        switch ($field->field_type) {
                                            case "text":
                                                if ($field->column_name == "lozinka") {
                                                    ?>
                                                    <label><?= $field->title; ?></label>
                                                    <input type="password" name='<?= $field->column_name; ?>' id="<?= $field->column_name; ?>" value='' placeholder="" />
                                                    <em <?= (in_array($field->column_name, $greske)) ? "style='display:block;'" : ""; ?>><?= "* Neispravno uneseno polje "; ?> <?= $field->title; ?></em>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <label><?= $field->title; ?></label>
                                                    <input type="text" name='<?= $field->column_name; ?>' id="<?= $field->column_name; ?>" value='<?= $userData->$columnName; ?>' placeholder="" />
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
                                                        if ($userData->$columnName == $option) {
                                                            echo "<option selected='selected' value='$option'>$option</option>";
                                                        } else {
                                                            echo "<option  value='$option'>$option</option>";
                                                        }
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
                                        if ($field->column_name == "lozinka") {

                                            echo "<p class='half right'>";
                                            ?>
                                            <label><?= $field->title; ?> ponovo</label>
                                            <input type="password" name='<?= $field->column_name; ?>_equal' id="<?= $field->column_name; ?>_equal" value='' placeholder="" />
                                            <em <?= (in_array($field->column_name . "_equal", $greske)) ? "style='display:block;'" : ""; ?>><?= "* Lozinke nisu iste"; ?> </em>    
                                            <?php
                                            echo "</p>";
                                            echo "<br clear='all'/>";
                                        }
                                    }
                                }
                                ?>

                                <p>
                                    <input class='more' type="submit" name="hitted" value="Izmeni podatke" />
                                </p>
                            </div>
                        </div>

                        <div class="half right picture">
                            <div style="padding-left:15px;">
                                <?= $db->getValue("text", "_content_html_blocks", "resource_id", "11"); ?>
                            </div>    
                        </div>      
                    </form>
                </div>
            </div>

        </div>
    </div>
    <?php
    include_once ("footer.php");
    ?>

</body>
</html>
