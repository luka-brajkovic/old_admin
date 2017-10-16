<div class="cart">
    <h1>Vaša korpa</h1>
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
                <th></td>
            </tr>
        </thead>
        <tbody>
            <?php
            $sessionID = session_id();
            $korpa = new View("korpa", $sessionID, 'session_id');
            if (!empty($korpa->id)) {
                $korpaID = $korpa->id;
                $proizvodiKorpe = $db->execQuery("SELECT PK.id, PK.original_rid, c1.url as c_url, c.url as cat_url, CP.product_image as slika, PK.title, PK.cena, PK.kolicina, CP.url as prod_url, CP.resource_id as prod_rid, CP.gratis_id as gratis, CP.gratis_id_2 as gratis_two FROM proizvodi_korpe PK "
                        . "JOIN _content_proizvodi CP ON CP.resource_id = PK.original_rid "
                        . " LEFT JOIN _content_brend cb ON cb.resource_id = CP.brand "
                        . " LEFT JOIN categories_content cc ON CP.resource_id = cc.content_resource_id "
                        . " LEFT JOIN categories c1 ON c1.resource_id = cc.category_resource_id "
                        . " LEFT JOIN categories c ON c.resource_id = c1.parent_id "
                        . "WHERE PK.korpa_rid = $korpaID");

                $total = 0;
                while ($dataKorpa = mysql_fetch_array($proizvodiKorpe)) {
                    ?>
                    <tr>
                        <td>
                            <a href="/<?= $dataKorpa['cat_url'] . "/" . $dataKorpa['c_url'] . "/" . $dataKorpa['prod_url'] . "/" . $dataKorpa['prod_rid']; ?>" title="<?= $dataKorpa['title']; ?>">
                                <?php
                                if (is_file("uploads/uploaded_pictures/_content_proizvodi/" . $dimUrlLitSecund . "/" . $dataKorpa['slika'])) {
                                    ?>
                                    <img src="/uploads/uploaded_pictures/_content_proizvodi/<?php echo $dimUrlLitSecund . "/" . $dataKorpa['slika']; ?>" alt="<?php echo $dataKorpa['title']; ?>" title="<?php echo $dataKorpa['title']; ?>" />
                                <?php } elseif ($dataKorpa['slika'] != "") {
                                    ?>
                                    <img src="<?php echo $dataKorpa['slika']; ?>" alt="<?php echo $dataKorpa['title']; ?>" title="<?php echo $dataKorpa['title']; ?>" />
                                    <?php
                                } else {
                                    ?>
                                    <img class="transition" src="/images/no-image.jpg" alt="<?= $dataKorpa['title']; ?> slika" title="<?= $dataKorpa['title']; ?>" />
                                    <?php
                                }
                                ?>
                            </a>
                        </td>
                        <td><?php echo $dataKorpa['title']; ?></td>
                        <td><?php echo number_format($dataKorpa['cena'], 2, ",", "."); ?> rsd</td>
                        <td>
                            <select class="korpa-proizvod-kolicina" data-item-id="<?php echo $dataKorpa['id']; ?>">
                                <?php
                                for ($i = 1; $i < 11; $i++) {
                                    $selected = $i == $dataKorpa['kolicina'] ? 'selected="selected"' : "";
                                    ?>
                                    <option <?php echo $selected; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            <strong><?php echo number_format($dataKorpa['cena'] * $dataKorpa['kolicina'], 2, ",", "."); ?> rsd</strong>
                        </td>
                        <td>
                            <a href="javascript:izbaciIzKorpe('<?php echo $dataKorpa['id']; ?>')" title="Izbaci iz korpe">
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
                            <td><?php echo $gratis->title; ?></td>
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
                    <a href="/" title="Nastavi kupovinu">Nastavi kupovinu</a>
                </td>
                <td>

                </td>
                <td>

                </td>
                <td>
                    <span>Ukupno za plaćanje</span>
                </td>
                <td>
                    <span><?php echo number_format($total, 2, ",", "."); ?> rsd</span>
                </td>
                <td>
                    <a href="/korpa-prijava" title="Sledeći korak">Dalje</a>
                    <i class="fa fa-caret-right next transition"></i>
                </td>
            </tr>
        </tfoot>
    </table>
</div>