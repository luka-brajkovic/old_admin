<?php
include_once ("library/config.php");
if ($f->getValue("md5_email") == '' && !$isLoged) {
    
    $_SESSION['infoTitle'] = "<h1>Morate biti prijavljeni</h1>";
    $_SESSION['infoText'] = "<p>Za sadržaj koji tražite morate biti prijavljeni.</p>";
    $f->redirect("/prijava");
} else {
    $md5_email = $f->getValue("md5_email");
    if ($md5_email != '') {
        $userColl = new Collection("_content_users");
        $userArr = $userColl->getCollection("WHERE md5(`e-mail`) = '" . $f->getValue("md5_email") . "'");
        if (count($userArr) != 1) {
            $f->redirect("/");
        } else {
            $userData = $userArr[0];
        }
    }
}

include_once ("head.php");
?>

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
            <h1>Promena lozinke</h1>
            <?php
            $contentTypeIDUsers = "69";

            $contentType = new View("content_types", $contentTypeIDUsers);
            $table_name = $contentType->table_name;

            $user_fields = mysqli_query($conn, "SELECT * FROM content_type_fields WHERE content_type_id = $contentTypeIDUsers ");

            $arrayOvihKojiIdu = array("lozinka");

            $greske = array();
            if ($f->getValue("hitted") != '') {

                if (strlen($f->getValue("lozinka")) < 3) {
                    array_push($greske, "lozinka");
                }
                if ($f->getValue("lozinka") != $f->getValue("lozinka_equal") || $f->getValue("lozinka") == '') {
                    array_push($greske, "lozinka_equal");
                }

                if (empty($greske)) {
                    $userData->lozinka = md5($f->getValue("lozinka"));
                    $userData->Save();

                    $_SESSION['infoTitle'] = "<h1>Lozinka promenjena</h1>";
                    $_SESSION['infoText'] = "<p>Vaša lozinka je uspešno promenjena, ukoliko i dalje imate problema pri pristupanju Vašem nalogu, kontaktirajte nas:<br/><br/>Telefon: <a href='tel:$csPhone'>$csPhone</a><br/> E-mail: <a href='mailto:$csEmail'>$csEmail</a>.</p>";
                    $f->redirect("/");
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
                        <?php if ($md5_email != "") {
                            ?>
                            <input type="hidden" name="md5_email" value="<?= $md5_email; ?>" />
                            <?php
                        }
                        ?>
                        <div class="half">
                            <div class="gray"> 
                                <?php
                                $user_fields = mysqli_query($conn, "SELECT * FROM content_type_fields WHERE content_type_id = $contentTypeIDUsers ORDER BY ordering ");
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
                                                $queryExtract = mysqli_query($conn, "SELECT * FROM $tableExtract WHERE status = 1 AND lang = $currentLanguage ORDER BY title ASC");
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

                                            echo "<p>";
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
                                    <input class='more' type="submit" name="hitted" value="Izmeni lozinku" />
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
