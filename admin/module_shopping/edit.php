<?php
require("../library/config.php");
$f->checkLogedAdmin("module");

$module_name = "shopping_carts";

$cart_id = $f->getValue("cart_id");

$cart = new View("shopping_carts", $cart_id);
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
                        <h1>Edit shopping cart - <?= $cart->title; ?></h1>

                        <form method="POST" action="work.php" name="edit_cart" id="edit_cart">
                            <fieldset>
                                <legend>Edit cart</legend>
                                <p>
                                    <label for="status">Status</label>
                                    <select name="status">
                                        <option value="1" <?php if ($cart->status == 1) echo 'selected="selected"'; ?>>Active</option>
                                        <option value="0" <?php if ($cart->status == 0) echo 'selected="selected"'; ?>>Inactive</option>
                                    </select>
                                    <span class="validate_error" id="title_error"></span>				
                                </p>
                                <p>
                                    <label for="sent">Sent</label>
                                    <select name="sents">
                                        <option value="1" <?php if ($cart->sent == 1) echo 'selected="selected"'; ?>>Yes</option>
                                        <option value="0" <?php if ($cart->sent == 0) echo 'selected="selected"'; ?>>No</option>
                                    </select>
                                    <span class="validate_error" id="title_error"></span>				
                                </p>
                                <input type="hidden" name="action" id="action" value="edit_cart"  />
                                <input type="hidden" name="cart_id" id="cart_id" value="<?= $cart->id ?>"  />
                                <input type="submit" name="save_button" id="save_button" value="Save" style="" class="button"  />
                            </fieldset>
                        </form>	

                        <hr />

                    </div>
                </div>
                <!-- End of Main Content -->


                <?php include("../sidebar.php"); ?>


            </div>
            <!-- End of bgwrap -->

        </div>
        <!-- End of Container -->

        <?php include("../footer.php"); ?>

    </body>
</html>