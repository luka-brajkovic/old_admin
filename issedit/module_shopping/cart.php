<?php
require("../library/config.php");
$f->checkLogedAdmin("module");
$infomsg = $f->getValue("infomsg");

$module_name = "shopping_carts";

$cart_rid = $f->getValue("cart_rid");

$tablePrint = new Tableprint();
$itemsCollection = new Collection("korpa");
$items = $itemsCollection->getCollection("WHERE id = '$cart_rid'");
$num = $items->$totalCount;

$cart = new View("korpa", $cart_rid);

$ostaloUKorpi = mysql_query("SELECT * FROM proizvodi_korpe WHERE korpa_rid = $cart_rid") or die(mysql_query());
if (mysql_num_rows($ostaloUKorpi) == 0) {
    $db->execQuery("DELETE FROM korpa WHERE id = $cart_rid");
}

if ($cart->id == '') {
    $f->redirect("index.php");
}

$user = mysql_query("SELECT *, `e-mail` as email FROM _content_korisnici WHERE id = $cart->user_id");
$user = mysql_fetch_object($user);

$grad = mysql_query("SELECT title, postanski_broj FROM _content_gradovi WHERE resource_id = $cart->grad LIMIT 1");
$grad = mysql_fetch_object($grad);
//$cart_id = $f->getValue('cart_id');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />		
        <title><?= ADMIN_TITLE; ?></title>
        <?php include("../head.php"); ?>
    </head>
    <body>
        <!-- Container -->
        <div id="container">

            <?php include("../header.php"); ?>

            <!-- Background wrapper -->
            <div id="bgwrap">

                <!-- Main Content -->
                <div id="content">
                    <div id="main">
                        <h1>
                            <?= ($user->firma) ? $user->firma . "<br>" : ""; ?>
                            <?= $user->name; ?> <?= $user->nachname; ?> </h1>

                        <?php
                        if ($infomsg != "") {

                            switch ($infomsg) {
                                case "success_add_item":
                                    ?>
                                    <div class="message success close">
                                        <h2>Success!</h2>
                                        <p>New item successfuly added.</p>
                                    </div>
                                    <?php
                                    break;

                                case "success_delete_item":
                                    ?>
                                    <div class="message success close">
                                        <h2>Success!</h2>
                                        <p>Item successfuly deleted.</p>
                                    </div>
                                    <?php
                                    break;

                                case "success_edit_item":
                                    ?>
                                    <div class="message success close">
                                        <h2>Success!</h2>
                                        <p>Item successfuly updated.</p>
                                    </div>
                                    <?php
                                    break;
                            }
                        }
                        ?>
                        <p>
                            <h2>Adresa: <?= $cart->adresa . " " . $cart->naselje . ", " . $grad->postanski_broj . " " . $grad->title; ?></h2>
                            <!---- <h2>Tip kupca: <?= ($user->tip_kupca) ? $user->tip_kupca : "Fizičko lice"; ?></h2> ---->
                            <h2>Telefon: <?= $cart->telefon; ?></h2>
                            <h2>E-mail: <?= $user->email; ?></h2>
                            <?php
                            if ($cart->napomena != "") {
                                ?>
                                <h2><span style="color: red">Napomena: <?= $cart->napomena; ?></span></h2>
                            <?php } ?>
                            <h2 style="color:green">Plaćanje i dostava: <?php
                                switch ($cart->nacin_placanja) {
                                    case 1:
                                        echo "Pouzećem (plaćanje po dostavi)";
                                        break;
                                    case 2:
                                        echo "Uplatnicom (plaćanje se vrši pre dostave)";
                                        break;
                                };
                                ?></h2>
                            <h2>Status: <?php
                                switch ($cart->status) {
                                    case 1:
                                        echo "Tek pristigla korpa";
                                        break;
                                    case 2:
                                        echo "Poslata roba";
                                        break;
                                    case 3:
                                        echo "Plaćeno";
                                        break;
                                    default:
                                        echo "Trash";
                                };
                                ?>
                            </h2>
                            <hr>
                                <script type="text/javascript">
                                    $(document).ready(function () {

                                        $("h2#admin_zaposleni strong").dblclick(function () {

                                            var value = $(this).html();
                                            var input_html = "<input type='text' value='" + value + "' /><button>Sacuvaj</button>";
                                            $(this).html(input_html);
                                        });
                                        $("h2#broj_fakture strong").dblclick(function () {

                                            var value = $(this).html();
                                            var input_html = "<input type='text' value='" + value + "' /><button>Sacuvaj</button>";
                                            $(this).html(input_html);
                                        });
                                    });

                                    $(document).on("click", "h2#admin_zaposleni strong button", function () {
                                        var cart_id = $(this).parent().data("id");
                                        var admin_zaposleni = $(this).parent().find("input[type='text']").val();

                                        var href = document.location.href;
                                        $.ajax({
                                            method: "post",
                                            url: "work.php",
                                            data: {'cart_id': cart_id, 'action': "change_admin_zaposleni", 'admin_zaposleni': admin_zaposleni}
                                        })
                                                .done(function (msg) {

                                                    window.location.href = href;
                                                });
                                    });


                                    $(document).on("click", "h2#broj_fakture strong button", function () {
                                        var cart_id = $(this).parent().data("id");
                                        var broj_fakture = $(this).parent().find("input[type='text']").val();

                                        var href = document.location.href;
                                        $.ajax({
                                            method: "post",
                                            url: "work.php",
                                            data: {'cart_id': cart_id, 'action': "change_broj_fakture", 'broj_fakture': broj_fakture}
                                        })
                                                .done(function (msg) {
                                                    window.location.href = href;
                                                });
                                    });
                                </script>
                                <h2 id="admin_zaposleni">Zaposleni koji preuzima korpu: <strong data-id='<?= $cart->id; ?>'><?= ($cart->admin_zaposleni) ? "$cart->admin_zaposleni" : "<input type='text' value='' placeholder='Unesite ime i prezime zaposlenog koji zavrsava predracun' /><button>Sacuvaj</button>"; ?></strong></h2>
                                <br><br>
                                        <h2 id="broj_fakture">Broj fakture: <strong data-id='<?= $cart->id; ?>'><?= ($cart->broj_fakture) ? "$cart->broj_fakture" : "<input type='text' value='' placeholder='Unesite broj fakture' /><button>Sacuvaj</button>"; ?></strong></h2>
                                        <i>Ukoliko želite da promenite broj fakture dvaput kliknite na broj fakture</i>
                                        </p>
                                        <input type="text" name="search_input" id="search_input" class="sf right_input" onblur="if (this.value == '')
                                                    this.value = this.defaultValue;" onfocus="if (this.value == this.defaultValue)
                                                                this.value = '';" value="Live search..." />
                                        <br clear="all" />

                                        <hr />
                                        <p>
                                            <?php $tablePrint->printShoppingCartItems($cart->id); ?>
                                        </p>
                                        <script type="text/javascript">
                                            $(document).ready(function () {
                                                $(".status_korpe a").click(function () {
                                                    var dataStatus = $(this).data("status");
                                                    var cart_id = '<?= $cart->id; ?>';
                                                    var href = document.location.href;
                                                    $.ajax({
                                                        method: "post",
                                                        url: "work.php",
                                                        data: {'status': dataStatus, 'id': cart_id, 'action': "change_status_cart"}
                                                    })
                                                            .done(function (msg) {
                                                                window.location.href = href;
                                                            });
                                                });
                                            });
                                        </script>    
                                        <form method="POST" action="">
                                            <h2>Operacije sa korpom</h2>
                                            <hr>
                                                <div class="status_korpe clear">
                                                    <div style="width: 20%; float:left;">
                                                        <p>Status korpe:</p>
                                                    </div>
                                                    <div style="width:80%; float:right;" class="clear"> 
                                                        <a data-status="1" <?= ($cart->status == 1) ? "class='active'" : ""; ?> href="javascript::">TEK PRISTIGLA KORPA</a>
                                                        <a data-status="2" <?= ($cart->status == 2) ? "class='active'" : ""; ?> href="javascript::">POSLATA KORPA</a>
                                                        <a data-status="3" <?= ($cart->status == 3) ? "class='active'" : ""; ?> href="javascript::">PLAĆENA KORPA</a>
                                                    </div>    
                                                </div>

                                                <hr>
                                                    <a target='_blank' href="cart_print.php?cart_rid=<?= $cart->id; ?>">ŠTAMPAJ KORPU (PROVERITE Da li korpa ima broj fakture)</a>    
                                                    </form>
                                                    </div>
                                                    </div>
                                                    <!-- End of Main Content -->





                                                    </div>
                                                    <!-- End of bgwrap -->

                                                    </div>
                                                    <!-- End of Container -->

                                                    <?php include("../footer.php"); ?>

                                                    </body>
                                                    </html>