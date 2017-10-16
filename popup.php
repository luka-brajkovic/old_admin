<?php
if ($f->getValue("poruka") != '') {
    $poruka = $f->getValue("poruka");
    ?>
    <div id="popup">
        <div id='popupInner'>
            <?php
            switch ($poruka) {
                case "prekratka-rec":
                    ?>
                    <h1>Prekratka reč za pretragu</h1>
                    <p>Poštovani, molimo Vas da pretražujete po reči od minimum 3 karaktera</p>
                    <?php
                    break;
                case "odjavljen":
                    ?>
                    <h1>Već ste odjavljeni</h1>
                    <p>Poštovani, Vaš email je već bio odjavljen sa naše newsletter liste!</p>
                    <?php
                    break;
                case "lista-zelja-prazna":
                    ?>
                    <h1>Lista želja je prazna</h1>
                    <p>Poštovani, Vaša lista želja je prazna, proizvode možete dodati u istu želja klikom na ikonicu srca na svakom proizvodu.</p>
                    <?php
                    break;
                case "uspesna-odjava":
                    ?>
                    <h1>Odjava sa newsletter liste</h1>
                    <p>Poštovani, uspešno ste se odjavili sa naše newsletter liste!</p>
                    <?php
                    break;
                case "404":

                    echo $db->getValue2("text", "_content_html_blocks", "resource_id", "8859", "lang", $currentLanguage);
                    break;
                case "lozinka-promenjena":
                    ?>
                    <h1>Lozinka promenjena</h1>
                    <p>Vaša lozinka je uspešno promenjena, ukoliko i dalje imate problema pri pristupanju Vašem nalogu, kontaktirajte nas:<br/><br/>Telefon: <?= $configSitePhone; ?><br/> E-mail: <a href='mailto:<?= $configSiteEmail; ?>'><?= $configSiteEmail; ?></a>.</p>
                    <?php
                    break;
                case "sacuvano":
                    ?>
                    <h1>Uspešno</h1>
                    <p>Podatci su sačuvani.</p>
                    <?php
                    break;
                case "poruceno":
                    echo $db->getValue2("text", "_content_html_blocks", "resource_id", "8860", "lang", $currentLanguage);
                    break;
                case "obavestenje":
                    ?>
                    <h1>Obaveštenje</h1>
                    <p>Nema proizvoda u korpi.</p>
                    <?php
                    break;
                case "prijava":

                    echo $db->getValue2("text", "_content_html_blocks", "resource_id", "8861", "lang", $currentLanguage);
                    break;
                case "poslata-aktivacija":
                    ?>
                    <h1>Poslat aktivacioni link</h1>
                    <p>Poštovani, na Vaš e-mail je poslat aktivacioni link. U cilju aktivacije Vašeg naloga potrebno je kliknuti na isti.</p>
                    <p>Ukoliko ne možete da ga pronađete proverite JUNK SPAM i TRASH folder Vašeg e-maila.</p>
                    <p>Ako ne pronađete aktivacioni link kontaktirajte nas na:<br/><br/>Telefon: <?= $configSitePhone; ?><br/> E-mail: <a href=''mailto:<?= $configSiteEmail; ?>'><?= $configSiteEmail; ?></a>.</p>
                    <?php
                    break;
                case "ne-mozete-poslati-aktivaciju":
                    ?>
                    <h1>Ne možete poslati aktivaciju</h1>
                    <p>Poštovani, na Vaš e-mail je već jednom poslat aktivacioni link.</p>
                    <p>Ukoliko ne možete da ga pronađete proverite JUNK SPAM i TRASH folder Vašeg e-maila.</p>
                    <p>Ako ne pronađete aktivacioni link kontaktirajte nas na:<br/><br/>Telefon: <?= $configSitePhone; ?><br/> E-mail: <a href=''mailto:<?= $configSiteEmail; ?>'><?= $configSiteEmail; ?></a>.</p> 
                    <?php
                    break;
                case "ne-postoji-nalog":
                    ?>
                    <h1>Nalog sa ovim e-mailom ne postoji</h1>
                    <p>Poštovani, ovaj nalog ne postoji.</p>
                    <p>Pokušajte da se registrujete klikom na link <a href='/registracija'>registracija</a>.</p>
                    <p>Ako imate problema sa registracijom, kontaktirajte nas na:<br/><br/>Telefon: <?= $configSitePhone; ?><br/> E-mail: <a href=''mailto:<?= $configSiteEmail; ?>'><?= $configSiteEmail; ?></a>.</p> 
                    <?php
                    break;
                case "dobrodosli":
                    ?>
                    <h1>Dobrodošli</h1>
                    <p>Poštovani, želimo Vam prijatno korišćenje naše internet prodavnice.</p>

                    <?php
                    break;
            }
            ?>
            <a href='javascript:' onclick='closePopup();' class="more">Zatvori</a>
        </div>
    </div>  
    <?php
}
?>

