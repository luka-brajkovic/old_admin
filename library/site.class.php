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
    function getFiltersFrontEndOptimized($cat_rid, $server_url, $cat_rid2) {

        $filterHeaders = new Collection("filter_headers");
        $filterValues = new Collection("filter_values");
        $filterJoins = new Collection("filter_joins");
        $categories = new Collection("categories");
        $proizvodi = new Collection("_content_proizvodi");
        $lang = 1;

        $categoriesArr = $categories->getCollection("WHERE resource_id = $cat_rid");
        $catData = $categoriesArr[0];



        $server_url = str_replace($catData->url, $catData->url . "#", $server_url);
        if (substr($server_url, -1) == "/") {
            $server_url = substr($server_url, 0, -1);
        }

        $addonUrlEx = explode("#", $server_url);

        $filterHeadersArr = $filterHeaders->getCollection("WHERE cat_resource_id = $cat_rid ORDER BY ordering");
        if (count($filterHeadersArr) > 0) {
            /*
              echo "<h5>Kategorie Filter</h5>"; */
            $joinsCat = "";
            $whereCat = "";

            if ($cat_rid != $cat_rid2) {
                $joinsCat .= " JOIN categories_content cc ON cc.content_resource_id = cp.resource_id";
                $whereCat .= " AND cc.category_resource_id = '$cat_rid2'";
            }

            foreach ($filterHeadersArr as $fh) {
                $counter = 0;

                $filValue = mysql_query("SELECT fv.* FROM filter_values fv "
                        . " LEFT JOIN filter_joins fj ON fj.fh_id = fv.fh_id "
                        . " LEFT JOIN _content_proizvodi cp ON cp.resource_id = fj.product_rid"
                        . " WHERE (cp.status = 1 OR cp.master_status = 'Active') AND fv.fh_id = $fh->id GROUP BY (fv.title) ORDER BY CAST(fv.title AS UNSIGNED), fv.title ASC ") or die(mysql_error());
                $numbPerFilter = mysql_num_rows($filValue);
                if (mysql_num_rows($filValue)) {
                    echo "<div class='filter_group'><h5>$fh->title</h5>";
                    echo "<ul class='check' data-id='" . $fh->id . "'>";
                } else {
                    echo "<div class='filter_group'>";
                    echo "<ul>";
                }
                switch ($fh->show) {
                    case '1':


                        while ($value = mysql_fetch_object($filValue)) {
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
                            $countProds = 0;

                            $proizvodiArr = mysql_query("
								SELECT cp.id FROM _content_proizvodi cp 
                                JOIN filter_joins fj ON cp.resource_id = fj.product_rid 
								$joinsCat
								WHERE fj.fv_id LIKE '%$value->id%' AND fj.fh_id = $fh->id AND (cp.status = 1 OR cp.master_status = 'Active') $whereCat ") or die(mysql_error());
                            $countProds = mysql_num_rows($proizvodiArr);
                             

                            $urlExplosion = explode("&", $server_url);

                            if($countProds > 0){
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
                                echo "<label>" . htmlspecialchars_decode($value->title) . " ($countProds)</label>"; /* AKO HOCES DA UBACIS KOLIKO PROIZVODA IMA DODAJ ($countProds)  */
                                echo "<input type='checkbox' name='" . $fh->url . "' value='$value->id' /></li>";
                                if($counter==3){ ?>
                                    <span id="doNotShowFilter-<?= $fh->url; ?>" class="doNotShowFilter">
                                <?php }elseif($numbPerFilter - 1==$counter && $counter > 2){ ?>
                                    </span>
                                    <a id='open-<?= $fh->url; ?>' class="open" onclick="showMoreFilters('<?= $fh->url; ?>')" href="javascript:">Prikaži sve</a>
                                    <i class="fa fa-angle-down filterArrowDown-<?= $fh->url; ?> filterArrowDown"></i><i class="fa fa-angle-up filterArrowUp-<?= $fh->url; ?> filterArrowUp"></i>
                                <?php }                            
                                $counter++;
                            }
                        }

                        break;

                    case '2':
                        $filterValuesArr = $filterValues->getCollection("WHERE fh_id = $fh->id ");
                        $currentUrl = $addonUrlEx[1];

                        if (count($filterValuesArr) > 0) {
                            echo "<select onchange=\"var thisValue = $(this).val(); submitFilters(thisValue,$fh->show)\" name='" . $fh->id . "_" . $lang . "' id='" . $fh->id . "_" . $lang . "'>";

                            echo "<option value='" . $fh->url . "_all'>Izaberi</option>";

                            foreach ($filterValuesArr as $value) {
                                $proizvodiArr = $proizvodi->getCollectionCustom("
										SELECT cp.id FROM _content_proizvodi cp JOIN filter_joins fj ON cp.resource_id = fj.product_rid 
										WHERE fj.fv_id LIKE '%$value->id--,%' AND fj.fh_id = $fh->id AND (cp.status = 1 OR cp.master_status = 'Active') AND cp.lang = $lang ");
                                $countProds = count($proizvodiArr);

                                if (strpos($currentUrl, "/" . $fh->url . "=") !== false) {

                                    $currentExplode = explode("/" . $fh->url . "=", $currentUrl);
                                    $currentEx1 = explode("/", $currentExplode[1]);
                                    $currentEx = $currentEx1[0];
                                    if (strpos($currentEx, $value->url) !== false) {
                                        echo "<option selected = 'selected' value='" . $fh->url . "_" . $value->url . "'>$value->title ($countProds)</option>";
                                    } else {
                                        echo "<option value='" . $fh->url . "_" . $value->url . "'>$value->title ($countProds - $currentEx)</option>";
                                    }
                                } else {
                                    echo "<option value='" . $fh->url . "_" . $value->url . "'>$value->title ($countProds)</option>";
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
        } else {
            /*
              $categoriesArr = $categories->getCollection("WHERE lang = $lang AND resource_id = $cat_rid");
              $catData = $categoriesArr[0];
              $categoriesArr = $categories->getCollection("WHERE lang = $lang AND resource_id = $catData->parent_id");
              $catParent = $categoriesArr[0];
              if($catParent->id!='') {
              return $this->getFiltersFrontEnd($catParent->resource_id, $lang, $server_url, $catData->resource_id);
              } else {
              return "";
              } */
        }
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

    function printGallery($gallery, $title) {

        $relativePath = substr($gallery, 1);

        $relativePathThumbs = $relativePath . "thumbs";
        $relativePathBigs = $relativePath . "bigs";



        if (is_dir($relativePathThumbs)) {
            if ($handle = opendir($relativePathThumbs)) {
                ?>
                <div class="galery">
                    <?php
                    while (false !== ($entry = readdir($handle))) {
                        if ($entry != "." && $entry != "..") {
                            ?>
                            <a title="<?= $title; ?>" data-fancybox-group="gallery" href="<?= $gallery . "bigs/$entry"; ?>" class="fancybox ">
                                <img alt="<?= $title; ?>" src="<?= $gallery . "thumbs/$entry"; ?>">
                            </a>
                            <?php
                        }
                    }
                    closedir($handle);
                    ?>
                </div>
                <?php
            }
        }
    }

    function printFrontMenuLong($menuResId, $parentId, $active = "", $lang, $activeClass = "active", $level = 1) {

        //Cupam koji mi je menu_id za ovaj jezik i za resource_id
        $menuQuery = Database::execQuery("SELECT id FROM menus WHERE resource_id = '$menuResId' AND lang_id = '$lang'");
        $dataMenu = mysql_fetch_array($menuQuery);

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
        $dataMenu = mysql_fetch_array($menuQuery);

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