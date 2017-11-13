<?php

class Site extends Functions {

    /**
     * 
     * @param $menuResId int Resource id za meni koji zelim da se ispisuje
     * @param $parentId int Parent id za linkove koje ispisujem
     * @param $active string Url stranice na kojoj se trenutno nalazim, pa na osnovu tog stampam klasu active ili koja je vec
     * @param $lang int Id jezika na kome se trenutno nalazim
     * @param $activeClass string Naziv klase koju stampam kada je link u meniju aktivan, po defaultu je active, moze da se menja
     * @param $level int Koliko sam u dubinu otisao
     * 
     */
    function getFiltersFrontEndOptimized($cat_rid, $server_url) {

        $catData = mysqli_query($this->dbLink,"SELECT url FROM categories WHERE resource_id = $cat_rid LIMIT 1");
        $catData = mysqli_fetch_object($catData);

        $server_url = str_replace($catData->url, $catData->url . "#", $server_url);
        if (substr($server_url, -1) == "/") {
            $server_url = substr($server_url, 0, -1);
        }

        $addonUrlEx = explode("#", $server_url);

        $filterHeadersArr = mysqli_query($this->dbLink,"SELECT title, id, `show`, url FROM filter_headers WHERE cat_resource_id = $cat_rid ORDER BY ordering") or die(mysqli_error($this->dbLink));
        while ($fh = mysqli_fetch_object($filterHeadersArr)) {
            $counter = 0;

            $filValue = mysqli_query($this->dbLink,"SELECT fv.url, fv.id, fv.title FROM filter_values fv "
                    . " JOIN filter_joins fj ON fj.fv_id = fv.id "
                    // . " JOIN _content_products cp ON cp.resource_id = fj.product_rid "
                    . " WHERE "
                    // . " (cp.status = 1 OR cp.master_status = 'Active') "
                    . " fv.fh_id = '$fh->id' GROUP BY (fv.id) ORDER BY CAST(fv.title AS UNSIGNED), fv.title ASC ") or die(mysqli_error($this->dbLink));
            $numbPerFilter = mysqli_num_rows($filValue);

            if ($numbPerFilter > 0) {
                echo "<div class='filter_group'><h5>$fh->title</h5>";
                echo "<ul class='check' data-id='" . $fh->id . "'>";
            } else {
                echo "<div class='filter_group'>";
                echo "<ul>";
            }
            switch ($fh->show) {
                case '1':


                    while ($value = mysqli_fetch_object($filValue)) {
                        $currentUrl = $server_url;

                        if (strpos($currentUrl, $fh->url) !== false) {
                            /* AKO IMA OVAJ HEADER */

                            $currentEx = explode("/" . $fh->url, $currentUrl);
                            list($thisPart) = explode("/", $currentEx[0]);
                            $oldPart = $thisPart;
                            if (strpos($thisPart, $value->url . "_") !== false) {
                                $thisPart = str_replace($value->url . "_", "", $thisPart);
                            } else if (strpos($thisPart, $value->url) !== false) {
                                $thisPart = str_replace($value->url, "", $thisPart);
                            } else {
                                $thisPart .= "_" . $value->url;
                            }
                            $currentUrl = str_replace($oldPart, $thisPart, $currentUrl);
                        } else {

                            /* AKO NEMA OVAJ HEADER */
                            $currentUrl .= "/" . $fh->url . "=" . $value->url;
                        }

                        // AKO HOCES DA UBACIS KOLIKO PROIZVODA IMA DODAJ ($countProds) 
                        $countProds->count = 0;

                        $proizvodiArr = mysqli_query($this->dbLink,"
								SELECT COUNT(cp.id) as count FROM _content_products cp 
                                JOIN filter_joins fj ON cp.resource_id = fj.product_rid
								WHERE fj.fv_id LIKE '%$value->id%' AND fj.fh_id = $fh->id "
                                . " AND (cp.status = 1 OR cp.master_status = 'Active') "
                                . "") or die(mysqli_error($this->dbLink));
                        $countProds = mysqli_fetch_object($proizvodiArr);

                        $urlExplosion = explode("&", $server_url);
                        if ($countProds->count > 0) {
                            echo "<li>";
                            /* ISPITIVANJE URL-a */
                            $filtersURL = "";
                            $urlEXPLOSION = explode("filters=true", $server_url);
                            if (!isset($urlEXPLOSION[1])) {
                                $urlEXPLOSION[1] = null;
                            } else {
                                $filtersURL = $urlEXPLOSION[1];
                            }

                            if (strpos($filtersURL, "&" . $fh->id . "=") !== false && strpos($filtersURL, "-" . $value->id . "-") !== false) {
                                echo "<span class=\"checkClick filter active\"><i class='fa fa-square inac'></i><i class='fa fa-check-square acti'></i></span>";
                            } else {
                                echo "<span class=\"checkClick filter\"><i class='fa fa-square inac'></i><i class='fa fa-check-square acti'></i></span>";
                            }
                            echo "<label>" . htmlspecialchars_decode($value->title) . " ($countProds->count)</label>";
                            echo "<input type='checkbox' name='" . $fh->url . "' value='$value->id' /></li>";
                            if (strpos($filtersURL, "&" . $fh->id . "=") === false && strpos($filtersURL, "-" . $value->id . "-") === false) {
                                if ($counter == 3) {
                                    ?>
                                    <span id="doNotShowFilter-<?= $fh->url; ?>" class="doNotShowFilter">
                                    <?php } elseif ($numbPerFilter - 1 == $counter && $counter > 2) { ?>
                                    </span>
                                    <a id='open-<?= $fh->url; ?>' class="open" onclick="showMoreFilters('<?= $fh->url; ?>')" href="javascript:">Prikaži sve</a>
                                    <i class="fa fa-angle-down filterArrowDown-<?= $fh->url; ?> filterArrowDown"></i><i class="fa fa-angle-up filterArrowUp-<?= $fh->url; ?> filterArrowUp"></i>
                                    <?php
                                }
                            }else{ ?>
                                    
                            <?php }
                            $counter++;
                        }
                    }

                    break;

                case '2':
                    $filterValuesArr = mysqli_query($this->dbLink,"SELECT * FROM filter_values WHERE fh_id = $fh->id ") or die(mysqli_error($this->dbLink));
                    $currentUrl = $addonUrlEx[1];

                    if (mysqli_num_rows($filterValuesArr) > 0) {
                        echo "<select onchange=\"var thisValue = $(this).val(); submitFilters(thisValue,$fh->show)\" name='" . $fh->id . "_1' id='" . $fh->id . "_1'>";

                        echo "<option value='" . $fh->url . "_all'>Izaberi</option>";

                        while ($value = mysqli_fetch_object($filterValuesArr)) {
                            $proizvodiArr = mysqli_query($this->dbLink,"
										SELECT COUNT(cp.id) as count FROM _content_products cp JOIN filter_joins fj ON cp.resource_id = fj.product_rid 
										WHERE fj.fv_id LIKE '%$value->id--,%' AND fj.fh_id = $fh->id "
                                    //  . " AND (cp.status = 1 OR cp.master_status = 'Active') "
                                    . " AND cp.lang = 1 ");
                            $countProds = mysqli_fetch_object($proizvodiArr);

                            if (strpos($currentUrl, "/" . $fh->url . "=") !== false) {

                                $currentExplode = explode("/" . $fh->url . "=", $currentUrl);
                                $currentEx1 = explode("/", $currentExplode[1]);
                                $currentEx = $currentEx1[0];
                                if (strpos($currentEx, $value->url) !== false) {
                                    echo "<option selected = 'selected' value='" . $fh->url . "_" . $value->url . "'>$value->title ($countProds->count)</option>";
                                } else {
                                    echo "<option value='" . $fh->url . "_" . $value->url . "'>$value->title ($countProds->count - $currentEx)</option>";
                                }
                            } else {
                                echo "<option value='" . $fh->url . "_" . $value->url . "'>$value->title ($countProds->count)</option>";
                            }
                        }
                        echo "</select>";
                    }

                    break;

                default:

                    break;
            }
            echo "</ul>";
            echo "</div>";
        }
        return;
    }

    function printCatsOptions($usluge_proizvodi, $categoriesArr) {
        if ($usluge_proizvodi == 1) {
            /* USLUGE */

            echo "<option value='0'>Usluge</option>";
            foreach ($categoriesArr as $cData) {
                echo "<option value='$cData->resource_id'>$cData->title</option>";
            }
        } else {
            /* PROIZVODI */
        }
    }

    function cutString($string, $lenght) {
        $arr = explode(" ", $string);
        $string = "";
        for ($i = 0; $i <= $lenght; $i++) {
            $string .= $arr[$i] . " ";
        }
        if (count($arr) > $lenght) {
            $string.='...';
        }
        return $string;
    }

    function printCatsMenu($usluge_proizvodi, $categoriesArr, $aktivna) {
        if ($usluge_proizvodi == 1) {
            /* USLUGE */
            ?>
            <ul>
                <?php
                foreach ($categoriesArr as $cData) {
                    ?>
                    <li>
                        <a <?= ($cData->resource_id == $aktivna) ? " class='active' " : ""; ?> href="/usluge/<?= $cData->url; ?>/<?= $cData->resource_id; ?>" title="<?= $cData->title; ?>"><?= $cData->title; ?></a>
                    </li>
                    <?php
                }
                ?>

            </ul>
            <?php
        } else {
            /* PROIZVODI */
        }
    }

    function printFrontMenuLong($menuResId, $parentId, $active = "", $lang, $activeClass = "active", $level = 1) {

        //Cupam koji mi je menu_id za ovaj jezik i za resource_id
        $menuQuery = Database::execQuery("SELECT id FROM menus WHERE resource_id = '$menuResId' AND lang_id = '$lang'");
        $dataMenu = mysqli_fetch_array($menuQuery);

        //Pravim kolekciju linkova koje treba da prikazujem
        $menuItemsCollection = new Collection("menu_items");
        $menuItems = $menuItemsCollection->getCollection("WHERE menu_id = '$dataMenu[id]' AND parent_id = '$parentId' ORDER BY ordering");

        //Pitam koji je level, ako je vece od jedan, onda ul treba da stoji van foreach petlje 
        if ($level > 1)
            echo "<ul>";

        //Postavljam brojac, da bi mogao da znam koju klasu da stampam, za ovo drak ili ne
        $counter = 1;
        //Krecem u foreach da stamapam menije
        foreach ($menuItems as $menuItem) {
            //Ako je level jedan, onda ul treba da se nalazi u foreach petlji
            if ($level == 1)
                echo "<ul>";

            //Postavljam klasu na prazan string
            $class = "";
            //Ako je link aktivan, onda mu dodajem activeClass-u, da se u meniju vidi na kom sam linku trenutno
            if ($active == $menuItem->url) {
                $class .= "$activeClass ";
            }

            //Ako je counter deljiv sa 2, onda ubacujemo na li i klasu dark
            if ($counter % 2 == 0) {
                $class .= "dark ";
            }

            //Ako nije klasa prazna, onda dodajem artribut class i to sve ide u li atribut
            if ($class != "") {
                $class = "class='" . $class . "'";
            }

            //Ide stampanje li-ja
            ?>
            <li <?= $class; ?>><a title="<?= $menuItem->title; ?>" href="<?= $menuItem->url; ?>" <?php if ($menuItem->open_type == 2) echo 'target="_blank"'; ?>>&gt; <?= $menuItem->title; ?></a>
                <?php
                //proveravam da li ovaj link ispod sebe ima dece, ako ima rekurzivno pozovem istu funkciju da stampa njegove podlinkove
                if (Database::numRows("SELECT * FROM menu_items WHERE menu_id = '$dataMenu[id]' AND parent_id = '$menuItem->id'") > 0) {
                    //Parametri su mi u principu isti, s tim da parent_id sada je id od trenutnog linka i level povecam za jedan, da brojim u kom sam levelu.
                    $this->printFrontMenuLong($menuResId, $menuItem->id, $active, $lang, $activeClass, $level + 1);
                }
                ?>
            </li>
            <?php
            //Povecavam counter za foreach 
            $counter++;

            //Ako je level jedan, onda zatvaram ul u foreach petlji
            if ($level == 1)
                echo "</ul>";
        }
        //Ako je level veci od jedan, onda zatvaram ul van foreach petlje
        if ($level > 1)
            echo "</ul>";
    }

    function printFrontMenu($menuResId, $parentId, $url, $lang, $activeClass = "active", $level = 1) {

        if ($parentId == 0)
            echo "<ul>";

        //Cupam koji mi je menu_id za ovaj jezik i za resource_id
        $menuQuery = Database::execQuery("SELECT id FROM menus WHERE resource_id = '$menuResId' AND lang_id = '$lang'");
        $dataMenu = mysqli_fetch_array($menuQuery);

        //Pravim kolekciju linkova koje treba da prikazujem
        $menuItemsCollection = new Collection("menu_items");
        $menuItems = $menuItemsCollection->getCollection("WHERE menu_id = '$dataMenu[id]' AND parent_id = '$parentId' ORDER BY ordering");

        //Pitam koji je level, ako je vece od jedan, onda ul treba da stoji van foreach petlje 
        if ($level > 1)
            echo "<ul>";

        //Postavljam brojac, da bi mogao da znam koju klasu da stampam, za ovo drak ili ne
        $counter = 1;
        //Krecem u foreach da stamapam menije
        foreach ($menuItems as $menuItem) {
            //Ide stampanje li-ja
            $ispitivac = "/sr/galerija-snova/" . $url;
            if ($menuItem->url == $ispitivac) {
                ?>
                <li><a class="aktivan" title="<?= $menuItem->title; ?>" href="<?= $menuItem->url; ?>" <?php if ($menuItem->open_type == 2) echo 'target="_blank"'; ?>><?= $menuItem->title; ?></a>
                    <?
                    } else if (!$url && $menuItem->url=="/") {
                    ?>
                <li><a class="aktivan" title="<?= $menuItem->title; ?>" href="<?= $menuItem->url; ?>" <?php if ($menuItem->open_type == 2) echo 'target="_blank"'; ?>><?= $menuItem->title; ?></a>
                    <?php
                } else {
                    ?>
                <li><a title="<?= $menuItem->title; ?>" href="<?= $menuItem->url; ?>" <?php if ($menuItem->open_type == 2) echo 'target="_blank"'; ?>><?= $menuItem->title; ?></a>
                    <?php
                }
                ?>

                <?php
                //proveravam da li ovaj link ispod sebe ima dece, ako ima rekurzivno pozovem istu funkciju da stampa njegove podlinkove
                if (Database::numRows("SELECT * FROM menu_items WHERE menu_id = '$dataMenu[id]' AND parent_id = '$menuItem->id'") > 0) {
                    //Parametri su mi u principu isti, s tim da parent_id sada je id od trenutnog linka i level povecam za jedan, da brojim u kom sam levelu.
                    $this->printFrontMenu($menuResId, $menuItem->id, $url, $lang, $activeClass, $level + 1);
                }
                ?>
            </li>
            <?php
            //Povecavam counter za foreach 
            $counter++;
        }
        //Ako je level veci od jedan, onda zatvaram ul van foreach petlje
        if ($level > 1)
            echo "</ul>";

        if ($parentId == 0)
            echo "</ul>";
    }
}
?>