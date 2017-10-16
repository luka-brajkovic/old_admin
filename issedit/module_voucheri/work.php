<?php
require("../library/config.php");
$resizeImage = new ResizeImage();
//Here we have all values from POST and GET
$values = $f->getRequestValues();
$action = $values["action"];

switch ($action) {

    case 'odobri_voucher':

        $rid = $f->getValue("rid");

        echo $rid;


        $voucher = new View("_content_voucheri", $rid, "resource_id");
        $voucher->status = 1;
        $voucher->odobreno = date("Y-m-d H:i:s");
        $voucher->Save();

        $uplatnica = new View("_content_uplatnice", $voucher->id_uplatnice, "resource_id");
        if ($uplatnica->kolicina > 1) {
            $uplatnica->kolicina -= 1;
            $uplatnica->cena -= $voucher->vrednost;
        } else {
            $uplatnica->status = 1;
        }
        $uplatnica->Save();

        $uslugaData = new View("_content_usluge", $voucher->id_ponude, "resource_id");
        $uslugaData->stvarno_kupljeno += 1;

        $uslugaData->Save();


        $korisnikData = new View("users", $voucher->korisnik);




        $header_1 = "#<p><strong>" . $voucher->broj_kupona . "</strong><br>ID: $voucher->resource_id</p>";
        $header_2 = "<h2>$uslugaData->title</h2>";

        $content_1 = $korisnikData->ime . " " . $korisnikData->prezime;
        $content_2 = $voucher->korisnik_vouchera;
        $content_3 = $f->makeFancyDate($uslugaData->iskoristiti_do);

        $diler = new View("_content_dileri", $uslugaData->diler, "resource_id");

        $firma = "<h1>$diler->title</h1><p><strong>$diler->adresa</strong><br>$diler->telefon</p>";

        $napomena_polje = str_replace("<li>", "<li style='list-style:none; padding:5px 0; margin:0; font-size:11px;'>", $uslugaData->napomene);


        /* UZEO TELO VAUCERA */
        $body = file_get_contents("../../emails/voucher.html");
        $body = str_replace("{BROJ VAUCERA}", $header_1, $body);
        $body = str_replace("{USLUGA}", $header_2, $body);
        $body = str_replace("{NOSILAC}", $content_1, $body);
        $body = str_replace("{KORISNIK}", $content_2, $body);
        $body = str_replace("{TRAJE DO}", $content_3, $body);

        $body = str_replace("{FIRMA}", $firma, $body);
        $body = str_replace("{NAPOMENE}", $napomena_polje, $body);



        require("../../library/phpmailer/class.phpmailer.php");

        $mail = new PHPMailer();
        $mail->From = "office@voucher.rs";
        $mail->FromName = "Voucher.rs | popusti, ponude, proizvodi, sve na jednom mestu";
        $mail->AddAddress($voucher->salje_se_na);
        $mail->Subject = "Vaučer | $uslugaData->title";
        $mail->Body = $body;
        $mail->Send();

        $_SESSION["poslat_vaucer"] = true;

        $f->redirect("/issedit/module_voucheri/index.php");

        break;

    case 'search_phrase':

        $phrase = $_POST["phrase"];

        $vouchersCollection = new Collection("_content_voucheri");
        $vouchersArr = $vouchersCollection->getCollectionCustom("SELECT DISTINCT cv.* FROM _content_voucheri cv JOIN users u ON  cv.korisnik = u.id 
			WHERE cv.resource_id LIKE '%$phrase%' OR cv.title LIKE '%$phrase%' OR cv.id_ponude LIKE '%$phrase%' OR cv.id_uplatnice LIKE '%$phrase%' OR cv.broj_kupona LIKE '%$phrase%' OR cv.korisnik_vouchera LIKE '%$phrase%' 
			OR  u.ime LIKE '%$phrase%' OR u.prezime LIKE '%$phrase%' OR u.email LIKE '%$phrase%' ORDER BY system_date DESC");



        foreach ($vouchersArr as $voucher) {
            $korisnik = new View("users", $voucher->korisnik);
            ?>
            <tr>
                <td>
                    <?= $voucher->resource_id; ?>	
                </td>
                <td>
                    <?= $voucher->title; ?>	
                </td>
                <td>
                    <?= $voucher->id_ponude; ?>	
                </td>
                <td>
                    <?= $voucher->vrednost; ?>	
                </td>
                <td>
                    <?= $f->makeFancyDate($voucher->system_date); ?>	
                </td>
                <td>
                    <?= $voucher->id_uplatnice; ?>	
                </td>
                <td>
                    <?= $voucher->broj_kupona; ?>	
                </td>
                <td>
                    <?php
                    if ($voucher->placeno_putem == 1) {
                        echo "Uplatnica";
                    }
                    ?>	
                </td>
                <td>
                    <?= $korisnik->ime . " " . $korisnik->prezime; ?>	
                </td>
                <td>
                    <?php
                    if ($voucher->status == 2) {
                        echo "NIJE";
                    }
                    ?>
                    <?php
                    if ($voucher->status == 1) {
                        echo $f->makeFancyDate($voucher->odobreno);
                    }
                    ?>	
            <?php
            if ($voucher->status == 3) {
                echo "STORNIRAN<br>" . $f->makeFancyDate($voucher->odobreno);
            }
            ?>		
                </td>
                <td>
                    <?= $voucher->email; ?>		
                </td>
                <td>
            <?= $voucher->salje_se_na; ?>		
                </td>
                <td>
            <?php
            if ($voucher->status == 1) {
                ?>
                        <a href="work.php?action=storniraj_voucher&rid=<?= $voucher->resource_id; ?>" onclick="javascript:return confirm('Da li ste sigurni?');">Storniraj kupon</a>
                        <?

                        }
                        if($voucher->status==2){
                        ?>
                        <a href="work.php?action=odobri_voucher&rid=<?= $voucher->resource_id; ?>" onclick="javascript:return confirm('Da li ste sigurni?');">
                            <img src='correct.png' />
                        </a>
                        <?
                        }
                        if($voucher->status==3){
                        echo "Voučer je storniran";
                        }
                        ?>		
                    </td>
                </tr>
                <?php
            }

            break;

            case 'search_everything':
            $status = $_POST["status"];

            $vouchersCollection = new Collection("_content_voucheri");
            $vouchersArr = $vouchersCollection->getCollection("WHERE status = $status ORDER BY system_date DESC");
            foreach ($vouchersArr as $voucher) {
                $korisnik = new View("users", $voucher->korisnik);
                ?>
                <tr>
                    <td>
                        <?= $voucher->resource_id; ?>	
                    </td>
                    <td>
                        <?= $voucher->title; ?>	
                    </td>
                    <td>
                        <?= $voucher->id_ponude; ?>	
                    </td>
                    <td>
                        <?= $voucher->vrednost; ?>	
                    </td>
                    <td>
                        <?= $f->makeFancyDate($voucher->system_date); ?>	
                    </td>
                    <td>
                        <?= $voucher->id_uplatnice; ?>	
                    </td>
                    <td>
                        <?= $voucher->broj_kupona; ?>	
                    </td>
                    <td>
                        <?php
                        if ($voucher->placeno_putem == 1) {
                            echo "Uplatnica";
                        }
                        ?>	
                    </td>
                    <td>
                        <?= $korisnik->ime . " " . $korisnik->prezime; ?>	
                    </td>
                    <td>
                        <?php
                        if ($voucher->status == 2) {
                            echo "NIJE";
                        }
                        ?>
                <?php
                if ($voucher->status == 1) {
                    echo $f->makeFancyDate($voucher->odobreno);
                }
                ?>	
                <?php
                if ($voucher->status == 3) {
                    echo "STORNIRAN<br>" . $f->makeFancyDate($voucher->odobreno);
                }
                ?>		
                    </td>
                    <td>
                <?= $voucher->email; ?>		
                    </td>
                    <td>
                <?= $voucher->salje_se_na; ?>		
                    </td>
                    <td>
                <?php
                if ($voucher->status == 1) {
                    ?>
                            <a href="work.php?action=storniraj_voucher?rid=<?= $voucher->resource_id; ?>" onclick="javascript:return confirm('Da li ste sigurni?');">Storniraj kupon</a>
                            <?

                            }
                            if($voucher->status==2){
                            ?>
                            <a href="work.php?action=odobri_voucher?rid=<?= $voucher->resource_id; ?>" onclick="javascript:return confirm('Da li ste sigurni?');">
                                <img src='correct.png' />
                            </a>
                            <?
                            }
                            if($voucher->status==3){
                            echo "Voučer je storniran";
                            }
                            ?>		
                        </td>
                    </tr>
                    <?php
                }
                break;
            }
        ?>