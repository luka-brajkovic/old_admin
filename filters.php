<div class="filters">
    <form action="" method="get">
        <?php
        if ($cat_last_url != '') {

            $parentIDMid = $catMiddleData->resource_id;
            $numcat = $db->execQuery("SELECT * FROM categories WHERE parent_id = $parentIDMid  ORDER BY title");
            if ($numcat > 0) {
                ?>
                <ul id="category_filter" class="check ">
                    <h5>Kategorije:</h5>
                    <?php
                    $catFromUrl = isset($_GET['cat']) ? $_GET['cat'] : array();
                    $queryFilterCat = $db->execQuery("SELECT * FROM categories WHERE status = 1 AND parent_id = $parentIDMid ORDER BY title");
                    while ($dataCatfilter = mysql_fetch_array($queryFilterCat)) {
                        $active = in_array($dataCatfilter['resource_id'], $catFromUrl) ? "active" : "";
                        ?>
                        <?php
                        if ($dataCatfilter[resource_id] == $catLastData->resource_id) {
                            $active = "active";
                        }
                        ?>
                        <li>
                            <span class="checkClick cat <?= $active; ?>">
                                <i class="fa fa-square inac"></i>
                                <i class="fa fa-check-square acti"></i>
                            </span>
                            <label><?= $dataCatfilter['title']; ?></label>
                            <input <?= ($dataCatfilter[resource_id] == $catLastData->resource_id) ? "checked='checked'" : ""; ?> type="checkbox" name="cat[]" value="<?= $dataCatfilter['resource_id']; ?>">
                        </li>
                        <?php
                    }
                    ?>
                </ul>
                <?php
            }
        } else {

            $parentIDMid = $catMiddleData->resource_id;
            $sqlCategory = "SELECT * FROM categories WHERE parent_id = $parentIDMid ORDER BY title";

            $numcat = $db->numRows($sqlCategory);
            if ($numcat > 0) {
                $numcat = $db->execQuery($sqlCategory);
                ?>
                <ul class="check">
                    <h5>Kategorije:</h5>
                    <?php
                    $catFromUrl = isset($_GET['cat']) ? $_GET['cat'] : array();
                    $queryFilterCat = $db->execQuery("SELECT * FROM categories WHERE parent_id = $parentIDMid");
                    while ($dataCatfilter = mysql_fetch_array($queryFilterCat)) {
                        $active = in_array($dataCatfilter['resource_id'], $catFromUrl) ? "active" : "";
                        ?>
                        <li>
                            <span class="checkClick cat <?= $active; ?>">
                                <i class="fa fa-square inac"></i>
                                <i class="fa fa-check-square acti"></i>
                            </span>
                            <label><?= $dataCatfilter['title']; ?></label>
                            <input type="checkbox" name="cat[]" value="<?= $dataCatfilter['resource_id']; ?>">
                        </li>
                        <?php
                    }
                    ?>
                </ul>
                <?php
            }
            if (mysql_num_rows($preporukaQuery) > 1 && $rangeSpanMin < $rangeSpanMax) {
                ?>

                <fieldset>
                    <h5>Raspon cene:</h5>
                    <?php
                    $priceData = mysql_fetch_object($preporukaQueryPrice);
                    $minPrice = round(isset($_GET['min_cena']) ? $_GET['min_cena'] : $rangeSpanMin);
                    $maxPrice = round(isset($_GET['max_cena']) ? $_GET['max_cena'] : $rangeSpanMax);
                    ?>
                    <input type="hidden" class="range-slider" value="<?= $minPrice . "," . $maxPrice; ?>" />
                </fieldset>
            <?php } ?>
            <?php if (mysql_num_rows($preporukaQueryBrand) > 0) {
                
                ?>  
                <ul class="check">
                    <h5>Robna marka:</h5>

                    <?php
                    $brandFromUrl = isset($_GET['brand']) ? $_GET['brand'] : array();
                    while ($dataBrandCat = mysql_fetch_array($preporukaQueryBrand)) {
                        $active = in_array($dataBrandCat['b_rid'], $brandFromUrl) ? "active" : "";
                        if ($dataBrandCat['b_title'] != '') {
                            
                            $brendArra = mysql_query(""
                        . "SELECT cp.id FROM _content_proizvodi cp "
                        . " LEFT JOIN _content_brend b ON cp.brand = b.resource_id "
                        . " LEFT JOIN categories_content cc ON cc.content_resource_id = cp.resource_id "
                        . " LEFT JOIN categories c3 ON cc.category_resource_id = c3.resource_id "
                        . " LEFT JOIN categories c2 ON c3.parent_id = c2.resource_id "
                        . " WHERE "
                        . " (c3.resource_id = $catMiddleData->resource_id OR c2.resource_id = $catMiddleData->resource_id ) "
                        . " AND (cp.status = 1 OR cp.master_status = 'Active') AND cp.lang = $currentLanguage AND b.resource_id = ".$dataBrandCat['b_rid']." GROUP BY (cp.resource_id)") or die(mysql_error());
                            $brendSount = mysql_num_rows($brendArra);
                            ?>
                            <li>
                                <span class="checkClick brand <?= $active; ?>">
                                    <i class="fa fa-square inac"></i>
                                    <i class="fa fa-check-square acti"></i>
                                </span>
                                <label><?= $dataBrandCat['b_title']." (".$brendSount.")"; ?></label>
                                <input type="checkbox" class="brand-check" name="brand[]" value="<?= $dataBrandCat['b_rid']; ?>">
                            </li>
                            <?php
                        }
                    }
                    ?>

                </ul>
                <?php
            }
        }
        /* HVATAM FILTERE ODOZGO */
        $site->getFiltersFrontEndOptimized($catMiddleData->resource_id, $REQUEST, $catMiddleData->resource_id);
        ?>
    </form>
</div>