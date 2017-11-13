<h1>Adresiranje</h1>
<div class="payHolder row">
    <div class="third">
        <div class="quarterMarginRB">
            <h4>Podaci iz profila</h4>
            <?php
            $userID = $_SESSION["loged_user"];
            $user = new View("_content_users", $userID);
            ?>
            <p>Ime i prezime: <strong><?= $user->ime . " " . $user->prezime; ?></strong></p>
            <p>Adresa: <strong><?= $user->ulica_i_broj; ?></strong></p>
            <p>Naselje: <strong><?= $user->naselje; ?></strong></p>
            <?php $gradJE = new View("_content_cities", $user->grad, "resource_id"); ?>
            <p>Grad: <strong><?= $user->postanski_broj . " " . $gradJE->title; ?></strong></p>
            <p>Telefon: <strong><?= $user->mobilni_telefon; ?></strong></p>
            <a href="/nalog/promena-adrese" title=""></a>
        </div>
    </div>
    <div class="third">
        <div class="quarterMarginRS quarterMarginLS">
            <?php
            $greske = array();
            if ($_POST["new_address"] != '') {
                if (strlen($f->getValue("ime")) < 2 || trim($f->getValue("ime")) == '') {
                    array_push($greske, "ime");
                }
                if (strlen($f->getValue("prezime")) < 2 || trim($f->getValue("prezime")) == '') {
                    array_push($greske, "prezime");
                }
                if (strlen($f->getValue("grad")) < 2 || trim($f->getValue("grad")) == '') {
                    array_push($greske, "grad");
                }
                if (strlen($f->getValue("ulica_i_broj")) < 2 || trim($f->getValue("ulica_i_broj")) == '') {
                    array_push($greske, "ulica_i_broj");
                }
            }
            ?>


            <h4>Podaci za prijem pošiljke</h4>
            <p><label for="hangeAddressHide"><input type="radio" checked="checked" class="dostava" name="dostava" value="1" id="hangeAddressHide">Želim da mi se poručeni proizvod isporuči na moju adresu (adresa iz profila na koju ste se registrovali)</label></p>
            <p><label for="clickChangeAddress"><input type="radio" name="dostava" class="dostava" value="2" id="clickChangeAddress">Želim da mi se porudžbina dostavi na drugu adresu</label></p>
        </div>
    </div>
    <div class="third">
        <?php
        if ($_POST["new_address"] != '') {
            if (empty($greske)) {
                $gradic = new View("_content_cities", $f->getValue("grad"), "resource_id");

                $user->extend($_POST);
                $user->postanski_broj = $gradic->postanski_broj;
                $user->Save();
                $f->redirect("/korpa-placanje");
            } else {
                ?>
                <div class="errors">
                    <h3>Greška</h3>
                    <ul>
                        <?php
                        if (in_array("ime", $greske)) {
                            echo "<li>Greška ime</li>";
                        }
                        ?>
                        <?php
                        if (in_array("prezime", $greske)) {
                            echo "<li>Greška prezime</li>";
                        }
                        ?>
                        <?php
                        if (in_array("naselje", $greske)) {
                            echo "<li>Greška adresa</li>";
                        }
                        ?>
                        <?php
                        if (in_array("grad", $greske)) {
                            echo "<li>Greška grad</li>";
                        }
                        ?>
                    </ul>
                </div>
                <?php
            }
        }
        ?>
        <div class="quarterMarginLB none" id="changeAddress" style='display: none;'>
            <form class="row" action="" method="post">
                <!-- SVE U VEZI USERA MORA SE POKLAPATI SA BAZOM I POLJIMA U BAZI -->
                <p class="full">
                    <label>Ime</label>
                    <input type="text" id="name" name="ime" value="<?= $user->ime; ?>">
                </p>
                <p class="full">
                    <label>Prezime</label>
                    <input type="text" id="nachname" name="prezime" value="<?= $user->prezime; ?>">
                </p>
                <p class="full">
                    <label>Mobilni telefon</label>
                    <input type="text" id="mobile_telefon" name="mobilni_telefon" value="<?= $user->mobilni_telefon; ?>">
                </p>
                <p class="full">
                    <label>Fiksni telefon</label>
                    <input type="text" id="festnetz_telefon" name="fiksni_telefon" value="<?= $user->fiksni_telefon; ?>">
                </p class="full">
                <p class="third-x2">
                    <label>Adresa</label>
                    <input type="text" id="adresse" name="ulica_i_broj" value="<?= $user->ulica_i_broj; ?>">
                </p>
                <p class="third">
                    <label>Naselje</label>
                    <input type="text" id="siedlung" name="naselje" value="<?= $user->naselje; ?>">
                </p>
                <p class="full">
                    <label>Grad</label>
                    <select id="city" name="grad">
                        <?php
                        $gradovi = mysqli_query($conn,"SELECT title, resource_id, postanski_broj FROM _content_cities WHERE status = 1 ORDER BY title");
                        while ($gradoviLista = mysqli_fetch_object($gradovi)) {
                            ?>
                            <option <?= ($user->grad == "$gradoviLista->resource_id") ? "selected" : ""; ?> value="<?= $gradoviLista->resource_id; ?>"><?= $gradoviLista->title . " (" . $gradoviLista->postanski_broj . ")"; ?></option>
                        <?php } ?>
                    </select>
                </p>
                <p class="withInput full">
                    <input type="submit" name="new_address" value="Sačuvaj">  
                </p>
            </form>
        </div>
    </div>
</div>
<div class="goBack margin-vertical clear">
    <div class="prGoL left">
        <i class="fa fa-caret-left prevGo transition"></i>
        <a class="left" href="/korpa" title="Vrati se nazad">Vrati se nazad</a>
    </div>
    <div class="neGoR right">
        <a class="right" href="/korpa-dostava" id="korpa-dostava" title="Dalje">Dalje</a> 
        <i class="fa fa-caret-right next transition"></i>
    </div>
</div>