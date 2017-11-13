<?php
require("../library/config.php");
$f->checkLogedAdmin("module");
$infomsg = $f->getValue("infomsg");

$sort = $f->getValue("sort");
switch ($sort) {
    default:
        $whereSQL = "";
        $orderSQL = "";
        break;

    case "approved":
        $whereSQL = "WHERE status = '1'";
        break;

    case "unapproved":
        $whereSQL = "WHERE status = '0'";
        break;

    case "banned":
        $whereSQL = "WHERE status = '2'";
        break;

    case "fb":
        $whereSQL = "WHERE fbuser = '1'";
        break;

    case "last3days":
        $whereSQL = "WHERE DATEDIFF(current_date, date_added ) <=3";
        break;
    case "ASC":
        $orderSQL = "ORDER BY date_added";
        break;
    case "DESC":
        $orderSQL = "ORDER BY date_added DESC";
        break;
}

//Ovo modul_name ostaje jer ce da se koristi kasnije za pisanje sidebar.php fajla
$module_name = "users";

$tablePrint = new Tableprint();

$usersCollection = new Collection("users");

$limit = $f->getValue("limit");
$usersCollection->_limit = $limit;

$users = $usersCollection->getCollection($whereSQL, $orderSQL);
$num = $users->$totalCount;
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
                        <h1>Users</h1>
                        <!--
                            Ovde ces da stavis vatanje notifikacija kao sto je na kategorijama.
                            Ja cu da ti prekopriam, pa ti samo implementiraj, videces kako se to radi.
                        -->

                        <?php
                        if ($infomsg != "") {

                            switch ($infomsg) {
                                case "success_add_user":
                                    ?>
                                    <div class="message success close">
                                        <h2>Success!</h2>
                                        <p>New user successfuly added.</p>
                                    </div>
                                    <?php
                                    break;

                                case "success_delete_user":
                                    ?>
                                    <div class="message success close">
                                        <h2>Success!</h2>
                                        <p>User successfuly deleted.</p>
                                    </div>
                                    <?php
                                    break;

                                case "success_edit_user":
                                    ?>
                                    <div class="message success close">
                                        <h2>Success!</h2>
                                        <p>User successfuly updated.</p>
                                    </div>
                                    <?php
                                    break;
                            }
                        }
                        ?>

                        <form method="POST" id="add_user" name="add_user" action="work.php">
                            <fieldset>
                                <legend>Add user</legend>
                                <p>
                                    <label for="fullname">Fullname *</label>
                                    <input type="text" class="lf" name="fullname" id="fullname" required="required"/>
                                </p>
                                <input type="hidden" name="action" value="add_user"/>
                                <input type="submit" class="button" value="Save"/>
                            </fieldset>
                        </form>

                        <hr />
                        <p>
                            <form method="GET" action="index.php" class="left_form w590">

                                <select name="sort" class="w120">
                                    <option value=""  <?php if ($sort == "") echo 'selected="selected"'; ?>>Category...</option>
                                    <option value="approved"  <?php if ($sort == "approved") echo 'selected="selected"'; ?>>Approved</option>
                                    <option value="unapproved"  <?php if ($sort == "unapproved") echo 'selected="selected"'; ?>>Not approved</option>
                                    <option value="banned"  <?php if ($sort == "banned") echo 'selected="selected"'; ?>>Banned</option>
                                    <option value="fbuser"  <?php if ($sort == "fbuser") echo 'selected="selected"'; ?>>Facebook users</option>
                                    <option value="last3days"  <?php if ($sort == "last3days") echo 'selected="selected"'; ?>>Users in last 3 days</option>
                                    <option value="ASC"  <?php if ($sort == "ASC") echo 'selected="selected"'; ?>>Date ASC</option>
                                    <option value="DESC"  <?php if ($sort == "DESC") echo 'selected="selected"'; ?>>Date DESC</option>
                                </select>

                                <select name="limit" class="w120">
                                    <option value="50"  <?php if ($limit == 50) echo 'selected="selected"'; ?>>Show 50</option>
                                    <option value="100" <?php if ($limit == 100) echo 'selected="selected"'; ?>>Show 100</option>
                                    <option value="200" <?php if ($limit == 200) echo 'selected="selected"'; ?>>Show 200</option>
                                    <option value=""  <?php if ($limit == "") echo 'selected="selected"'; ?>>Show all</option>
                                </select>

                                <input type="submit" class="button" value="Filter" />
                            </form>
                        </p>
                        <p>
                            <input type="text" name="search_input" id="search_input" class="sf right_input" onblur="if (this.value == '')
                                        this.value = this.defaultValue;" onfocus="if (this.value == this.defaultValue)
                                                    this.value = '';" value="Live search..." />
                            <br clear="all" />
                        </p>
                        <hr />
                        <?php
                        $tablePrint->printUsersTable($users);
                        ?>

                    </div>
                </div>
                <!-- End of Main Content -->

                <!-- Ovo ovde ostaje, cita se sidebar -->
                <?php include("../sidebar.php"); ?>


            </div>
            <!-- End of bgwrap -->

        </div>
        <!-- End of Container -->

        <!-- Ovo ovde ostaje, cita se footer i to je to -->
        <?php include("../footer.php"); ?>

    </body>
</html>