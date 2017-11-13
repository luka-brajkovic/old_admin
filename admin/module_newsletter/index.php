<?php
require("../library/config.php");
$f->checkLogedAdmin("module");
$infomsg = $f->getValue("infomsg");
$cid = $f->getValue("cid");

$module_name = "content";
$tablePrint = new Tableprint();

$contentTypeContent = new View("content_types", $cid);
$langCollection = new Collection("languages");
$languages = $langCollection->getCollection();

if ($_POST["newsrids"] != '') {
    $rewrite = new View("newsletter_template", 1);


    $rids = '';

    foreach ($_POST['newsrids'] as $value) {
        $rids .= $value . ", ";
    }
    $rewrite->subject = $f->getValue("naslov");
    $rewrite->body = $rids;
    $rewrite->Save();

    $body = file_get_contents("../../emails/newsletterchina.html");

    $telo = "";
    $counter = 0;
    $ponudeArr = explode(",", $rewrite->subject);
    foreach ($ponudeArr as $ponuda_id) {
        $usluga = new View("_content_usluge", $ponuda_id, "resource_id");
        if ($ponuda_id != '') {
            if ($counter % 2 != 0) {
                $telo = "<tr>";
                $telo .= '<td style="padding: 10px 5px 0px 0px; text-align:left; font-size:14px; color:#000000;">';

                $telo .= '<a href="/ponude/' . $csDomain . $usluga->url . '/' . $usluga->resource_id . '" title="' . $usluga->title . '" border="0" style="color: #FFFFFF">';
                $telo .= '<img src="' . $csDomainuploads . 'uploads/uploaded_pictures/_content_usluge/238x141/' . $usluga->slika_1 . '"/>';
                $telo .= '</a>';

                $telo .= '<a href="/ponude/' . $csDomain . $usluga->url . '/' . $usluga->resource_id . '" title="' . $usluga->title . '" border="0" style="color: #000000">';
                $telo .= $usluga->title;
                $telo .= '</a>';
                $telo .= '<table cellpadding="0" cellspacing="0" style="width: 100%; font-family:Arial, sans-serif; text-align: left" align=center>';


                $telo .= '<tr>';

                $telo .= '<td style="padding: 10px 5px 0px 0px; text-align:right; font-size:14px; color:#000000;">';
                $telo .= '<p style="font-size:11px; text-decoration:line-through; color:#cecccc; margin:0; padding:0; display:block">' . number_format($usluga->stara_cena, 2, ",", " ") . ' din</p>';
                $telo .= '<p style="font-size:14px;  color:#000000; font-weight:bold; margin:0; padding:0; display:block">' . number_format($usluga->nova_cena, 2, ",", " ") . ' din</p>';
                $telo .= '</td>';

                $telo .= '<td style="padding: 10px 5px 0px 0px; text-align:right; font-size:14px; color:#000000;>';
                $telo .= '<a href="/ponude/' . $csDomain . $usluga->url . '/' . $usluga->resource_id . '" title="' . $usluga->title . '" border="0" style="color: #FFFFFF; padding:7px 10px; background:#20b7b8;">';
                $telo .= 'Detaljnije';
                $telo .= '</a>';
                $telo .= '</td>';

                $telo.= '</tr>';

                $telo .= "</td>";
            } else {
                $telo .= '<td style="padding: 10px 0px 0px 5px; text-align:right; font-size:14px; color:#FFF;">';

                $telo .= '<a href="/ponude/' . $csDomain . $usluga->url . '/' . $usluga->resource_id . '" title="' . $usluga->title . '" border="0" style="color: #FFFFFF">';
                $telo .= '<img src="' . $csDomainuploads . 'uploads/uploaded_pictures/_content_usluge/238x141/' . $usluga->slika_1 . '"/>';
                $telo .= '</a>';

                $telo .= '<a href="/ponude/' . $csDomain . $usluga->url . '/' . $usluga->resource_id . '" title="' . $usluga->title . '" border="0" style="color: #000000">';
                $telo .= $usluga->title;
                $telo .= '</a>';
                $telo .= '<table cellpadding="0" cellspacing="0" style="width: 100%; font-family:Arial, sans-serif; text-align: left" align=center>';


                $telo .= '<tr>';

                $telo .= '<td style="padding: 10px 5px 0px 0px; text-align:right; font-size:14px; color:#000000;">';
                $telo .= '<p style="font-size:11px; text-decoration:line-through; color:#cecccc; margin:0; padding:0; display:block">' . number_format($usluga->stara_cena, 2, ",", " ") . ' din</p>';
                $telo .= '<p style="font-size:14px;  color:#000000; font-weight:bold; margin:0; padding:0; display:block">' . number_format($usluga->nova_cena, 2, ",", " ") . ' din</p>';
                $telo .= '</td>';

                $telo .= '<td style="padding: 10px 5px 0px 0px; text-align:right; font-size:14px; color:#000000;>';
                $telo .= '<a href="/ponude/' . $csDomain . $usluga->url . '/' . $usluga->resource_id . '" title="' . $usluga->title . '" border="0" style="color: #FFFFFF; padding:7px 10px; background:#20b7b8;">';
                $telo .= 'Detaljnije';
                $telo .= '</a>';

                $telo .= '</td>';
                $telo .= '</tr>';
            }
        }
    }

    $body = str_replace("{BODY}", $telo, $body);

    file_put_contents("../../emails/newsletterchina.html", $body);
}

$newsTemplate = new View("newsletter_template", 1);
$newsRids = $newsTemplate->body;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />		
        <title><?= ADMIN_TITLE; ?></title>
        <?php include("../head.php"); ?>
        <style type="text/css">

            table.fullwidth tbody tr:hover {background:#e2e2e2;}

        </style>
        <script type="text/javascript">

            function checkThis(item) {

                var state = $("#item_" + item).attr('style');

                if (state == 'background:#d6d8d8') {
                    $("#item_" + item).attr("style", "background:#57c5c7");
                    $("#item_" + item + " td input").attr("checked", "checked");
                } else {
                    $("#item_" + item).attr("style", "background:#d6d8d8");
                    $("#item_" + item + " td input").prop("checked", false);
                }


            }

        </script>
    </head>
    <body>
        <!-- Container -->
        <div id="container">

            <?php include("../header.php"); ?>

            <!-- Background wrapper -->
            <div id="bgwrap">

                <!-- Main Content -->
                <div id="content">

                    <form method="POST" action="index.php">	

                        <br clear="all">
                            <p>
                                <label for="naslov">NASLOV NEWSLETTERA</label>
                                <input type="text" name="naslov" value="<?= $newsTemplate->subject; ?>" id="naslov" style="width: 40%;" />
                            </p>
                            <p>
                                <label for="rids">ID BROJEVI PONUDA (read-only)</label>
                                <input readonly="readonly" type="text" name="rids" style="width: 40%;" value="<?= $newsRids; ?>" id="rids" />
                            </p>
                            <p style="float:left;">
                                <input type="submit" value="Napravi newsletter"  style="width: 300px; background:#454545; color:#FFF;" />
                            </p>
                            <p style="float:right;">
                                <input style="width:300px" name="search_input" id="search_input" class="sf right_input" onblur="if (this.value == '')
                                            this.value = this.defaultValue;" onfocus="if (this.value == this.defaultValue)
                                                        this.value = '';" value="Live search..." type="text">
                            </p>
                            <div class="clear"></div>
                            <?php $tablePrint->printNewsletterTable(); ?>


                    </form>
                </div>
                <!-- End of Main Content -->





            </div>
            <!-- End of bgwrap -->

        </div>
        <!-- End of Container -->

        <?php include("../footer.php"); ?>

    </body>
</html>