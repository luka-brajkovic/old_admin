<?php
include_once ("library/config.php");
?>

<?php include_once ("head.php"); ?>

</head>
<body>
    <?php
    include_once ("popup.php");
    include_once ("header.php");
    ?>
    <div class="container">    
        <div class="content">
            <div class="nav_account clear">
                <?php include_once("account_nav.php"); ?>
            </div>
            <h1>Promena adrese</h1>
            <?php
            $contentTypeIDUsers = "69";

            $contentType = new View("content_types", $contentTypeIDUsers);
            $table_name = $contentType->table_name;

            $user_fields = mysql_query("SELECT * FROM content_type_fields WHERE content_type_id = $contentTypeIDUsers ");

            $arrayOvihKojiIdu = array("ulica_i_broj", "sprat", "broj_stana", "grad", "postanski_broj", "naselje");


            $greske = array();
            if ($f->getValue("hitted") != '') {



                while ($field = mysql_fetch_object($user_fields)) {
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

                        $userData->Save();
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
                            <p>Nalog sa e-mailom <strong>"<?php echo $f->getValue("e-mail"); ?>"</strong> već postoji i aktivan je.</p>
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
                            <p>Nalog sa e-mailom <strong>"<?php echo $f->getValue("e-mail"); ?>"</strong> već postoji, ali nije aktivan</p>
                            <ul>
                                <li>Ukoliko je ovo Vaš e-mail, pogledajte u Vašem inboxu, poslali smo Vam aktivacioni e-mail preko kojeg će te aktivirati Vaš nalog</li>
                                <li>Ukoliko je ovo Vaš e-mail, a ne možete pronaći aktivacioni e-mail, kliknite na link <a href='/posalji-aktivaciju/<?php echo md5($f->getValue("e-mail")); ?>'>posalji aktivaciju</a></li>
                                <li>Ukoliko ovo nije Vaš e-mail, pokušajte registraciju sa drugim e-mailom.</li>
                            </ul>
                        </div>  
                        <?php
                    }
                }
                ?>

                <div class="logCont row">

                    <form action="<?php echo $REQUEST; ?>" method="post" class="clear">
                        <div class="half left">
                            <div class="gray"> 
                                <?php
                                $user_fields = mysql_query("SELECT * FROM content_type_fields WHERE content_type_id = $contentTypeIDUsers ORDER BY ordering ");
                                /*

                                 * CUSTOM FORMA - pravim counter da bi polja rasporedjivao levo ili desno kako hocu
                                 * 
                                 *        */
                                $counter = 0;

                                while ($field = mysql_fetch_object($user_fields)) {
                                    $columnName = $field->column_name;
                                    $counter++;
                                    if (in_array($field->column_name, $arrayOvihKojiIdu)) {


                                        echo "<p>";

                                        /* END PRILAGODJENO */


                                        switch ($field->field_type) {
                                            case "text":
                                                if ($field->column_name == "lozinka") {
                                                    ?>
                                                    <label><?php echo $field->title; ?></label>
                                                    <input type="password" name='<?php echo $field->column_name; ?>' id="<?php echo $field->column_name; ?>" value='' placeholder="" />
                                                    <em <?php echo(in_array($field->column_name, $greske)) ? "style='display:block;'" : ""; ?>><?php echo "* Neispravno uneseno polje "; ?> <?php echo $field->title; ?></em>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <label><?php echo $field->title; ?></label>
                                                    <input type="text" name='<?php echo $field->column_name; ?>' id="<?php echo $field->column_name; ?>" value='<?php echo $userData->$columnName; ?>' placeholder="" />
                                                    <em <?php echo(in_array($field->column_name, $greske)) ? "style='display:block;'" : ""; ?>><?php echo "* Neispravno uneseno polje "; ?> <?php echo $field->title; ?></em>
                                                    <?php
                                                }
                                                break;
                                            case "select":
                                                $list = explode(",", $field->default_value);
                                                ?>
                                                <strong>Najnovije akcije, popusti i obaveštenja na tvoj e-mail?</strong>
                                                <label><?php echo $field->title; ?></label>
                                                <select name='<?php echo $field->column_name; ?>' id='<?php echo $field->column_name; ?>' >
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
                                                <label><?php echo $field->title; ?></label>
                                                <?php
                                                list($tableExtract, $key, $show) = explode(",", $field->default_value);
                                                $queryExtract = mysql_query("SELECT * FROM $tableExtract WHERE status = 1 AND lang = $currentLanguage ORDER BY title ASC");
                                                ?>
                                                <select name='<?php echo $field->column_name; ?>' id='<?php echo $field->column_name; ?>'>
                                                    <option value=''>Odaberi <?php echo $field->title; ?></option>
                                                    <?php
                                                    while ($value = mysql_fetch_array($queryExtract)) {
                                                        ?>
                                                        <option <?php echo($userData->$columnName == $value[$key]) ? " selected='selected' " : ""; ?> value='<?php echo $value[$key]; ?>'><?php echo $value[$show]; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                                <em <?php echo(in_array($field->column_name, $greske)) ? "style='display:block;'" : ""; ?>><?php echo "* Neispravno uneseno polje "; ?> <?php echo $field->title; ?></em>

                                                <?php
                                                break;
                                        }
                                        echo "</p>";
                                        if ($field->column_name == "lozinka") {

                                            echo "<p>";
                                            ?>
                                            <label><?php echo $field->title; ?> ponovo</label>
                                            <input type="password" name='<?php echo $field->column_name; ?>_equal' id="<?php echo $field->column_name; ?>_equal" value='' placeholder="" />
                                            <em <?php echo(in_array($field->column_name . "_equal", $greske)) ? "style='display:block;'" : ""; ?>><?php echo "* Lozinke nisu iste"; ?> </em>    
                                            <?php
                                            echo "</p>";
                                            echo "<br clear='all'/>";
                                        }
                                    }
                                }
                                ?>

                                <p>
                                    <input class='more' type="submit" name="hitted" value="Izmeni adresu" />
                                </p>
                            </div>
                        </div>

                        <div class="half right picture">
                            <div style="padding-left:15px;">
                                <?php echo $db->getValue("text", "_content_html_blocks", "resource_id", "8915"); ?>
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
