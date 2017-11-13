<figure class="center brenfLogoSingle">
    <?php
    if (is_file("uploads/uploaded_pictures/_content_brand/140x60/" . $brandObj->logo)) {
        ?>
        <img class="trueImage" src="/uploads/uploaded_pictures/_content_brand/140x60/<?= $brandObj->logo; ?>" alt="<?= $proizvod->title; ?>" title="<?= $brandObj->title; ?>">
    <?php } else { ?>
        <img class="logoImage" src="/images/logo.jpg" alt="<?= $brandObj->title; ?>" title="<?= $brandObj->title; ?>">
    <?php } ?>
</figure>
<div class="filters">
    <form action="" method="get">
        <ul class="check">
            <h5>Kategorije:</h5>
            <?php
            $catFromUrl = isset($_GET['cat']) ? $_GET['cat'] : array();
            $queryFilterCat = $db->execQuery($sqlCat);
            while ($dataCatfilter = mysqli_fetch_array($queryFilterCat)) {
                $active = in_array($dataCatfilter['resource_id'], $catFromUrl) ? "active" : "";
                
                $brendArra = mysqli_query($conn,""
                        . "SELECT cp.id FROM _content_products cp "
                        . " LEFT JOIN _content_brand b ON cp.brand = b.resource_id "
                        . " LEFT JOIN categories_content cc ON cc.content_resource_id = cp.resource_id "
                        . " LEFT JOIN categories c3 ON cc.category_resource_id = c3.resource_id "
                        . " LEFT JOIN categories c2 ON c3.parent_id = c2.resource_id "
                        . " WHERE "
                        . " (c3.resource_id = " . $dataCatfilter['resource_id'] . " OR c2.resource_id = " . $dataCatfilter['resource_id'] . " ) "
                        . " AND cp.status = 1 AND cp.lang = $currentLanguage AND b.resource_id = " . $brandObj->resource_id . " GROUP BY (cp.resource_id)") or die(mysqli_error($conn));
                $brendSount = mysqli_num_rows($brendArra);
                if ($brendSount > 0) {
                ?>
                <li>
                    <span class="checkClick cat <?= $active; ?>">
                        <i class="fa fa-square inac"></i>
                        <i class="fa fa-check-square acti"></i>
                    </span>
                    <label><?= $dataCatfilter['master_cat_title'] . " - " . $dataCatfilter['title']." (".$brendSount.")"; ?></label>
                    <input type="checkbox" name="cat[]" value="<?= $dataCatfilter['resource_id']; ?>">
                </li>
                <?php
                }
            }
            ?>
        </ul>      
    </form>
</div>