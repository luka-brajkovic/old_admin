<div class="filters">
    <form id="formicei" action="" method="get">
        <?php
        if ($cat_last_url != '') {

            $parentIDMid = $catMiddleData->resource_id;
            $numcat = $db->execQuery("SELECT * FROM categories WHERE parent_id = $parentIDMid ORDER BY title");
            if ($numcat > 0) {
                ?>
                <ul id="category_filter" class="check ">
                    <h5>Kategorije:</h5>
                    <?php
                    $catFromUrl = isset($_GET['cat']) ? $_GET['cat'] : array();
                    $queryFilterCat = $db->execQuery("SELECT * FROM categories WHERE status = 1 AND parent_id = $parentIDMid ORDER BY title");
                    while ($dataCatfilter = mysqli_fetch_array($queryFilterCat)) {
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
                    while ($dataCatfilter = mysqli_fetch_array($queryFilterCat)) {
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
            ?>
            <fieldset class="priceRangeFech">
                <h5>Raspon cene (rsd):</h5>
                <input id="minPrice" type="number" value="<?= ($getMinPrice > 1 && $getMinPrice != "") ? $getMinPrice:""; ?>" name="min_cena" placeholder="od <?= round($rangeSpanMin); ?>">
                <input id="maxPrice" type="number" value="<?= ($getMaxPrice > 1 && $getMaxPrice != "") ? $getMaxPrice : ""; ?>" name="max_cena" placeholder="do <?= round($rangeSpanMax); ?>">
                <a id="priceChange" class="transition">Primeni</a>
            </fieldset>
            <?php if (mysqli_num_rows($preporukaQueryBrand) > 0) {
                ?>  
                <ul class="check">
                    <h5>Robna marka:</h5>

                    <?php
                    $brandFromUrl = isset($_GET['brand']) ? $_GET['brand'] : array();
                    while ($dataBrandCat = mysqli_fetch_array($preporukaQueryBrand)) {
                        $active = in_array($dataBrandCat['b_rid'], $brandFromUrl) ? "active" : "";
                        if ($dataBrandCat['b_title'] != '') {

                            $brendArra = mysqli_query($conn,""
                                    . "SELECT cp.id FROM _content_products cp "
                                    . " JOIN _content_brand b ON cp.brand = b.resource_id "
                                    . " JOIN categories_content cc ON cc.content_resource_id = cp.resource_id "
                                    . " JOIN categories c3 ON cc.category_resource_id = c3.resource_id "
                                    . " JOIN categories c2 ON c3.parent_id = c2.resource_id "
                                    . " WHERE "
                                    . " (c3.resource_id = $catMiddleData->resource_id OR c2.resource_id = $catMiddleData->resource_id ) "
                                    . " AND (cp.status = 1 OR cp.master_status = 'Active') AND cp.lang = $currentLanguage AND b.resource_id = " . $dataBrandCat['b_rid']) or die(mysqli_error($conn));
                            $brendSount = mysqli_num_rows($brendArra);
                            ?>
                            <li>
                                <span class="checkClick brand <?= $active; ?>">
                                    <i class="fa fa-square inac"></i>
                                    <i class="fa fa-check-square acti"></i>
                                </span>
                                <label><?= $dataBrandCat['b_title'] . " (" . $brendSount . ")"; ?></label>
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
        $site->getFiltersFrontEndOptimized($catMiddleData->resource_id, $REQUEST);
        ?>
    </form>
</div>