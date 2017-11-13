<?php
require("../library/config.php");
$f->checkLogedAdmin("module");
$infomsg = $f->getValue("infomsg");

$module_name = "shopping_carts";

$tablePrint = new Tableprint();
$cartsCollection = new Collection("korpa");

$whereSQL = " id != '' ";

$filter = $f->getValue("filter");
if ($filter == '') {
    $filter = 1;
    $add = " AND status = $filter ";
} else if ($filter == 1 || $filter == 2 || $filter == 3) {
    $add = " AND status = $filter ";
} else if ($filter == 4) {
    $add = " AND (status = 1 OR status = 2 OR status = 3) ";
}
//echo $whereSQL;	
$whereSQL .= " $add ";




$carts = $cartsCollection->getCollectionCustom("SELECT * FROM korpa WHERE " . $whereSQL . " ORDER BY system_date DESC");
$num = $carts->$totalCount;
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
                        <h1>Shopping carts</h1>

                        <?php
                        if ($infomsg != "") {

                            switch ($infomsg) {
                                case "success_add_cart":
                                    ?>
                                    <div class="message success close">
                                        <h2>Success!</h2>
                                        <p>New cart successfuly added.</p>
                                    </div>
                                    <?php
                                    break;

                                case "success_delete_cart":
                                    ?>
                                    <div class="message success close">
                                        <h2>Success!</h2>
                                        <p>Cart successfuly deleted.</p>
                                    </div>
                                    <?php
                                    break;

                                case "success_edit_cart":
                                    ?>
                                    <div class="message success close">
                                        <h2>Success!</h2>
                                        <p>Cart successfuly updated.</p>
                                    </div>
                                    <?php
                                    break;
                            }
                        }
                        ?>	<p>
                            <form method="GET" action="index.php" class="left_form w590">
                                <select name="filter" onchange="this.form.submit()">
                                    <option value="1" <?php if ($filter == 1) echo 'selected="selected"'; ?>>New cart</option>
                                    <option value="2" <?php if ($filter == 2) echo 'selected="selected"'; ?>>Send item</option>
                                    <option value="3" <?php if ($filter == 3) echo 'selected="selected"'; ?>>Paid</option>

                                    <option value="4" <?php if ($filter == 4) echo 'selected="selected"'; ?>>All carts</option>
                                </select>
                            </form>
                        </p>
                        <input type="text" name="search_input" id="search_input" class="sf right_input" onblur="if (this.value == '')
                                    this.value = this.defaultValue;" onfocus="if (this.value == this.defaultValue)
                                                this.value = '';" value="Live search..." />
                        <br clear="all" />

                        <hr />
                        <p>
                            <?php $tablePrint->printShoppingCarts($carts); ?>
                        </p> 

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