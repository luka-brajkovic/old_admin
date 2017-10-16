<?php
include_once("library/config.php");
$action = $f->getValue("action");
switch ($action) {
    
    case "check_if_mail_exist":

        $email = $f->getValue("email");
        $sql = "SELECT id FROM _content_korisnici WHERE `e-mail` = '$email'";
        $num = $db->numRows($sql);
        if ($num > 0) {
            $sql = "SELECT id FROM _content_korisnici WHERE `e-mail` = '$email' AND status = 1";
            $numActive = $db->numRows($sql);
            if ($numActive > 0) {
                /* IMA AKTIVNIH */
                $body = "<p>Poštovani, u nastavku je link preko kojeg će te dobiti formu za promenu e-mail adrese.</p>";
                $body .= "<p><a href='" . $configSiteDomain . "moj-nalog/promena-lozinke/" . md5($email) . "' >Promena lozinke</a></p>";
                $body .= "<p><strong>" . ucfirst(str_replace("www.", "", $SERVER_NAME)) . "</strong></p>";
                $yesterday = date("Y-m-d", time() - 60 * 60 * 24);
                $userQuery = mysql_query("SELECT * FROM _content_korisnici WHERE `e-mail` = '$email' AND poslat_email = '" . date("Y-m-d") . "'");
                if (mysql_num_rows($userQuery) > 0) {
                    echo "4";
                } else {
                    mysql_query("UPDATE _content_korisnici SET `poslat_email` = '" . date("Y-m-d") . "' WHERE `e-mail` = '$email'");
                    $f->sendEmail($configSiteEmail, ucfirst(str_replace("www.", "", $SERVER_NAME)), $email, "Link za promenu lozinke", $body);
                    echo "1";
                }
            } else {
                /* NEAKTIVNI */
                echo "2";
            }
        } else {
            echo "3";
        }
        break;
    case "change_show_type":

        $klasa = $f->getValue("klasa");

        $_SESSION["show_type"] = $klasa;

        break;

    case "cats":

        $catCol = new Collection("_content_lekovi");
        $catArr = $catCol->getCollection("WHERE status = 1");
        foreach ($catArr as $cat) {

            $title = $cat->url;

            $cat->slika = strtolower($title) . ".jpg";
            $cat->Save();
        }

        echo "Sacuvano";
        break;


    case "ciljne":

        $lekoviCol = new Collection("_content_lekovi");
        $lekoviArr = $lekoviCol->getCollection("WHERE status = 1");

        foreach ($lekoviArr as $lek) {
            echo $lek->lekovi_po_ciljnim_vrstama . "<br>";
        }

        break;




    case "odjava":

        if (isset($_SESSION["loged_user"])) {
            if (session_id() != "") {
                $sesIdKorpe = session_id();
                $korpari = mysql_query("SELECT id FROM korpa WHERE session_id = '$sesIdKorpe' LIMIT 1");
                $korpari = mysql_fetch_object($korpari);
                if ($korpari->id != "") {
                    mysql_query("DELETE FROM `korpa` WHERE id = $korpari->id");
                    mysql_query("DELETE FROM `proizvodi_korpe` WHERE korpa_rid = $korpari->id");
                }
            }
            unset($_SESSION["loged_user"]);
        }

        $f->redirect("/");

        break;
    case "send_email":

        $email = $f->stringCleaner($f->getValue("email"));
        $user = $f->stringCleaner($f->getValue("ime"));
        $subject = $f->stringCleaner($f->getValue("naslov"));
        $body = $f->stringCleaner($f->getValue("poruka"));


        require("library/phpmailer/class.phpmailer.php");

        $mail = new PHPMailer();
        $mail->From = $email;
        $mail->FromName = "Visual Media doo | Branding Marketing Advertising";
        $mail->AddAddress($configSiteEmail);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->Send();

        $_SESSION["email_sent"] = true;
        $f->redirect("/kontakt");
        break;

    case "aktivacija":

        $email = $f->getValue("md5email");

        $usersCol = new Collection("users");
        $users = $usersCol->getCollection("WHERE status = 2 AND md5(email) = '$email' ");
        if (count($users) == 1) {
            $user = $users[0];
            $user->status = 1;
            $user->Save();

            $_SESSION["loged_user"] = $user->id;
            if (isset($_SESSION["vrati_posle_na"])) {
                $f->redirect($_SESSION["vrati_posle_na"]);
            } else {
                $_SESSION["first_timer"] = true;

                $f->redirect("/");
            }
        } else {
            $f->redirect("/");
        }


        break;

    case 'send_link':

        $email = $f->getValue("md5email");
        $code = $f->getValue("md5code");

        $usersCol = new Collection("users");
        $users = $usersCol->getCollection("WHERE status = 2 AND md5(email) = '$email' AND md5(code) = '$code'");
        if (count($users) == 1) {

            $novi_korisnik = $users[0];

            $body = "<p>Poštovani<br>još samo jedan korak i završili ste registraciju.</p>";
            $body .= "Kliknite <a href='" . $configSiteDomain . "aktiviraj/" . md5($novi_korisnik->email) . "'>OVDE</a>";
            $body .= "<br><br>Čim kliknete na aktivacioni link bićete preusmereni na web sajt kao prijavljen korisnik.<br><br>";
            $body .= "Hvala Vam na interesovanju.<br><br>";
            $body .= "Osoblje web sajta voucher.rs";


            require("library/phpmailer/class.phpmailer.php");

            $mail = new PHPMailer();
            $mail->From = "noreply@voucher.rs";
            $mail->FromName = "Voucher.rs | popusti, ponude, proizvodi, sve na jednom mestu";
            $mail->AddAddress($novi_korisnik->email);
            $mail->Subject = "Aktivacioni link | Voucher.rs";
            $mail->Body = $body;
            $mail->Send();

            $_SESSION["poslata_aktivacija"] = true;

            $f->redirect("/registracija");
        } else {
            $f->redirect("/");
        }

        break;

    case "remove-from-compare":

        $rid = $f->getValue("rid");

        if (isset($_SESSION["compare"])) {
            $niz = $_SESSION["compare"];
            if (($key = array_search("'" . $rid . "'", $niz)) !== false) {
                unset($niz[$key]);
            }
            $_SESSION["compare"] = $niz;
            if (count($_SESSION["compare"]) > 0) {
                $colProds = new Collection("_content_proizvodi");
                $colProdsArr = $colProds->getCollection("WHERE `status` = 1 AND `resource_id` IN (" . implode(",", $niz) . ")");
                ?>
                <a id="comparasion" href="/uporedi">Uporedi</a> 
                <?php
                foreach ($colProdsArr as $item) {
                    ?>
                    <a title="<?= $item->title; ?>" href="javascript:"><?= $item->title; ?> <span data-rid='<?= $item->resource_id; ?>'>x</span></a>
                    <?php
                }
            } else {
                echo "fadeOut";
            }
        }



        break;
    case "add-to-compare":

        $rid = $f->getValue("rid");

        $colProds = new Collection("_content_proizvodi");

        if (isset($_SESSION["compare"]) && count($_SESSION["compare"]) > 0) {

            $niz = $_SESSION["compare"];

            if (!in_array("'" . $rid . "'", $_SESSION["compare"]) && count($_SESSION["compare"]) < 3) {
                $whatCat = mysql_query("SELECT cp.*,c4.resource_id as cat_rid_sub "
                        . " FROM _content_proizvodi cp"
                        . " LEFT JOIN categories_content cc ON cp.resource_id = cc.content_resource_id "
                        . " LEFT JOIN categories c4 ON cc.category_resource_id = c4.resource_id "
                        . " LEFT JOIN categories c3 ON c4.parent_id = c3.resource_id "
                        . " WHERE cp.`status` = 1 AND cp.`resource_id` IN (" . implode(",", $niz) . ")") or die(mysql_error());

                $whatCatArr = mysql_fetch_array($whatCat);

                $allowCat = $whatCatArr["cat_rid_sub"];

                $whatCatToPut = mysql_query("SELECT cp.*,c4.resource_id as cat_rid_sub "
                        . " FROM _content_proizvodi cp"
                        . " LEFT JOIN categories_content cc ON cp.resource_id = cc.content_resource_id "
                        . " LEFT JOIN categories c4 ON cc.category_resource_id = c4.resource_id "
                        . " LEFT JOIN categories c3 ON c4.parent_id = c3.resource_id "
                        . " WHERE cp.`status` = 1 AND cp.`resource_id` = '$rid'") or die(mysql_error());

                $whatCatToPutArr = mysql_fetch_array($whatCatToPut);

                $allowCatToPut = $whatCatToPutArr["cat_rid_sub"];

                if (($allowCat == $allowCatToPut)) {

                    array_push($_SESSION["compare"], "'" . $rid . "'");
                    array_push($niz, "'" . $rid . "'");
                    $puted = true;
                } else {
                    $puted = false;
                }
            } else {
                $puted = false;
            }
        } else {
            $niz = array("'" . $rid . "'");
            $_SESSION["compare"] = $niz;

            $puted = true;
        }

        if ($puted) {
            ?>
            <a id="comparasion" href="/uporedi">Uporedi</a> 
            <?php
            $colProdsArr = $colProds->getCollection("WHERE `status` = 1 AND `resource_id` IN (" . implode(",", $niz) . ")");

            foreach ($colProdsArr as $item) {
                ?>
                <a title="<?= $item->title; ?>" href="javascript:"><?= $item->title; ?> <span data-rid='<?= $item->resource_id; ?>'>x</span></a>
                <?php
            }
        } else {
            echo 1;
        }

        break;

    case "remove-from-wish":

        $rid = $f->getValue("rid");
        $user_id = $userData->resource_id;

        $itemsInW = new Collection("list_zelja");
        $itemsInWArr = $itemsInW->getCollection("WHERE `product_rid` = '$rid' AND `user_rid` = '$userData->resource_id' ");
        if (count($itemsInWArr) == 1) {
            $newWish = $itemsInWArr[0];
            $newWish->Remove();
        }
        $itemsInWArr = $itemsInW->getCollection("WHERE `user_rid` = '$userData->resource_id'");
        echo count($itemsInWArr);
        break;

    case "add-to-wish":

        if ($isLoged) {
            $rid = $f->getValue("rid");
            $user_id = $userData->resource_id;
            $itemsInW = new Collection("list_zelja");
            $itemsInWArr = $itemsInW->getCollection("WHERE `product_rid` = '$rid' AND `user_rid` = '$userData->resource_id' ");
            if (count($itemsInWArr) == 0) {
                $newWish = new View("list_zelja");
                $newWish->product_rid = $rid;
                $newWish->user_rid = $user_id;
                $newWish->system_date = date("Y-m-d H:i:s");
                $newWish->Save();
            }
            $itemsInWArr = $itemsInW->getCollection("WHERE `user_rid` = '$userData->resource_id'");
            echo count($itemsInWArr);
        }
        break;



    case "add-to-cart":

        $itemID = $_POST['itemID'];
        $price = $_POST['price'];
        $q = $_POST['q'];
        $proizvod = new View("_content_proizvodi", $itemID, 'resource_id');
        $brandic = $db->getValue("title", "_content_brend", "resource_id", "$proizvod->brand");
        $prodForCart = $brandic . " " . $proizvod->title;

        $sessionID = session_id();

        $korpa = new View("korpa", $sessionID, 'session_id');
        if (!empty($korpa->id)) {
            $korpaID = $korpa->id;

            $query = $db->execQuery("SELECT id from proizvodi_korpe WHERE korpa_rid = $korpaID AND original_rid = $itemID");
            $proizvodKorpe = mysql_fetch_row($query);

            if (isset($proizvodKorpe[0])) {

                $item = new View("proizvodi_korpe", $proizvodKorpe[0]);
                $item->kolicina = $item->kolicina + $q;
                $item->Save();
            } else {
                $item = new View("proizvodi_korpe");
                $item->korpa_rid = $korpaID;
                $item->original_rid = $itemID;
                $item->title = $prodForCart;
                $item->cena = $price;
                $item->kolicina = $q;
                $item->gratis = $proizvod->gratis_id;
                $item->gratis2 = $proizvod->gratis_id_2;
                $item->Save();
            }
        } else {
            $korpa = new View("korpa");
            $korpa->session_id = $sessionID;
            $korpa->status = 0;
            $korpa->Save();
            $korpaID = $korpa->id;

            $item = new View("proizvodi_korpe");
            $item->korpa_rid = $korpaID;
            $item->original_rid = $itemID;
            $item->title = $prodForCart;
            $item->cena = $price;
            $item->kolicina = $q;
            $item->gratis = $proizvod->gratis_id;
            $item->gratis2 = $proizvod->gratis_id_2;
            $item->Save();
        }

        $query_u_korpi = mysql_query("SELECT * FROM proizvodi_korpe WHERE korpa_rid = $korpa->id ");

        echo mysql_num_rows($query_u_korpi);

        break;


    case "remove-from-cart":

        $id = $_POST['itemID'];
        $db->execQuery("DELETE FROM proizvodi_korpe WHERE id = $id");

        break;

    case "update-q-cart":
        $id = $_POST['itemID'];
        $q = $_POST['q'];
        $db->execQuery("UPDATE proizvodi_korpe SET kolicina = $q WHERE id = $id");
        break;

    case "update-user-info-cart":
        $userID = $_SESSION["loged_user"];
        $dostava = $_POST['dostava'];
        $sessionID = session_id();
        $korpa = new View("korpa", $sessionID, 'session_id');
        if ($dostava == 1) {
            $user = new View("_content_korisnici", $userID);
            $korpa->ime = $user->ime;
            $korpa->prezime = $user->prezime;
            $korpa->telefon = $user->mobilni_telefon;
            $korpa->adresa = $user->ulica_i_broj;
            $korpa->grad = $user->grad;
            $korpa->zip = $user->postanski_broj;
            $korpa->user_id = $userID;
            $korpa->Save();
        } else {
            $korpa->ime = $_POST['ime'];
            $korpa->prezime = $_POST['prezime'];
            $korpa->telefon = $_POST['telefon'];
            $korpa->adresa = $_POST['adresa'];
            $korpa->grad = $_POST['grad'];
            $korpa->zip = $_POST['zip'];
            $korpa->user_id = $userID;
            $korpa->Save();
        }

        break;


    case "korpa-zavrsi":

        $sessionID = session_id();

        $korpa = new View("korpa", $sessionID, 'session_id');
        $korpa->nacin_placanja = $f->getValue('nacin_placanja');
        $korpa->napomena = $f->getValue('napomena');
        $korpa->status = 1;
        $korpa->user_id = $userData->id;

        $korpa->system_date = date("Y-m-d H-i-s");
        $korpa->session_id = "zavrseno";
        $korpa->Save();

        $emailQ = mysql_query("SELECT `e-mail` as email FROM _content_korisnici WHERE id = $korpa->user_id ");
        $emailArr = mysql_fetch_array($emailQ);
        $email = $emailArr[email];

        $db->execQuery("UPDATE korpa SET `session_id` = '' WHERE `id` = '$korpa->id' ");

        $body = "Nova kupovina koju možete videti i kontrolisati u admin delu sajta na stranici Korpe!";
        $f->sendEmail($configSiteEmail, $name, $configSiteEmail, "Nova kupovina na sajtu " . $configSiteFirm, $body);

        break;
}
?>