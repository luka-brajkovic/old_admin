<?php
require("../library/config.php");
$f->checkLogedAdmin();
$module_name = "index";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />		
        <title><?= ADMIN_TITLE; ?></title>
        <?php include("head.php"); ?>
    </head>
    <body>
        <!-- Container -->
        <div id="container">

            <?php include("header.php"); ?>

            <!-- Background wrapper -->
            <div id="bgwrap">

                <!-- Main Content -->
                <div id="content">
                    <div id="main">
                        <h1>Slanje newslettera</h1>

                        <?php
                        $newsletterSpecEmail = $_POST["specemail"];
                        $okinut = $_POST["okinut"];
                        $porukaGet = $_POST["poruka"];
                        $pocetniLimit = $_POST["pocetniLimit"];
                        $krajnjiLimit = $_POST["krajnjiLimit"];
                        if ($porukaGet != "") {
                            $addQ = "AND resource_id = '$porukaGet'";
                        }
                        $newsletterDatas = new Collection("_content_newsletter_slanje");
                        $newsletterDatasArr = $newsletterDatas->getCollection("WHERE status = 1 AND lang = $currentLanguage $addQ");
                        $newsletterData = $newsletterDatasArr[0];

                        $link2 = "";
                        if ($newsletterData->link2 != "") {
                            $link2 = '<a style="color: #FFF;display: inline-block;border-radius: 4px;font-size: 1em;width: auto;text-align: center;padding: 10px 15px;text-decoration: none;margin-right:15px;margin-bottom:15px;font-size: 13px;background:#E5A812;" href="' . $newsletterData->link2 . '" title="' . $newsletterData->link2_text . '" target="_blank">' . $newsletterData->link2_text . '</a>';
                        }
                        $newsletterDataSend = '<div style="text-align:justify;">' . $newsletterData->text . '</div>';
                        if ($newsletterData->link != "") {
                            $newsletterDataSend .= '<br /><div style="text-align: center;"><a style="color: #FFF;display: inline-block;border-radius: 4px;font-size: 1em;width: auto;text-align: center;padding: 10px 15px;text-decoration: none;margin-right:15px;margin-bottom:15px;font-size: 13px;background:#E62E04;" href="' . $newsletterData->link . '" title="' . $newsletterData->link_text . '" target="_blank">' . $newsletterData->link_text . '</a>' . $link2 . '</div>';
                        }

                        $newsletterDataSend = str_replace("../../", $csDomain, str_replace('<table ', '<table cellpadding="0" cellspacing="0" ', $newsletterDataSend));

                        include_once 'newsletter-template.php';

                        $poslatoSpec = "";
                        $poslatoSvima = "";
                        $poslatoNa = "";
                        
                        if ($okinut != "") {
                            if ($newsletterSpecEmail != "") {
                                                                
                                $f->sendMail($csEmail, $csName, $newsletterSpecEmail, "", $newsletterData->title, $body, $currentLanguage);
                                
                                $poslatoNa = $newsletterSpecEmail;
                                $poslatoSpec = "Da";
                            } elseif ($newsletterSpecEmail == "" && $krajnjiLimit != "" && $pocetniLimit != "") {
                                $newsletterSendic = new Collection("_content_newsletter");
                                $newsletterSendicArr = $newsletterSendic->getCollection("WHERE status = 1 AND title !='' LIMIT $pocetniLimit,$krajnjiLimit");
                                foreach ($newsletterSendicArr as $newslet) {
                                    $f->sendMail($csEmail, $csName, $newslet->title, $newsletterData->title, str_replace("promeniumd5", md5($newslet->title), $bodyUser, $currentLanguage));
                                    $poslatoNa .= $newslet->title . ", ";
                                    $poslatoSvima = "Da";
                                }
                                $nemaAktivnih = "Da";
                            }
                        }
                        ?>
                        <div class="newlettercic">
                            <div id="popUp">
                                <div class="popUpInner newslet forms clear">
                                    <form action="" method="post" style="width: 30%;">
                                        <div style="margin-bottom: 20px;">
                                            <select name="poruka" style="margin-bottom: 20px;width: 100%;padding: 7px 4%;color: #666;border: 1px solid #7A7A7A;font: 400 1.5em/1.5em Arial, Helvetica;border-radius: 4px;background: transparent none repeat scroll 0px 0px;">
                                                <?php
                                                $poruke = mysqli_query($conn,"SELECT resource_id, title FROM _content_newsletter_slanje WHERE status = 1 ORDER BY ordering desc");
                                                while ($porukaId = mysqli_fetch_object($poruke)) {
                                                    ?>
                                                    <option value="<?= $porukaId->resource_id; ?>"><?= $porukaId->title; ?></option>
                                                <?php } ?>
                                            </select>
                                            <input type="number" value="" name="pocetniLimit" min="0" placeholder="Početni email" />
                                            <input type="number" value="299" name="krajnjiLimit" min="0" placeholder="Na koliko se šalje" />
                                        </div>
                                        <?php /* <textarea placeholder="Newsletter" name="newsletter" cols="40" rows="10">Naslov: <?= $newsletterData->title; ?>&#10;&#10;Poruka: <?= $newsletterData->text; ?>&#10;&#10;Link: <?= $newsletterData->link.' - '.$newsletterData->link_text; ?>&#10;&#10;Link2: <?= $newsletterData->link2.' - '.$newsletterData->link2_text; ?> 
                                          </textarea> */ ?>
                                        <div style="margin-bottom: 20px;">
                                            <input type="text" name="specemail" placeholder="Specijalni email" value="" />
                                        </div>
                                        <div style="margin-bottom: 20px;">
                                            <input style="cursor:pointer;" class="more button" type="submit" value="Pošalji" name="okinut" />
                                        </div>
                                    </form>
                                    <?php
                                    if ($okinut != "") {
                                        ?>
                                        <div>
                                            <p id="<?php
                                            if ($poslatoSvima != '' || $poslatoSpec != '') {
                                                echo "send";
                                            } elseif ($poslatoSvima == "" && $poslatoSpec == "") {
                                                echo "nosend";
                                            }
                                            ?>"><?php
                                                   if ($poslatoSvima != '') {
                                                       echo $poslatoNa;
                                                       echo "<br>POSLATO SVIMA";
                                                   } elseif ($poslatoSpec != '') {
                                                       echo "POSLATO NA SPECIJALNI - " . $poslatoNa;
                                                   } elseif ($poslatoSvima == "" && $poslatoSpec == "" && $nemaAktivnih == "") {
                                                       echo "GREŠKA";
                                                   } elseif ($poslatoSvima == "" && $poslatoSpec == "" && $nemaAktivnih != "") {
                                                       echo "NEMA AKTIVNIH MAILOVA POD OVIM KRITERIJUMIMA";
                                                   }
                                                   ?></p>
                                        </div>
<?php } ?>
                                </div>
                            </div><?php ?>

                            <hr />

                        </div>
                    </div>
                    <!-- End of Main Content -->


<?php include("sidebar.php"); ?>


                </div>
                <!-- End of bgwrap -->

            </div>
            <!-- End of Container -->

<?php include("footer.php"); ?>

        </div>
    </body>
</html>