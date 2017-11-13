<?php

require("../library/config.php");
$resizeImage = new ResizeImage();

$values = $f->getRequestValues();
$action = $f->getValue('action');

switch ($action) {



    case "change_status_cart":
        $cart_id = $f->getValue("id");

        /* SVO SLANJE MEJLOVA RADIS OVDE */


        /* END SLANJE MEJLOVA */

        $cart = new View("korpa", $cart_id);

        $oldStatus = $cart->status;


        $cart->status = $f->getValue("status");
        $cart->Save();

        if ($oldStatus == 1 && $f->getValue("status") == 2) {

            $cart->datum_poslata = date("Y-m-d H:i:s");
            $cart->Save();
        }

        echo "SAVED";
        break;

    case "change_admin_zaposleni":
        $cart_id = $f->getValue("cart_id");

        $result = mysqli_query($conn,"SHOW COLUMNS FROM `korpa` LIKE 'admin_zaposleni'");
        $exists = (mysqli_num_rows($result)) ? TRUE : FALSE;
        if (!$exists) {
            mysqli_query($conn,"ALTER TABLE `korpa` ADD admin_zaposleni VARCHAR( 255 ) ") or die(mysqli_error($conn));
            mysqli_query($conn,"ALTER TABLE `korpa` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci") or die(mysqli_error($conn));
        }

        $cart = new View("korpa", $cart_id);
        $cart->admin_zaposleni = $f->getValue("admin_zaposleni");
        $cart->Save();
        echo "SAVED";
        break;

    case "change_broj_fakture":
        $cart_id = $f->getValue("cart_id");

        /* SVO SLANJE MEJLOVA RADIS OVDE */


        /* END SLANJE MEJLOVA */

        $cart = new View("korpa", $cart_id);
        $cart->broj_fakture = $f->getValue("broj_fakture");
        $cart->Save();
        echo "SAVED";
        break;

    case "change_kolicina":
        $proizvod_id = $f->getValue("proizvod_id");
        $kolicina = $f->getValue("kolicina");
        $proizvod = new View("proizvodi_korpe", $proizvod_id);
        $korpaID = $proizvod->korpa_rid;



        $proizvod->kolicina = $kolicina;
        if ($kolicina == 0 || $kolicina == '') {
            $proizvod->Remove();
        } else {
            $proizvod->Save();
        }

        $ostaloUKorpi = mysqli_query($conn,"SELECT * FROM proizvodi_korpe WHERE korpa_rid = $korpaID");
        if (mysqli_num_rows($ostaloUKorpi) == 0) {
            $db->execQuery("DELETE FROM korpa WHERE id = $korpaID");
        }

        echo "SAVED";
        break;

    case "edit_cart":
        $cart_id = $f->getValue("cart_id");
        $cart = new View("shopping_carts", $cart_id);
        $cart->extend($_POST);
        $cart->Save();
        $f->redirect("index.php");
        break;

    case "delete_cart":
        $cart_id = $f->getValue("cart_id");
        $cart = new View("korpa", $cart_id);
        $itemsCollection = new Collection("proizvodi_korpe");
        $items = $itemsCollection->getCollection("WHERE korpa_rid = '$cart->id'");
        foreach ($items as $item) {
            $itemSingle = new View("proizvodi_korpe", $item->id);
            $itemSingle->Remove();
        }
        $cart->Remove();
        $f->redirect("index.php");
        break;
}
?>