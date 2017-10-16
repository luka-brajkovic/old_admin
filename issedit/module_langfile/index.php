<?php
require("../library/config.php");
$f->checkLogedAdmin("module");
$infomsg = $f->getValue("infomsg");

$tablePrint = new Tableprint();
$module_name = "langfile";

$langCollection = new Collection("languages");
$languages = $langCollection->getCollection();

$editMode = true;
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
                    <div id="main" style="margin-left:320px;">
                        <?php
                        if ($infomsg != "") {

                            switch ($infomsg) {
                                case "success_add_language_cons":
                                    ?>
                                    <div class="message success close">
                                        <h2>Success!</h2>
                                        <p>New language constant successfuly added.</p>
                                    </div>
                                    <?php
                                    break;

                                case "success_delete_language_cons":
                                    ?>
                                    <div class="message success close">
                                        <h2>Success!</h2>
                                        <p>Language constant successfuly deleted.</p>
                                    </div>
                                    <?php
                                    break;

                                case "success_edit_language_cons":
                                    ?>
                                    <div class="message success close">
                                        <h2>Success!</h2>
                                        <p>Language constant successfuly updated.</p>
                                    </div>
                                    <?php
                                    break;
                            }
                        }
                        ?>

                        <h1>Language files on website</h1>

                        <form method="POST" action="work.php?action=save_lang_file">
                            <style type="text/css">
                                table thead tr th {font-size:12px; padding:10px; text-align:left; border:1px solid #CCC; background:#DDDDDD;}
                                table tbody tr td {padding:5px; border:1px solid #CCC; }
                                table tbody tr td input {width:90%; padding:3px 2%; background:none; border:none; outline:none;}
                            </style>
                            <table width="100%;" style="border-collapse: collapse; border:1px solid #CCC; ">
                                <thead>
                                    <tr>
                                        <th>Constants</th>
                                        <?php
                                        $langs = mysql_query("SELECT * FROM languages");
                                        while ($langData = mysql_fetch_object($langs)) {
                                            echo "<th>$langData->title</th>";
                                        }
                                        ?>
                                        <th>Erase</th>
                                    </tr>
                                </thead>
                                <tbody id='table_body'>
                                    <?php
                                    $counter = 0;
                                    $rel = 1;
                                    $langs = mysql_query("SELECT * FROM languages");
                                    $trenutni = "";
                                    $countLangs = mysql_num_rows($langs);
                                    $past = array();
                                    $otvoreno = 0;
                                    while ($langData = mysql_fetch_object($langs)) {
                                        ${"xml_" . $langData->code} = simplexml_load_file("../../library/languages/" . $langData->code . ".xml");
                                        foreach (${"xml_" . $langData->code} as $konstanta) {
                                            if ($konstanta->const != '') {
                                                if (!in_array(trim($konstanta->const), $past)) {
                                                    $counter++;
                                                    if ($counter % ($countLangs + 2) == 0) {

                                                        echo "<td><a href='javascript:void(0);' onclick='eraseThisLine($rel);'>Erase This Line</a></td>";
                                                        echo "</tr>";
                                                        $counter++;
                                                        $rel++;
                                                    }
                                                    $subLangs = mysql_query("SELECT * FROM languages");
                                                    array_push($past, trim($konstanta->const));
                                                    $trenutni = trim($konstanta->const);
                                                    echo "<tr id='tr_$rel'>";
                                                    echo "<td><input type='text' name='const_" . $rel . "' value='" . $konstanta->const . "' /> </td>";
                                                    $otvoreno = 1;
                                                }

                                                while ($subLang = mysql_fetch_object($subLangs)) {
                                                    ${"sub_xml_" . $subLang->code} = simplexml_load_file("../../library/languages/" . $subLang->code . ".xml");
                                                    foreach (${"sub_xml_" . $subLang->code} as $subKonst) {
                                                        if ($trenutni == trim($subKonst->const)) {
                                                            $counter++;
                                                            echo "<td><input type='text' name='" . $subLang->code . "_" . $rel . "' value='" . $subKonst->value . "' /></td>";
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    if ($otvoreno == 1) {
                                        $counter++;
                                        echo "<td><a href='javascript:void(0);' onclick='eraseThisLine($rel);'>Erase This Line</a></td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="<?= $countLangs + 2; ?>"></td>
                                    </tr>
                                </tbody>
                            </table>

                            <a onclick='add_new_field();' id='counter' rel="<?= $rel + 1; ?>" style="position: fixed;right: 164px;top: 136px;z-index: 1;width: 128px;height: 39px;background: #454545 none repeat scroll 0% 0%;color: #FFF !important;padding: 5px;text-align: center;cursor: pointer;border-radius: 5px;font-size: 13px;line-height: 39px;" class="button" href='javascript:void(0);'>Add new fields</a>

                            <input type="submit" value="Save" style="position:fixed; right:20px; top:136px; z-index: 1; width:140px; height:50px; background:#454545; color:#FFF; text-align: center; cursor:pointer; border-radius: 5px;" />
                        </form>    
                        <script type="text/javascript">
                            function add_new_field() {
                                var broj = $("#counter").attr("rel");
                                var inputString = '<td><input type="text" name="const_' + broj + '" value="" /></td>';
<?php
$langs = mysql_query("SELECT * FROM languages");
while ($langData = mysql_fetch_object($langs)) {
    ?>
                                    inputString += '<td><input type="text" name="<?= $langData->code; ?>_' + broj + '" value="" /></td>';
    <?php
}
?>
                                inputString += '<td style="text-align:center;"><a href="javascript:void(0);" onclick="eraseThisLine(' + broj + ')">Erase this line</a></td>';
                                $("#table_body").find("tr:first-child").before('<tr id="tr_' + broj + '">' + inputString + '</tr>');
                                broj = parseInt(broj);
                                broj = broj + 1;
                                $("#counter").attr("rel", broj);
                            }
                            function eraseThisLine(broj) {
                                var brojInt = parseInt(broj);
                                var brojString = String(broj);
                                $("#tr_" + broj).remove();
                                var max = $("#counter").attr("rel");
                                var maxInt = parseInt(max);
                                var newMax = maxInt - 1;
                                var newMaxString = String(newMax);
                                maxInt = maxInt - 1;

                                var maxString = String(max);
                                var brojNextInt = 0;
                                var brojNextString = "";
                                var firstValue = "";

                                for (var i = brojInt; i < maxInt; i++) {
                                    brojInt = i;
                                    brojString = String(brojInt);
                                    brojNextInt = i + 1;
                                    brojNextString = String(brojNextInt);
                                    firstValue = $("#tr_" + brojNextString + " td:nth-child(1) input").attr("name");
                                    firstValue = firstValue.replace(brojNextString, brojString);
                                    $("#tr_" + brojNextString + " td:nth-child(1) input").attr("name", firstValue);
<?php
$langs = mysql_query("SELECT * FROM languages");
$counter = 1;
while ($langData = mysql_fetch_object($langs)) {
    $counter++;
    ?>
                                        firstValue = $("#tr_" + brojNextString + " td:nth-child(<?= $counter; ?>) input").attr("name");

                                        firstValue = firstValue.replace(brojNextString, brojString);
                                        $("#tr_" + brojNextString + " td:nth-child(<?= $counter; ?>) input").attr("name", firstValue);
    <?php
}
?>
                                    alert("eraseThisLine(" + brojString + ");");
                                    $("#tr_" + brojNextString + " td:nth-child(5) a").attr("onclick", "eraseThisLine(" + brojString + ");");
                                    $("#tr_" + brojNextString).attr("id", "tr_" + brojString);

                                }

                                $("#counter").attr("rel", newMaxString);
                            }
                        </script>
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