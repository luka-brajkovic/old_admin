<?php
/* error_reporting(E_ALL);
  ini_set('display_errors', E_ALL); */
libxml_use_internal_errors(TRUE);
require_once("../library/config.php");
set_time_limit(-1);
ini_set('memory_limit', -1);

$external_xml = "http://www.ewe.rs/share/backend_231/?user=skycomp&secretcode=322e8&attributes=1";

$url = $external_xml;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$xmlresponse = curl_exec($ch);
$xml = simplexml_load_string($xmlresponse);

$uso = 0;
?>
<!DOCTYPE html>
<html lang="sr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    </head>
    <?php
    $osnovne_karakteristikeCheck = array("Osnovne karakteristike", "Ostale karakteristike", "Garancija", "Fizičke karakteristike", "Dodatne funkcije", "Priključci / Slotovi", "Audio", "Video", "Ekran", "Priključci sa strane", "Karakteristike", "Rezolucija", "Ostalo", "Povezivost", "Bežična mreža", "Zaštita", "Zaštita", "Žična mreža", "Antene", "Matična ploča", "MAC adrese", "Procesor", "Memorija (RAM)", "Grafika", "Skladištenje podataka", "Mreža", "Softver", "Napajanje", "Slotovi", "Priključci", "Periferni uređaji", "Osnovno", "Memorija", "Skladišni interfejs", "Priključci na zadnjem panelu", "Unutrašnji priključci", "Procesor / Čipset", "Kućište / Napajanje", "Baterija", "Čipset", "Priključci pozadi", "Brzina čitanja", "Radijator", "Ventilatori", "Brzina upisivanja", "Pumpa", "Snimanje fotografija", "Snimanje videa", "Displej", "Ležišta za spoljne uređaje", "Unutrašnja ležišta", "Priključci napred", "Mobilna mreža", "Prednja kamera", "Kamera", "Tastatura", "Karakterisitke ploče za kuvanje", "Karakterisitke rerne", "Dodatna grafika", "Mikrofon", "Štampa", "Ležišta za ventilatore", "Kompatibilnost", "Potrošni materijal", "Senzor", "Ugrađeni ventilatori", "Skeniranje", "+12V naponske grane", "Hlađenje", "Kopiranje", "Faks", "Zvučnici", "Fiksni priključci", "Optički uređaj", "Ostale naponske grane", "Lampa", "Modularni priključci", "Grafički procesor", "SATA hard diskovi", "Firewall", "Kontroleri", "Kućište", "Hard diskovi", "Pristup podacima", "Backup", "IP Cam server (video nadzor)", "Ušteda energije", "Multimedijalna podrška", "Print server", "Upravljanje", "Subwoofer", "Sateliti", "Miš", "IPv6", "VLAN", "Sočivo", "Centralni zvučnik", "Prednji sateliti", "Ulazni priključci", "Subwoofer", "Izlazni priključci", "Zadnji sateliti", "Brzina skeniranja", "Slika");

    foreach ($xml->children() as $childs) {

        $uso++;

        $id = $category = $subcategory = $productDesc = "";

        $osnovne_karakteristike = $ostale_karakteristike = $garancijaNiz = $ekran = $video = $audio = $slotovi = $prikljuci_slotovi = $prikljuci = $dodatne = $fizikal = $strana = $karakteristike = $rezolucija = $brzina = $ostalo = $povezano = $bezicna = $antene = $zastitamreza = $zastitna = $mac = $procesor = $memram = $grafika = $skladistenje = $mrezica = $napajanje = $softver = $periferija = $memory = $prikljuci_od_pozadi = $skladisnji_interfejs = $prikljuci_unutra = $proces_cip = $kuciste_napajanje = $baterija = $cipset = $prikljuci_pozadinski = $ventilatori = $radijator = $speedread = $speed_write = $pumpa = $stampa = $rec_video = $displey = $rec_photo = $leziste_spolja = $prikljuci_napred = $kamera = $unutranja_lezeraj = $prednja_kamera = $mobilna_mreza = $kara_ploca_kuvanje = $kara_rerne = $tastatura = $dodatn_grafika = $mikrofonija = $lezi_ventilator = $kompatibilnost = $potrosni_materijal = $senzor = $ugradjen_ventil = $skeniranje = $kopiranje = $fax = $zvucnici = $fix_prikljuci = $naponske_grane = $hladjenje = $ostale_naponske_grane = $modularni_prikljci = $graficki_procesor = $lampa = $firewall = $sata_hardisk = $opticki_uredjaji = $kontroleri = $kuciste = $hard_diskici = $pristup_podatcima = $backup = $multimedija_podrska = $print_server = $upravljanjec = $ip_cam_server = $usteda_energije = $vlan = $ipv = $socivo = $mis = $sateliti = $ulazni_prikljucakic = $subwoofer = $izlazni_prikljucak = $prednji_sateliti = $zadnji_sateliti = $centralni_zvucnik = $slika = array();

        foreach ($childs->children() as $uhvat) {
            if ($uhvat->getName() == "id") {
                $id = $uhvat;
            }
            if ($uhvat->getName() == "category") {
                $category = $uhvat;
            }
            if ($uhvat->getName() == "subcategory") {
                $subcategory = $uhvat;
            }

            if ($uhvat->getName() == "specifications") {

                /*                 * ***************************** PROVERA NOVIH FILTERA ************************************** */
                $filter = 0;
                $filters = "";
                foreach ($uhvat->children() as $groupSpec) {
                    if (!in_array($groupSpec->attributes(), $osnovne_karakteristikeCheck)) {
                        $filter++;
                        $filters .= $groupSpec->attributes() . "\r\n";
                    }
                }
                /*                 * ***************************** KRAJ PROVERE NOVIH FILTERA ************************************** */

                foreach ($uhvat->children() as $groupSpec) {
                    if ($groupSpec->attributes() == "Osnovne karakteristike") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $osnovne) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($osnovne->attributes());
                                $vrednost = htmlspecialchars($osnovne->children());
                                if ($key != '' && $vrednost != '') {
                                    $osnovne_karakteristike["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Ostale karakteristike") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $ostale) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($ostale->attributes());
                                $vrednost = htmlspecialchars($ostale->children());
                                if ($key != '' && $vrednost != '') {
                                    $ostale_karakteristike["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Garancija") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $garancija) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($garancija->attributes());
                                $vrednost = htmlspecialchars($garancija->children());
                                if ($key != '' && $vrednost != '') {
                                    $garancijaNiz["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Ekran") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $ekrn) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($ekrn->attributes());
                                $vrednost = htmlspecialchars($ekrn->children());
                                if ($key != '' && $vrednost != '') {
                                    $ekran["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Video") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $video["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Audio") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $audio["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Priključci / Slotovi") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $prikljuci_slotovi["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Dodatne funkcije") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $dodatne["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Fizičke karakteristike") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $fizikal["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Priključci sa strane") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $strana["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Karakteristike") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $karakteristike["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Rezolucija") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $rezolucija["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Brzina skeniranja") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $brzina["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Ostalo") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $ostalo["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Povezivost") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $povezano["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Bežična mreža") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $bezicna["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Antene") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $antene["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Žična mreža") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $zastitamreza["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Zaštita") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $zastitna["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "MAC adrese") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $mac["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Procesor") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $procesor["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Memorija (RAM)") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $memram["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Grafika") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $grafika["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Skladištenje podataka") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $skladistenje["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Mreža") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $mrezica["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Priključci") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $prikljuci["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Slotovi") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $slotovi["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Napajanje") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $napajanje["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Softver") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $softver["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Periferni uređaji") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $periferija["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Memorija") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $memory["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Skladišni interfejs") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $skladisnji_interfejs["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Priključci na zadnjem panelu") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $prikljuci_od_pozadi["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Unutrašnji priključci") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $prikljuci_unutra["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Procesor / Čipset") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $proces_cip["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Čipset") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $cipset["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Kućište / Napajanje") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $kuciste_napajanje["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Baterija") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $baterija["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Priključci pozadi") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $prikljuci_pozadinski["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Ventilatori") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $ventilatori["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Radijator") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $radijator["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Brzina čitanja") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $speedread["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Brzina upisivanja") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $speed_write["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Pumpa") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $pumpa["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Štampa") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $stampa["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Snimanje fotografija") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $rec_photo["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Snimanje videa") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $rec_video["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Displej") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $displey["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Ležišta za spoljne uređaje") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $leziste_spolja["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Unutrašnja ležišta") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $unutranja_lezeraj["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Priključci napred") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $prikljuci_napred["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Kamera") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $kamera["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Prednja kamera") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $prednja_kamera["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Mobilna mreža") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $mobilna_mreza["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Karakterisitke ploče za kuvanje") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $kara_ploca_kuvanje["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Karakterisitke rerne") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $kara_rerne["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Tastatura") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $tastatura["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Dodatna grafika") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $dodatn_grafika["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Mikrofon") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $mikrofonija["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Ležišta za ventilatore") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $lezi_ventilator["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Kompatibilnost") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $kompatibilnost["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Potrošni materijal") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $potrosni_materijal["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Senzor") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $senzor["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Ugrađeni ventilatori") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $ugradjen_ventil["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Skeniranje") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $skeniranje["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Kopiranje") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $kopiranje["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Faks") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $fax["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Zvučnici") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $zvucnici["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Fiksni priključci") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $fix_prikljuci["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Hlađenje") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $hladjenje["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "+12V naponske grane") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $naponske_grane["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Ostale naponske grane") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $ostale_naponske_grane["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Modularni priključci") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $modularni_prikljci["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Grafički procesor") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $graficki_procesor["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Lampa") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $lampa["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Firewall") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $firewall["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "SATA hard diskovi") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $sata_hardisk["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Optički uređaj") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $opticki_uredjaji["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Kontroleri") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $kontroleri["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Kućište") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $kuciste["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Hard diskovi") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $hard_diskici["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Pristup podacima") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $pristup_podatcima["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Backup") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $backup["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Multimedijalna podrška") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $multimedija_podrska["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Print server") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $print_server["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Upravljanje") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $upravljanjec["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Ušteda energije") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $usteda_energije["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "IP Cam server (video nadzor)") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $ip_cam_server["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "VLAN") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $vlan["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "IPv6") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $ipv["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Sočivo") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $socivo["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Miš") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $mis["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Sateliti") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $sateliti["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Ulazni priključci") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $ulazni_prikljucakic["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Subwoofer") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $subwoofer["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Izlazni priključci") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $izlazni_prikljucak["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Prednji sateliti") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $prednji_sateliti["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Zadnji sateliti") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $zadnji_sateliti["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Centralni zvučnik") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $centralni_zvucnik["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                    if ($groupSpec->attributes() == "Slika") {
                        if ($groupSpec->count() > 0) {
                            foreach ($groupSpec->children() as $vid) {
                                $key = $vrednost = "";
                                $key = htmlspecialchars($vid->attributes());
                                $vrednost = htmlspecialchars($vid->children());
                                if ($key != '' && $vrednost != '') {
                                    $slika["$key"] = "$vrednost";
                                }
                            }
                        }
                    }
                }
            }
        }

        $category = htmlspecialchars($category);
        $subcategory = htmlspecialchars($subcategory);

        if ($category == "Notebook / Tablet") {
            $category = "Laptop i Tablet računari";
            if ($subcategory == "Notebook") {
                $subcategory = "Laptop računari";
            }
            if ($subcategory == "Tableti") {
                $subcategory = "Tablet računari";
            }
        }

        if ($category == "Outlet") {
            if ($subcategory == "Laptopovi") {
                $subcategory = "Laptop računari";
            }
            if ($subcategory == "Tableti") {
                $subcategory = "Tablet računari";
            }
        }
        if ($category == "Skeneri") {
            if ($subcategory == "A4") {
                $subcategory = "A4 Skeneri";
            }
            if ($subcategory == "A3") {
                $subcategory = "A3 Skeneri";
            }
        }

        $MasterCat = mysql_query("SELECT resource_id, title FROM categories WHERE title = '$category' AND parent_id = '0' LIMIT 1");
        $MasterCat = mysql_fetch_object($MasterCat);

        $subCat = mysql_query("SELECT resource_id, title FROM categories WHERE title = '$subcategory' AND parent_id = '$MasterCat->resource_id' LIMIT 1");
        $subCat = mysql_fetch_object($subCat);

        $id = htmlspecialchars($id);
        $inItem = new View("_content_proizvodi", "$id", "product_code");
        $inFilter = mysql_query("SELECT id FROM filter_joins WHERE product_rid = '$inItem->resource_id' LIMIT 1");
        $inFilter = mysql_fetch_object($inFilter);

        if (!empty($inItem->id) && empty($inFilter->id)) {
            if (count($osnovne_karakteristike) > 0) {
                $count = 0;
                foreach ($osnovne_karakteristike as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Osnovne karakteristike</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($ostale_karakteristike) > 0) {
                $count = 0;
                foreach ($ostale_karakteristike as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Ostale karakteristike</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    if ($key == "Poklon" && $value == "Assassin\'s Creed Unity igra") {
                        $value = "Assassin&amp;#39;s Creed Unity igra";
                    }
                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($ekran) > 0) {
                $count = 0;
                foreach ($ekran as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Ekran</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    if ($key == "Ostalo" && $subCat->resource_id == "9106") {
                        
                    } else {
                        $content = new Collection("filter_headers");
                        $headerUrl = "";
                        $headerUrl = $f->generateUrlFromText("$key");
                        $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                        $inHeadersFilter = $inCats[0];
                        if (empty($inHeadersFilter->id)) {
                            $inHeaders = new View("filter_headers");
                            $inHeaders->lang = 1;
                            $inHeaders->title = "$key";
                            $inHeaders->url = "$headerUrl";
                            $inHeaders->cat_resource_id = $subCat->resource_id;
                            $inHeaders->show = 1;
                            $inHeaders->Save();
                        }
                        $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                        $kecHeader = mysql_fetch_object($kecHeader);

                        $contentVal = new Collection("filter_values");
                        $url = "";
                        $url = $f->generateUrlFromText("$value");
                        $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                        $inValueFilter = $inVal[0];
                        if (empty($inValueFilter->id)) {
                            $inValue = new View("filter_values");
                            $inValue->fh_id = $kecHeader->id;
                            $inValue->lang = 1;
                            $inValue->title = "$value";
                            $inValue->url = "$url";
                            $inValue->Save();
                        }
                        $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                        $kecValue = mysql_fetch_object($kecValue);

                        $inJoins = new View("filter_joins");
                        $inJoins->lang = 1;
                        $inJoins->product_rid = $inItem->resource_id;
                        $inJoins->fv_id = $kecValue->id;
                        $inJoins->fh_id = $kecHeader->id;
                        $inJoins->cat_rid = $subCat->resource_id;
                        $inJoins->Save();
                    }
                }
            }

            if (count($video) > 0) {
                $count = 0;
                foreach ($video as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Video</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($audio) > 0) {
                $count = 0;
                foreach ($audio as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Audio</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    if (($key == "Ostalo" && $subCat->resource_id == "9106") || ($key == "Snaga" && $subCat->resource_id == "9106")) {
                        
                    } else {
                        $content = new Collection("filter_headers");
                        $headerUrl = "";
                        $headerUrl = $f->generateUrlFromText("$key");
                        $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                        $inHeadersFilter = $inCats[0];
                        if (empty($inHeadersFilter->id)) {
                            $inHeaders = new View("filter_headers");
                            $inHeaders->lang = 1;
                            $inHeaders->title = "$key";
                            $inHeaders->url = "$headerUrl";
                            $inHeaders->cat_resource_id = $subCat->resource_id;
                            $inHeaders->show = 1;
                            $inHeaders->Save();
                        }
                        $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                        $kecHeader = mysql_fetch_object($kecHeader);

                        $contentVal = new Collection("filter_values");
                        $url = "";
                        $url = $f->generateUrlFromText("$value");
                        $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                        $inValueFilter = $inVal[0];
                        if (empty($inValueFilter->id)) {
                            $inValue = new View("filter_values");
                            $inValue->fh_id = $kecHeader->id;
                            $inValue->lang = 1;
                            $inValue->title = "$value";
                            $inValue->url = "$url";
                            $inValue->Save();
                        }
                        $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                        $kecValue = mysql_fetch_object($kecValue);

                        $inJoins = new View("filter_joins");
                        $inJoins->lang = 1;
                        $inJoins->product_rid = $inItem->resource_id;
                        $inJoins->fv_id = $kecValue->id;
                        $inJoins->fh_id = $kecHeader->id;
                        $inJoins->cat_rid = $subCat->resource_id;
                        $inJoins->Save();
                    }
                }
            }

            if (count($prikljuci_slotovi) > 0) {
                $count = 0;
                foreach ($prikljuci_slotovi as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Priključci / Slotovi</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($dodatne) > 0) {
                $count = 0;
                foreach ($dodatne as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Dodatne funkcije</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($fizikal) > 0) {
                $count = 0;
                foreach ($fizikal as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Fizičke karakteristike</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    if (($key == "Dimenzije" && $subCat->resource_id == "9106") || ($key == "Masa" && $subCat->resource_id == "9106")) {
                        
                    } else {
                        $content = new Collection("filter_headers");
                        $headerUrl = "";
                        $headerUrl = $f->generateUrlFromText("$key");
                        $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                        $inHeadersFilter = $inCats[0];
                        if (empty($inHeadersFilter->id)) {
                            $inHeaders = new View("filter_headers");
                            $inHeaders->lang = 1;
                            $inHeaders->title = "$key";
                            $inHeaders->url = "$headerUrl";
                            $inHeaders->cat_resource_id = $subCat->resource_id;
                            $inHeaders->show = 1;
                            $inHeaders->Save();
                        }
                        $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                        $kecHeader = mysql_fetch_object($kecHeader);

                        $contentVal = new Collection("filter_values");
                        $url = "";
                        $url = $f->generateUrlFromText("$value");
                        $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                        $inValueFilter = $inVal[0];
                        if (empty($inValueFilter->id)) {
                            $inValue = new View("filter_values");
                            $inValue->fh_id = $kecHeader->id;
                            $inValue->lang = 1;
                            $inValue->title = "$value";
                            $inValue->url = "$url";
                            $inValue->Save();
                        }
                        $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                        $kecValue = mysql_fetch_object($kecValue);

                        $inJoins = new View("filter_joins");
                        $inJoins->lang = 1;
                        $inJoins->product_rid = $inItem->resource_id;
                        $inJoins->fv_id = $kecValue->id;
                        $inJoins->fh_id = $kecHeader->id;
                        $inJoins->cat_rid = $subCat->resource_id;
                        $inJoins->Save();
                    }
                }
            }

            if (count($strana) > 0) {
                $count = 0;
                foreach ($strana as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Priključci sa strane</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($karakteristike) > 0) {
                $count = 0;
                foreach ($karakteristike as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Karakteristike</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($rezolucija) > 0) {
                $count = 0;
                foreach ($rezolucija as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Rezolucija</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($brzina) > 0) {
                $count = 0;
                foreach ($brzina as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Brzina skeniranja</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($ostalo) > 0) {
                $count = 0;
                foreach ($ostalo as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Ostalo</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($povezano) > 0) {
                $count = 0;
                foreach ($povezano as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Povezivost</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($bezicna) > 0) {
                $count = 0;
                foreach ($bezicna as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Bežična mreža</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($antene) > 0) {
                $count = 0;
                foreach ($antene as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Antene</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($zastitna) > 0) {
                $count = 0;
                foreach ($zastitna as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Zaštita</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($zastitamreza) > 0) {
                $count = 0;
                foreach ($zastitamreza as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Žična mreža</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($mac) > 0) {
                $count = 0;
                foreach ($mac as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>MAC adrese</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($procesor) > 0) {
                $count = 0;
                foreach ($procesor as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Procesor</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($memram) > 0) {
                $count = 0;
                foreach ($memram as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Memorija (RAM)</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    if ($key == "Ostalo" && $subCat->resource_id == "9106") {
                        
                    } else {
                        $content = new Collection("filter_headers");
                        $headerUrl = "";
                        $headerUrl = $f->generateUrlFromText("$key");
                        $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                        $inHeadersFilter = $inCats[0];
                        if (empty($inHeadersFilter->id)) {
                            $inHeaders = new View("filter_headers");
                            $inHeaders->lang = 1;
                            $inHeaders->title = "$key";
                            $inHeaders->url = "$headerUrl";
                            $inHeaders->cat_resource_id = $subCat->resource_id;
                            $inHeaders->show = 1;
                            $inHeaders->Save();
                        }
                        $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                        $kecHeader = mysql_fetch_object($kecHeader);

                        $contentVal = new Collection("filter_values");
                        $url = "";
                        $url = $f->generateUrlFromText("$value");
                        $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                        $inValueFilter = $inVal[0];
                        if (empty($inValueFilter->id)) {
                            $inValue = new View("filter_values");
                            $inValue->fh_id = $kecHeader->id;
                            $inValue->lang = 1;
                            $inValue->title = "$value";
                            $inValue->url = "$url";
                            $inValue->Save();
                        }
                        $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                        $kecValue = mysql_fetch_object($kecValue);

                        $inJoins = new View("filter_joins");
                        $inJoins->lang = 1;
                        $inJoins->product_rid = $inItem->resource_id;
                        $inJoins->fv_id = $kecValue->id;
                        $inJoins->fh_id = $kecHeader->id;
                        $inJoins->cat_rid = $subCat->resource_id;
                        $inJoins->Save();
                    }
                }
            }

            if (count($grafika) > 0) {
                $count = 0;
                foreach ($grafika as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Grafika</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($skladistenje) > 0) {
                $count = 0;
                foreach ($skladistenje as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Skladištenje podataka</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($mrezica) > 0) {
                $count = 0;
                foreach ($mrezica as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Mreža</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    if ($key == "Wi-Fi" && $subCat->resource_id == "9106") {
                        
                    } else {
                        $content = new Collection("filter_headers");
                        $headerUrl = "";
                        $headerUrl = $f->generateUrlFromText("$key");
                        $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                        $inHeadersFilter = $inCats[0];
                        if (empty($inHeadersFilter->id)) {
                            $inHeaders = new View("filter_headers");
                            $inHeaders->lang = 1;
                            $inHeaders->title = "$key";
                            $inHeaders->url = "$headerUrl";
                            $inHeaders->cat_resource_id = $subCat->resource_id;
                            $inHeaders->show = 1;
                            $inHeaders->Save();
                        }
                        $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                        $kecHeader = mysql_fetch_object($kecHeader);

                        $contentVal = new Collection("filter_values");
                        $url = "";
                        $url = $f->generateUrlFromText("$value");
                        $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                        $inValueFilter = $inVal[0];
                        if (empty($inValueFilter->id)) {
                            $inValue = new View("filter_values");
                            $inValue->fh_id = $kecHeader->id;
                            $inValue->lang = 1;
                            $inValue->title = "$value";
                            $inValue->url = "$url";
                            $inValue->Save();
                        }
                        $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                        $kecValue = mysql_fetch_object($kecValue);

                        $inJoins = new View("filter_joins");
                        $inJoins->lang = 1;
                        $inJoins->product_rid = $inItem->resource_id;
                        $inJoins->fv_id = $kecValue->id;
                        $inJoins->fh_id = $kecHeader->id;
                        $inJoins->cat_rid = $subCat->resource_id;
                        $inJoins->Save();
                    }
                }
            }

            if (count($prikljuci) > 0) {
                $count = 0;
                foreach ($prikljuci as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Priključci</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($slotovi) > 0) {
                $count = 0;
                foreach ($slotovi as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Slotovi</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($napajanje) > 0) {
                $count = 0;
                foreach ($napajanje as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Napajanje</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($softver) > 0) {
                $count = 0;
                foreach ($softver as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Softver</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    if ($key == "Programi / Ekskluzivne aplikacije / Servisi" && $subCat->resource_id == "9106") {
                        
                    } else {
                        $content = new Collection("filter_headers");
                        $headerUrl = "";
                        $headerUrl = $f->generateUrlFromText("$key");
                        $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                        $inHeadersFilter = $inCats[0];
                        if (empty($inHeadersFilter->id)) {
                            $inHeaders = new View("filter_headers");
                            $inHeaders->lang = 1;
                            $inHeaders->title = "$key";
                            $inHeaders->url = "$headerUrl";
                            $inHeaders->cat_resource_id = $subCat->resource_id;
                            $inHeaders->show = 1;
                            $inHeaders->Save();
                        }
                        $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                        $kecHeader = mysql_fetch_object($kecHeader);

                        $contentVal = new Collection("filter_values");
                        $url = "";
                        $url = $f->generateUrlFromText("$value");
                        $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                        $inValueFilter = $inVal[0];
                        if (empty($inValueFilter->id)) {
                            $inValue = new View("filter_values");
                            $inValue->fh_id = $kecHeader->id;
                            $inValue->lang = 1;
                            $inValue->title = "$value";
                            $inValue->url = "$url";
                            $inValue->Save();
                        }
                        $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                        $kecValue = mysql_fetch_object($kecValue);

                        $inJoins = new View("filter_joins");
                        $inJoins->lang = 1;
                        $inJoins->product_rid = $inItem->resource_id;
                        $inJoins->fv_id = $kecValue->id;
                        $inJoins->fh_id = $kecHeader->id;
                        $inJoins->cat_rid = $subCat->resource_id;
                        $inJoins->Save();
                    }
                }
            }

            if (count($periferija) > 0) {
                $count = 0;
                foreach ($periferija as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Periferni uređaji</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($memory) > 0) {
                $count = 0;
                foreach ($memory as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Memorija</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($skladisnji_interfejs) > 0) {
                $count = 0;
                foreach ($skladisnji_interfejs as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Skladišni interfejs</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($prikljuci_od_pozadi) > 0) {
                $count = 0;
                foreach ($prikljuci_od_pozadi as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Priključci na zadnjem panelu</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($prikljuci_unutra) > 0) {
                $count = 0;
                foreach ($prikljuci_unutra as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Unutrašnji priključci</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($proces_cip) > 0) {
                $count = 0;
                foreach ($proces_cip as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Procesor / Čipset</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    if ($key == "Ostalo" && $subCat->resource_id == "9106") {
                        
                    } else {
                        $content = new Collection("filter_headers");
                        $headerUrl = "";
                        $headerUrl = $f->generateUrlFromText("$key");
                        $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                        $inHeadersFilter = $inCats[0];
                        if (empty($inHeadersFilter->id)) {
                            $inHeaders = new View("filter_headers");
                            $inHeaders->lang = 1;
                            $inHeaders->title = "$key";
                            $inHeaders->url = "$headerUrl";
                            $inHeaders->cat_resource_id = $subCat->resource_id;
                            $inHeaders->show = 1;
                            $inHeaders->Save();
                        }
                        $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                        $kecHeader = mysql_fetch_object($kecHeader);

                        $contentVal = new Collection("filter_values");
                        $url = "";
                        $url = $f->generateUrlFromText("$value");
                        $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                        $inValueFilter = $inVal[0];
                        if (empty($inValueFilter->id)) {
                            $inValue = new View("filter_values");
                            $inValue->fh_id = $kecHeader->id;
                            $inValue->lang = 1;
                            $inValue->title = "$value";
                            $inValue->url = "$url";
                            $inValue->Save();
                        }
                        $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                        $kecValue = mysql_fetch_object($kecValue);

                        $inJoins = new View("filter_joins");
                        $inJoins->lang = 1;
                        $inJoins->product_rid = $inItem->resource_id;
                        $inJoins->fv_id = $kecValue->id;
                        $inJoins->fh_id = $kecHeader->id;
                        $inJoins->cat_rid = $subCat->resource_id;
                        $inJoins->Save();
                    }
                }
            }

            if (count($kuciste_napajanje) > 0) {
                $count = 0;
                foreach ($kuciste_napajanje as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Kućište / Napajanje</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($baterija) > 0) {
                $count = 0;
                foreach ($baterija as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Baterija</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($cipset) > 0) {
                $count = 0;
                foreach ($cipset as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Čipset</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($prikljuci_pozadinski) > 0) {
                $count = 0;
                foreach ($prikljuci_pozadinski as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Priključci pozadi</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($ventilatori) > 0) {
                $count = 0;
                foreach ($ventilatori as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Ventilatori</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($radijator) > 0) {
                $count = 0;
                foreach ($radijator as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Radijator</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($speedread) > 0) {
                $count = 0;
                foreach ($speedread as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Brzina čitanja</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($speed_write) > 0) {
                $count = 0;
                foreach ($speed_write as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Brzina upisivanja</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($pumpa) > 0) {
                $count = 0;
                foreach ($pumpa as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Pumpa</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($stampa) > 0) {
                $count = 0;
                foreach ($stampa as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Štampa</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($rec_photo) > 0) {
                $count = 0;
                foreach ($rec_photo as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Snimanje fotografija</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($rec_video) > 0) {
                $count = 0;
                foreach ($rec_video as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Snimanje videa</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($displey) > 0) {
                $count = 0;
                foreach ($displey as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Displej</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($leziste_spolja) > 0) {
                $count = 0;
                foreach ($leziste_spolja as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Ležišta za spoljne uređaje</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($unutranja_lezeraj) > 0) {
                $count = 0;
                foreach ($unutranja_lezeraj as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Unutrašnja ležišta</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($prikljuci_napred) > 0) {
                $count = 0;
                foreach ($prikljuci_napred as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Priključci napred</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($kamera) > 0) {
                $count = 0;
                foreach ($kamera as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Kamera</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    if ($key == "Model" && $subCat->resource_id == "9106") {
                        
                    } else {
                        $content = new Collection("filter_headers");
                        $headerUrl = "";
                        $headerUrl = $f->generateUrlFromText("$key");
                        $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                        $inHeadersFilter = $inCats[0];
                        if (empty($inHeadersFilter->id)) {
                            $inHeaders = new View("filter_headers");
                            $inHeaders->lang = 1;
                            $inHeaders->title = "$key";
                            $inHeaders->url = "$headerUrl";
                            $inHeaders->cat_resource_id = $subCat->resource_id;
                            $inHeaders->show = 1;
                            $inHeaders->Save();
                        }
                        $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                        $kecHeader = mysql_fetch_object($kecHeader);

                        $contentVal = new Collection("filter_values");
                        $url = "";
                        $url = $f->generateUrlFromText("$value");
                        $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                        $inValueFilter = $inVal[0];
                        if (empty($inValueFilter->id)) {
                            $inValue = new View("filter_values");
                            $inValue->fh_id = $kecHeader->id;
                            $inValue->lang = 1;
                            $inValue->title = "$value";
                            $inValue->url = "$url";
                            $inValue->Save();
                        }
                        $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                        $kecValue = mysql_fetch_object($kecValue);

                        $inJoins = new View("filter_joins");
                        $inJoins->lang = 1;
                        $inJoins->product_rid = $inItem->resource_id;
                        $inJoins->fv_id = $kecValue->id;
                        $inJoins->fh_id = $kecHeader->id;
                        $inJoins->cat_rid = $subCat->resource_id;
                        $inJoins->Save();
                    }
                }
            }

            if (count($prednja_kamera) > 0) {
                $count = 0;
                foreach ($prednja_kamera as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Prednja kamera</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($mobilna_mreza) > 0) {
                $count = 0;
                foreach ($mobilna_mreza as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Mobilna mreža</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }
            if (count($kara_ploca_kuvanje) > 0) {
                $count = 0;
                foreach ($kara_ploca_kuvanje as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Karakterisitke ploče za kuvanje</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($kara_rerne) > 0) {
                $count = 0;
                foreach ($kara_rerne as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Karakterisitke rerne</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }
            if (count($tastatura) > 0) {
                $count = 0;
                foreach ($tastatura as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Tastatura</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    if ($key == "Ostalo" && $subCat->resource_id == "9106") {
                        
                    } else {
                        $content = new Collection("filter_headers");
                        $headerUrl = "";
                        $headerUrl = $f->generateUrlFromText("$key");
                        $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                        $inHeadersFilter = $inCats[0];
                        if (empty($inHeadersFilter->id)) {
                            $inHeaders = new View("filter_headers");
                            $inHeaders->lang = 1;
                            $inHeaders->title = "$key";
                            $inHeaders->url = "$headerUrl";
                            $inHeaders->cat_resource_id = $subCat->resource_id;
                            $inHeaders->show = 1;
                            $inHeaders->Save();
                        }
                        $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                        $kecHeader = mysql_fetch_object($kecHeader);

                        $contentVal = new Collection("filter_values");
                        $url = "";
                        $url = $f->generateUrlFromText("$value");
                        $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                        $inValueFilter = $inVal[0];
                        if (empty($inValueFilter->id)) {
                            $inValue = new View("filter_values");
                            $inValue->fh_id = $kecHeader->id;
                            $inValue->lang = 1;
                            $inValue->title = "$value";
                            $inValue->url = "$url";
                            $inValue->Save();
                        }
                        $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                        $kecValue = mysql_fetch_object($kecValue);

                        $inJoins = new View("filter_joins");
                        $inJoins->lang = 1;
                        $inJoins->product_rid = $inItem->resource_id;
                        $inJoins->fv_id = $kecValue->id;
                        $inJoins->fh_id = $kecHeader->id;
                        $inJoins->cat_rid = $subCat->resource_id;
                        $inJoins->Save();
                    }
                }
            }
            if (count($dodatn_grafika) > 0) {
                $count = 0;
                foreach ($dodatn_grafika as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Dodatna grafika</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }
            if (count($mikrofonija) > 0) {
                $count = 0;
                foreach ($mikrofonija as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Mikrofon</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($lezi_ventilator) > 0) {
                $count = 0;
                foreach ($lezi_ventilator as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Ležišta za ventilatore</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($kompatibilnost) > 0) {
                $count = 0;
                foreach ($kompatibilnost as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Kompatibilnost</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }
            if (count($potrosni_materijal) > 0) {
                $count = 0;
                foreach ($potrosni_materijal as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Potrošni materijal</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }
            if (count($senzor) > 0) {
                $count = 0;
                foreach ($senzor as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Senzor</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }
            if (count($ugradjen_ventil) > 0) {
                $count = 0;
                foreach ($ugradjen_ventil as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Ugrađeni ventilatori</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }
            if (count($skeniranje) > 0) {
                $count = 0;
                foreach ($skeniranje as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Skeniranje</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }
            if (count($kopiranje) > 0) {
                $count = 0;
                foreach ($kopiranje as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Kopiranje</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($fax) > 0) {
                $count = 0;
                foreach ($fax as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Faks</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }
            if (count($fix_prikljuci) > 0) {
                $count = 0;
                foreach ($fix_prikljuci as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Fiksni priključci</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }
            if (count($zvucnici) > 0) {
                $count = 0;
                foreach ($zvucnici as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Zvučnici</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($hladjenje) > 0) {
                $count = 0;
                foreach ($hladjenje as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Hlađenje</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }

            if (count($naponske_grane) > 0) {
                $count = 0;
                foreach ($naponske_grane as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>+12V naponske grane</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }
            if (count($ostale_naponske_grane) > 0) {
                $count = 0;
                foreach ($ostale_naponske_grane as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Ostale naponske grane</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }
            if (count($modularni_prikljci) > 0) {
                $count = 0;
                foreach ($modularni_prikljci as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Modularni priključci</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }
            if (count($graficki_procesor) > 0) {
                $count = 0;
                foreach ($graficki_procesor as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Grafički procesor</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }
            if (count($lampa) > 0) {
                $count = 0;
                foreach ($lampa as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Lampa</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }
            if (count($firewall) > 0) {
                $count = 0;
                foreach ($firewall as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Firewall</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }
            if (count($sata_hardisk) > 0) {
                $count = 0;
                foreach ($sata_hardisk as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>SATA hard diskovi</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }
            if (count($opticki_uredjaji) > 0) {
                $count = 0;
                foreach ($opticki_uredjaji as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Optički uređaj</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }
            if (count($kontroleri) > 0) {
                $count = 0;
                foreach ($kontroleri as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Kontroleri</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }
            if (count($kuciste) > 0) {
                $count = 0;
                foreach ($kuciste as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Kućište</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }
            if (count($hard_diskici) > 0) {
                $count = 0;
                foreach ($hard_diskici as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Hard diskovi</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }
            if (count($pristup_podatcima) > 0) {
                $count = 0;
                foreach ($pristup_podatcima as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Pristup podacima</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }
            if (count($backup) > 0) {
                $count = 0;
                foreach ($backup as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Backup</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }
            if (count($multimedija_podrska) > 0) {
                $count = 0;
                foreach ($multimedija_podrska as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Multimedijalna podrška</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }
            if (count($print_server) > 0) {
                $count = 0;
                foreach ($print_server as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Print server</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }
            if (count($upravljanjec) > 0) {
                $count = 0;
                foreach ($upravljanjec as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Upravljanje</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }
            if (count($usteda_energije) > 0) {
                $count = 0;
                foreach ($usteda_energije as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Ušteda energije</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }
            if (count($ip_cam_server) > 0) {
                $count = 0;
                foreach ($ip_cam_server as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>IP Cam server (video nadzor)</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }
            if (count($vlan) > 0) {
                $count = 0;
                foreach ($vlan as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>VLAN</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }
            if (count($ipv) > 0) {
                $count = 0;
                foreach ($ipv as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>IPv6</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }
            if (count($socivo) > 0) {
                $count = 0;
                foreach ($socivo as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Sočivo</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }
            if (count($mis) > 0) {
                $count = 0;
                foreach ($mis as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Miš</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }
            if (count($sateliti) > 0) {
                $count = 0;
                foreach ($sateliti as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Sateliti</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }
            if (count($ulazni_prikljucakic) > 0) {
                $count = 0;
                foreach ($ulazni_prikljucakic as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Ulazni priključci</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }
            if (count($subwoofer) > 0) {
                $count = 0;
                foreach ($subwoofer as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Subwoofer</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }
            if (count($izlazni_prikljucak) > 0) {
                $count = 0;
                foreach ($izlazni_prikljucak as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Izlazni priključci</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }
            if (count($prednji_sateliti) > 0) {
                $count = 0;
                foreach ($prednji_sateliti as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Prednji sateliti</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }
            if (count($zadnji_sateliti) > 0) {
                $count = 0;
                foreach ($zadnji_sateliti as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Zadnji sateliti</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }
            if (count($centralni_zvucnik) > 0) {
                $count = 0;
                foreach ($centralni_zvucnik as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Centralni zvučnik</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }
            if (count($slika) > 0) {
                $count = 0;
                foreach ($slika as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $productDesc .= '<tr class="titRed"><td>Slika</td><td>' . $key . '</td><td>' . $value . '</td></tr>';
                    } else {
                        $productDesc .= "<tr><td>&nbsp;</td><td>$key</td><td>$value</td></tr>";
                    }

                    $content = new Collection("filter_headers");
                    $headerUrl = "";
                    $headerUrl = $f->generateUrlFromText("$key");
                    $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $inHeadersFilter = $inCats[0];
                    if (empty($inHeadersFilter->id)) {
                        $inHeaders = new View("filter_headers");
                        $inHeaders->lang = 1;
                        $inHeaders->title = "$key";
                        $inHeaders->url = "$headerUrl";
                        $inHeaders->cat_resource_id = $subCat->resource_id;
                        $inHeaders->show = 1;
                        $inHeaders->Save();
                    }
                    $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                    $kecHeader = mysql_fetch_object($kecHeader);

                    $contentVal = new Collection("filter_values");
                    $url = "";
                    $url = $f->generateUrlFromText("$value");
                    $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $inValueFilter = $inVal[0];
                    if (empty($inValueFilter->id)) {
                        $inValue = new View("filter_values");
                        $inValue->fh_id = $kecHeader->id;
                        $inValue->lang = 1;
                        $inValue->title = "$value";
                        $inValue->url = "$url";
                        $inValue->Save();
                    }
                    $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                    $kecValue = mysql_fetch_object($kecValue);

                    $inJoins = new View("filter_joins");
                    $inJoins->lang = 1;
                    $inJoins->product_rid = $inItem->resource_id;
                    $inJoins->fv_id = $kecValue->id;
                    $inJoins->fh_id = $kecHeader->id;
                    $inJoins->cat_rid = $subCat->resource_id;
                    $inJoins->Save();
                }
            }
            if (count($garancijaNiz) > 0) {
                $count = 0;
                foreach ($garancijaNiz as $key => $value) {
                    $count++;
                    $value = rtrim($value, ", ");
                    if ($count == 1) {
                        $inItem->warranty = $value;

                        $content = new Collection("filter_headers");
                        $headerUrl = "";
                        $headerUrl = $f->generateUrlFromText("$key");
                        $inCats = $content->getCollection("WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                        $inHeadersFilter = $inCats[0];
                        if (empty($inHeadersFilter->id)) {
                            $inHeaders = new View("filter_headers");
                            $inHeaders->lang = 1;
                            $inHeaders->title = "$key";
                            $inHeaders->url = "$headerUrl";
                            $inHeaders->cat_resource_id = $subCat->resource_id;
                            $inHeaders->show = 1;
                            $inHeaders->Save();
                        }
                        $kecHeader = mysql_query("SELECT id FROM filter_headers WHERE url = '$headerUrl' AND cat_resource_id = '$subCat->resource_id' LIMIT 1");
                        $kecHeader = mysql_fetch_object($kecHeader);

                        $contentVal = new Collection("filter_values");
                        $url = "";
                        $url = $f->generateUrlFromText("$value");
                        $inVal = $contentVal->getCollection("WHERE url = '$url' AND fh_id = '$kecHeader->id' LIMIT 1");
                        $inValueFilter = $inVal[0];
                        if (empty($inValueFilter->id)) {
                            $inValue = new View("filter_values");
                            $inValue->fh_id = $kecHeader->id;
                            $inValue->lang = 1;
                            $inValue->title = "$value";
                            $inValue->url = "$url";
                            $inValue->Save();
                        }
                        $kecValue = mysql_query("SELECT id FROM filter_values WHERE title = '$value' AND fh_id = '$kecHeader->id' LIMIT 1");
                        $kecValue = mysql_fetch_object($kecValue);

                        $inJoins = new View("filter_joins");
                        $inJoins->lang = 1;
                        $inJoins->product_rid = $inItem->resource_id;
                        $inJoins->fv_id = $kecValue->id;
                        $inJoins->fh_id = $kecHeader->id;
                        $inJoins->cat_rid = $subCat->resource_id;
                        $inJoins->Save();
                    }
                }
            }

            $inItem->technical_description = $productDesc;
            $inItem->Save();
        }
    }

    /*     * ***************************** BRISANJE NEAKTIVNIH FILTERA ************************************** */
    $brisiFiltere = 0;
    $zaobrisati = mysql_query("SELECT cp.resource_id as cprid, fj.id as fjid FROM _content_proizvodi cp LEFT JOIN filter_joins fj ON fj.product_rid = cp.resource_id WHERE cp.status = 2 AND cp.master_status != 'Active'");
    while ($zaobrisatProduct = mysql_fetch_object($zaobrisati)) {
        if ($zaobrisatProduct->fjid != "") {
            $brisiFiltere++;
            mysql_query("DELETE FROM filter_joins WHERE id = '$zaobrisatProduct->fjid'");
        }
    }
    /*     * ***************************** KRAJ BRISANJA NEAKTIVNIH FILTERA ************************************** */

    echo "\r\nFILTERI PROIZVODA UBACENI \r\n\r\n\r\n";
    echo "IMA $filter NOVIH FILTERA, A NIZ JE:\r\n\r\n" . $filters . "\r\n\r\n";
    echo $brisiFiltere . " - UKUPNO OBRISANO FILTERA\r\n\r\n";
    echo $uso . " - UKUPNO PROIZVODA";
    ?> 
</html>