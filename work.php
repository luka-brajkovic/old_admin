<?php
include_once("library/config.php");
$action = $f->getValue("action");
$id_token = $f->getValue("idtoken");

switch ($action) {

    case "check_if_mail_exist":

        $email = $f->getValue("email");
        $sql = "SELECT id FROM _content_users WHERE `e-mail` = '$email'";
        $num = $db->numRows($sql);
        if ($num > 0) {
            $sql = "SELECT id FROM _content_users WHERE `e-mail` = '$email' AND status = 1";
            $numActive = $db->numRows($sql);
            if ($numActive > 0) {
                /* IMA AKTIVNIH */
                $body = "<p>Poštovani, u nastavku je link preko kojeg će te dobiti formu za promenu vaše lozinke.</p>";
                $body .= "<p><a href='" . $csDomain . "moj-nalog/promena-lozinke/" . md5($email) . "' >Promena lozinke</a></p>";
                $body .= "<p><strong>" . $csName . "</strong></p>";
                $yesterday = date("Y-m-d", time() - 60 * 60 * 24);
                $userQuery = mysqli_query($conn, "SELECT * FROM _content_users WHERE `e-mail` = '$email' AND poslat_email = '" . date("Y-m-d") . "'");
                if (mysqli_num_rows($userQuery) > 0) {
                    echo "4";
                } else {
                    mysqli_query($conn, "UPDATE _content_users SET `poslat_email` = '" . date("Y-m-d") . "' WHERE `e-mail` = '$email'");

                    $f->sendMail($csEmail, $csName, $email, "", "Link za promenu lozinke - $csName", $body, $currentLanguage);
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

    case "odjava":

        if (isset($_SESSION["loged_user"])) {
            if (session_id() != "") {
                $sesIdKorpe = session_id();
                $korpari = mysqli_query($conn, "SELECT id FROM korpa WHERE session_id = '$sesIdKorpe' LIMIT 1");
                $korpari = mysqli_fetch_object($korpari);
                if ($korpari->id != "") {
                    mysqli_query($conn, "DELETE FROM `korpa` WHERE id = $korpari->id");
                    mysqli_query($conn, "DELETE FROM `proizvodi_korpe` WHERE korpa_rid = $korpari->id");
                }
            }
            unset($_SESSION["loged_user"]);
        }

        $f->redirect("/");

        break;

    case "aktivacija-newsletter":

        $email = $f->getValue("md5_email");

	$usersCol = new Collection("_content_newsletter");
	$users = $usersCol->getCollection("WHERE md5(title) = '$email' LIMIT 1");

	if (count($users) == 1) {
		$user = $users[0];
		$user->status = 1;
		$user->Save();

		$_SESSION['infoTitle'] = "<h1>Uspešna aktivacija</h1>";
		$_SESSION['infoText'] = "<p>Poštovani, uspešno ste aktivirali Vašu prijavu na newsletter.</p>";

		$f->redirect("/");
	}

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

    case "remove-from-compare":

        $rid = $f->getValue("rid");

        if (isset($_SESSION["compare"])) {
            $niz = $_SESSION["compare"];
            if (($key = array_search("'" . $rid . "'", $niz)) !== false) {
                unset($niz[$key]);
            }
            $_SESSION["compare"] = $niz;
            if (count($_SESSION["compare"]) > 0) {
                $colProds = new Collection("_content_products");
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

        $colProds = new Collection("_content_products");

        if (isset($_SESSION["compare"]) && count($_SESSION["compare"]) > 0) {

            $niz = $_SESSION["compare"];

            if (!in_array("'" . $rid . "'", $_SESSION["compare"]) && count($_SESSION["compare"]) < 3) {
                $whatCat = mysqli_query($conn, "SELECT cp.*,c4.resource_id as cat_rid_sub "
                        . " FROM _content_products cp"
                        . " LEFT JOIN categories_content cc ON cp.resource_id = cc.content_resource_id "
                        . " LEFT JOIN categories c4 ON cc.category_resource_id = c4.resource_id "
                        . " LEFT JOIN categories c3 ON c4.parent_id = c3.resource_id "
                        . " WHERE cp.`status` = 1 AND cp.`resource_id` IN (" . implode(",", $niz) . ")") or die(mysqli_error($conn));

                $whatCatArr = mysqli_fetch_array($whatCat);

                $allowCat = $whatCatArr["cat_rid_sub"];

                $whatCatToPut = mysqli_query($conn, "SELECT cp.*,c4.resource_id as cat_rid_sub "
                        . " FROM _content_products cp"
                        . " LEFT JOIN categories_content cc ON cp.resource_id = cc.content_resource_id "
                        . " LEFT JOIN categories c4 ON cc.category_resource_id = c4.resource_id "
                        . " LEFT JOIN categories c3 ON c4.parent_id = c3.resource_id "
                        . " WHERE cp.`status` = 1 AND cp.`resource_id` = '$rid'") or die(mysqli_error($conn));

                $whatCatToPutArr = mysqli_fetch_array($whatCatToPut);

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
        $proizvod = new View("_content_products", $itemID, 'resource_id');
        $brandic = $db->getValue("title", "_content_brand", "resource_id", "$proizvod->brand");
        $prodForCart = $brandic . " " . $proizvod->title;

        $sessionID = session_id();

        if ($proizvod->gratis_id != "") {
            $gratis1 = $proizvod->gratis_id;
        } else {
            $gratis1 = 0;
        }

        if ($proizvod->gratis_id_2 != "") {
            $gratis2 = $proizvod->gratis_id_2;
        } else {
            $gratis2 = 0;
        }

        $korpa = new View("korpa", $sessionID, 'session_id');
        if (!empty($korpa->id)) {
            $korpaID = $korpa->id;

            $query = $db->execQuery("SELECT id from proizvodi_korpe WHERE korpa_rid = $korpaID AND original_rid = $itemID");
            $proizvodKorpe = mysqli_fetch_row($query);

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
                $item->gratis = $gratis1;
                $item->gratis2 = $gratis2;
                $item->Save();
            }
        } else {
            $korpa = new View("korpa");
            $korpa->session_id = $sessionID;
            $korpa->status = 0;
            $korpa->user_id = 0;
            $korpa->nacin_placanja = 0;
            $korpa->num_views = 0;
            $korpa->datum_poslata = "0000-00-00 00:00:00";
            $korpa->system_date = "0000-00-00 00:00:00";
            $korpa->Save();
            $korpaID = $korpa->id;

            $item = new View("proizvodi_korpe");
            $item->korpa_rid = $korpaID;
            $item->original_rid = $itemID;
            $item->title = $prodForCart;
            $item->cena = $price;
            $item->kolicina = $q;
            $item->gratis = $gratis1;
            $item->gratis2 = $gratis2;
            $item->Save();
        }

        $query_u_korpi = mysqli_query($conn, "SELECT * FROM proizvodi_korpe WHERE korpa_rid = $korpa->id ");

        echo mysqli_num_rows($query_u_korpi);

        break;


    case "remove-from-cart":

        $id = $_POST['itemID'];
        $db->execQuery("DELETE FROM proizvodi_korpe WHERE id = $id");

        break;

case "remove-kupon":

        $_SESSION['kupon'] = "";

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
            $user = new View("_content_users", $userID);
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

        $emailQ = mysqli_query($conn, "SELECT `e-mail` as email FROM _content_users WHERE id = $korpa->user_id ");
        $emailArr = mysqli_fetch_array($emailQ);
        $email = $emailArr[email];

        $db->execQuery("UPDATE korpa SET `session_id` = '' WHERE `id` = '$korpa->id' ");

        $body = "Nova kupovina koju možete videti i kontrolisati u admin delu sajta na stranici Korpe!";

        $f->sendMail($csEmail, $csName, $csEmail, "", "Nova kupovina na sajtu - $csName", $body, $currentLanguage);

        break;


    case "logout":
        unset($_SESSION["loged_user"]);

        unset($_SESSION['access_token']);

        //$f->redirect("/");

        break;


    case "login_fb":
        /* require 'facebook.php'; */
        require_once  'Facebook/autoload.php';

        $fb = new \Facebook\Facebook([
            'app_id' => '144319329541447',
            'app_secret' => '3e9c96c425ba74bb4ec9f3320bf2d393',
            'default_graph_version' => 'v2.11',
                //'default_access_token' => '{access-token}', // optional
        ]);

        $accessToken = $_REQUEST['tk'];
        try {
            // Get the \Facebook\GraphNodes\GraphUser object for the current user.
            // If you provided a 'default_access_token', the '{access-token}' is optional.
            $response = $fb->get('/me', $accessToken);

        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $me = $response->getGraphUser();
        echo 'Logged in as ' . $me;

//        $config = array();
//        $config["app_id"] = "2201700689855818";
//        $config["app_secret"] = "8e973efabbeb258a6cb02057e827086a";
//        $fb = new Facebook\Facebook($config);
//
//        $ajax_user_id = $_POST['id'];
//        $ajax_tk = $_POST['tk'];
//
//        $readData = @file_get_contents('https://graph.facebook.com/v2.11/me?fields=id,name,email,picture&access_token=' . $ajax_tk);
//        $fbuser = json_decode($readData);
//
//        var_dump($readData);
//
//        $email = $fbuser->email;
//        if ($email) {
//            $usersCollection = new Collection("_content_users");
//            $usersA = $usersCollection->getCollection("WHERE email = '$email'");
//            list($name,$prezime,$midle)= explode(" ",$fbuser->name);
//
//
//            if (count($usersA) == 0) {
//                $user = new View("_content_users");
//                $user->ime = $name;
//                $user->prezime = $prezime." ".$midle;
//                $user->email = $email;
//                $user->status = 1;
//                $user->fbuser = 1;
//                $user->newsletter = "Da";
//                $user->facebook_id = $fbuser->id;
//                $user->system_date = date("Y-m-d H:i:s");
//                $user->Save();
//                $_SESSION["loged_user"] = $user->id;
//            } else {
//                $user = $usersA[0];
//                $user->status = 1;
//                if (!$user->ime) {
//                    $user->ime = $name;
//                    $user->prezime = $prezime." ".$midle;
//                }
//                $user->Save();
//                $_SESSION["loged_user"] = $user->id;
//            }
//            /* PORUKA AKO HOCE DA MU KAZES DA JE LOGOVAN */
//            echo "1";
//        } else {
//            echo "2";
//        }
        break;


    case "login_google":
        require_once 'vendor/autoload.php';

        $id_token = $_POST['idtoken'];

        $client = new Google_Client(['client_id' => $CLIENT_ID]);
        $payload = $client->verifyIdToken($id_token);
        if ($payload) {
            $email = ($payload['email']);
            $ime = ($payload['given_name']);
            $prezime = ($payload['family_name']);


            if ($email) {
            $usersCollection = new Collection("_content_users");
            $usersA = $usersCollection->getCollection("WHERE `e-mail` = '$email'");

            $upismai = "e-mail";

            if (count($usersA) == 0) {
                $user = new View("_content_users");
                $user->ime = $ime;
                $user->prezime = $prezime;
                $user->$upismai = $email;
                $user->status = 1;
                $user->fbuser = 1;
                $user->newsletter = "Da";
                $user->system_date = date("Y-m-d H:i:s");
                $user->Save();
                $_SESSION["loged_user"] = $user->id;

                $_SESSION['infoTitle'] = "<h1>Dobrodošli</h1>";
                $_SESSION['infoText'] = "<p>Poštovani, želimo Vam prijatno korišćenje naše internet prodavnice.</p>";

            } else {
                $user = $usersA[0];
                $user->status = 1;
                if (!$user->ime) {
                    $user->ime = $name;
                    $user->prezime = $prezime." ".$midle;
                }
                $user->Save();
                $_SESSION["loged_user"] = $user->id;

                $_SESSION['infoTitle'] = "<h1>Dobrodošli</h1>";
                $_SESSION['infoText'] = "<p>Poštovani, želimo Vam prijatno korišćenje naše internet prodavnice.</p>";
            }
            /* PORUKA AKO HOCE DA MU KAZES DA JE LOGOVAN */
            echo "1";
        } else {
            echo "2";
        }



        } else {
            varDump("invalid id token");
        }
        break;
}
?>