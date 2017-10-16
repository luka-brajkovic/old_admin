
<h1>Dostava</h1>
<div class="nacinHolder margin-vertical row">
    <div class="half">
        <div class="nacinHolderInner">
            <h4>Način plaćanja</h4>
            <p>Dragi korisniče, nalazite se na poslednjem koraku u procesu kupovine. Molimo da pažljivo proverite sve detalje kupovine. Takođe, potrebno je i da izaberete jedan od ponuđenih načina plaćanja. Detaljne informacije o načinima plaćanja možete naći na sledećem linku načini plaćanja. Ukoliko je sve kao što ste želeli, kliknite na dugme Poruči. Nakon toga kupovina je obavljena, a vi ćete dobiti email sa svim njenim detaljima.</p>
        </div>
    </div>
    <?php
    $sessionID = session_id();
    $korpa = new View("korpa", $sessionID, 'session_id');
    ?>
    <div class="half">
        <div class="nacinHolderInner">
            <p><label for="pay1"><input <?php if ($korpa->nacin_placanja == 1) echo 'checked="checked"'; ?> type="radio" name="payment_where" class="nacin-placanja-radio" value="1" id="pay1">Plaćanje pouzećem kuriru prilikom preuzimanja pošiljke. Plaćanje se vrši isključivo gotovinom.</label></p>
            <p><label for="pay2"><input <?php if ($korpa->nacin_placanja == 2) echo 'checked="checked"'; ?> type="radio" name="payment_where" class="nacin-placanja-radio" value="2" id="pay2">Uplatnicom na šalteru banke ili pošte ili e-bankingom. Rok za uplatu porudžbenice je tri radna dana odnosno 72 sata, nakon čega će ista biti stornirana.</label></p>
        </div>
    </div>
</div>
<div class="potvrdaHolder margin-vertical clear">
    <h4>Potvrda porudžbine</h4>
    <table>
        <tr>
            <th>Naručilac</th>
            <th>Adresa isporuke</th>
            <th>Način dostave</th>
            <th>Način plaćanja</th>
            <th>Napomena</th>
        </tr>

        <tr>
            <td><?= $korpa->ime; ?> <?= $korpa->prezime; ?></td>
            <?php
            $cityc = new View("_content_gradovi", $korpa->grad, 'resource_id');
            ?>
            <td><?= $korpa->adresa; ?>, <?=
                $korpa->zip . " ";
                echo ($cityc->title != "") ? $cityc->title : $korpa->grad;
                ?></td>
            <td>CITY EXPRESS</td>
            <td id="nacin-placanja">
                <?php
                if ($korpa->nacin_placanja == 1) {
                    echo "Plaćanje pouzećem kuriru prilikom preuzimanja pošiljke. Plaćanje se vrši isključivo gotovinom.";
                } else {
                    echo "Uplatnicom na šalteru banke ili pošte ili e-bankingom. Rok za uplatu porudžbenice je tri radna dana odnosno 72 sata, nakon čega će ista biti stornirana.";
                }
                ?>
            </td>
            <td><textarea name="napomena" id="napomena"><?= $korpa->napomena; ?></textarea></td>
        </tr>
    </table>
</div>
<div class="finishCart">
    <div class="cart">
        <table>
            <thead>
                <tr>
                    <th>slika</th>
                    <th>naziv</th>
                    <th>cena</th>
                    <th>količina</th>
                    <th>
                        <strong>ukupno</strong>
                    </th>
                    <th>
                    </th></tr>
            </thead>
            <tbody>
                <?php
                if (!empty($korpa->id)) {
                    $korpaID = $korpa->id;
                    $proizvodiKorpe = $db->execQuery("SELECT PK.id, PK.original_rid, CP.product_image as slika, PK.title, PK.cena, PK.kolicina, CP.gratis_id as gratis, CP.gratis_id_2 as gratis_two FROM proizvodi_korpe PK "
                            . "JOIN _content_proizvodi CP ON CP.resource_id = PK.original_rid "
                            . "WHERE PK.korpa_rid = $korpaID");

                    $total = 0;
                    while ($dataKorpa = mysql_fetch_array($proizvodiKorpe)) {
                        ?>
                        <tr>
                            <td>
                                <img width="179" height="179" src="/uploads/uploaded_pictures/_content_proizvodi/<?= $dimUrlLitSecund . "/" . $dataKorpa['slika']; ?>" alt="<?= $dataKorpa['title']; ?>" title="<?= $dataKorpa['title']; ?>" />
                            </td>
                            <td><?= $dataKorpa['title']; ?></td>
                            <td><?= number_format($dataKorpa['cena'], 2, ",", "."); ?> rsd</td>
                            <td>
                                <select class="korpa-proizvod-kolicina" data-item-id="<?= $dataKorpa['id']; ?>">
                                    <?php
                                    for ($i = 1; $i < 11; $i++) {
                                        $selected = $i == $dataKorpa['kolicina'] ? 'selected="selected"' : "";
                                        ?>
                                        <option <?= $selected; ?> value="<?= $i; ?>"><?= $i; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <strong><?= number_format($dataKorpa['cena'] * $dataKorpa['kolicina'], 2, ",", "."); ?> rsd</strong>
                            </td>
                            <td>
                                <a href="javascript:izbaciIzKorpe('<?= $dataKorpa['id']; ?>')" title="Izbaci iz korpe">
                                    <i class="fa fa-times transition"></i>
                                </a>
                            </td>
                        </tr>
                        <?php
                        if ($dataKorpa['gratis']) {
                            $gratis = mysql_query("SELECT cp.product_image, cp.resource_id, cp.title, cp.url, c.url as sub_url, c1.url as cat_url, cb.title as b_title FROM _content_proizvodi cp "
                                    . " LEFT JOIN categories_content cc ON cc.content_resource_id = cp.resource_id "
                                    . " LEFT JOIN categories c ON c.resource_id = cc.category_resource_id "
                                    . " LEFT JOIN categories c1 ON c.parent_id = c1.resource_id "
                                    . " LEFT JOIN _content_brend cb ON cb.resource_id = cp.brand "
                                    . " WHERE cp.resource_id = " . $dataKorpa['gratis'] . " LIMIT 1");
                            $gratis = mysql_fetch_object($gratis);
                            ?>
                            <tr>
                                <td>
                                    <a href="/<?= $gratis->sub_url . "/" . $gratis->cat_url . "/" . $gratis->url . "/" . $gratis->resource_id; ?>" title="<?= $gratis->b_title . " " . $gratis->title; ?>">
                                        <img src="/uploads/uploaded_pictures/_content_proizvodi/<?php echo $dimUrlLitSecund . "/" . $gratis->product_image; ?>" alt="<?= $gratis->b_title . " " . $gratis->title; ?>">
                                    </a>
                                </td>
                                <td><?= $gratis->b_title . " " . $gratis->title; ?></td>
                                <td><?= $dataKorpa['kolicina']; ?>,00 rsd</td>
                                <td><?= $dataKorpa['kolicina']; ?></td>
                                <td>
                                    <strong><?= $dataKorpa['kolicina']; ?>,00 rsd</strong>
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                            <?php
                            $total += $dataKorpa['kolicina'];
                        }
                        if ($dataKorpa['gratis_two']) {
                            $gratis = mysql_query("SELECT cp.product_image, cp.resource_id, cp.title, cp.url, c.url as sub_url, c1.url as cat_url, cb.title as b_title FROM _content_proizvodi cp "
                                    . " LEFT JOIN categories_content cc ON cc.content_resource_id = cp.resource_id "
                                    . " LEFT JOIN categories c ON c.resource_id = cc.category_resource_id "
                                    . " LEFT JOIN categories c1 ON c.parent_id = c1.resource_id "
                                    . " LEFT JOIN _content_brend cb ON cb.resource_id = cp.brand "
                                    . " WHERE cp.resource_id = " . $dataKorpa['gratis_two'] . " LIMIT 1");
                            $gratis = mysql_fetch_object($gratis);
                            ?>
                            <tr>
                                <td>
                                    <a href="/<?= $gratis->sub_url . "/" . $gratis->cat_url . "/" . $gratis->url . "/" . $gratis->resource_id; ?>" title="<?= $gratis->b_title . " " . $gratis->title; ?>">
                                        <img src="/uploads/uploaded_pictures/_content_proizvodi/<?php echo $dimUrlLitSecund . "/" . $gratis->product_image; ?>" alt="<?= $gratis->b_title . " " . $gratis->title; ?>">
                                    </a>
                                </td>
                                <td><?= $gratis->b_title . " " . $gratis->title; ?></td>
                                <td><?= $dataKorpa['kolicina']; ?>,00 rsd</td>
                                <td><?= $dataKorpa['kolicina']; ?></td>
                                <td>
                                    <strong><?= $dataKorpa['kolicina']; ?>,00 rsd</strong>
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                            <?php
                            $total += $dataKorpa['kolicina'];
                        }
                        $total += $dataKorpa['cena'] * $dataKorpa['kolicina'];
                    }
                } else {
                    echo "<tr><td colspan='6'>Nema proizvoda u korpi.</td></tr>";
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td>
                        <i class="fa fa-caret-left prevGo transition"></i>
                        <a class="gogoBack" href="/korpa-placanje" title="Vrati se nazad">Vrati se nazad</a>
                    </td>
                    <td>

                    </td>
                    <td>

                    </td>
                    <td>
                        <span>Ukupno za plaćanje</span>
                    </td>
                    <td>
                        <span><?= number_format($total, 2, ",", "."); ?> rsd</span>
                    </td>
                    <td>
                        <a href="javascript:void(0)" id="korpa-poruci" title="Sledeći korak">Poruči</a>
                        <i class="fa fa-caret-right next transition"></i>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>