<div class="container">
    <ul class="pagePosition clear" itemtype="http://data-vocabulary.org/BreadcrumbList">
        <li>
            <span>Vi ste ovde:</span>
        </li>
        <li itemprop="itemListElement" itemscope="itemscope" itemtype="http://schema.org/ListItem">
            <a href="/" title="<?= $configSiteTitle; ?>" itemprop="url">
                <span itemprop="name">Početna</span>
            </a>
        </li>
        <li>
            <span>Sve kategorije</span>
        </li>

    </ul>
    <h1>Sve kategorije</h1>
    <div class="allCatsCont row">
        <?php
        $proizvodi_akciji = mysql_query("SELECT cp.*,c1.title as cat_title,c1.url as cat_url FROM _content_proizvodi cp"
                . " LEFT JOIN categories_content cc ON cp.resource_id = cc.content_resource_id "
                . " LEFT JOIN categories c2 ON cc.category_resource_id = c2.resource_id "
                . " LEFT JOIN categories c1 ON c2.parent_id = c1.resource_id "
                . " WHERE (cp.status = 1 OR cp.master_status = 'Active') AND c1.parent_id = 0 AND (cp.akcija = 'Da' OR (cp.price < cp.old_price OR cp.master_price < cp.old_price)) GROUP BY c1.resource_id ORDER BY c1.ordering ") or die(mysql_error());
        if (mysql_num_rows($proizvodi_akciji) > 0) {
            ?>
            <div class="quarter margin-vertical">
                <a href="/proizvodi-na-akciji" title="Proizvodi na akciji"><h4 class="transition">Proizvodi na akciji</h4></a>
                <ul>
                    <?php
                    while ($itemAkti = mysql_fetch_object($proizvodi_akciji)) {
                        ?>
                        <li>
                            <a href="/<?= $itemAkti->cat_url; ?>" title="<?= $itemAkti->cat_title; ?>"><?= $itemAkti->cat_title; ?></a>
                        </li>
                    <?php } ?>
                </ul>  
            </div>
        <?php } ?>
        <?php
        $catsQ = mysql_query("SELECT * FROM categories WHERE parent_id = 0 AND lang = 1 AND content_type_id = 72 AND status = 1 ORDER BY ordering");
        while ($dataCat = mysql_fetch_object($catsQ)) {
            ?>
            <div class="quarter margin-vertical">
                <a href="/<?= $dataCat->url; ?>" title="<?= $dataCat->title; ?>"><h4 class="transition"><?= $dataCat->title; ?></h3></a>
                <ul>
                    <?php
                    $subCatsQ = mysql_query("SELECT * FROM categories WHERE parent_id = $dataCat->resource_id AND lang = 1 AND content_type_id = 72 AND status = 1 ORDER BY ordering");
                    while ($subCat = mysql_fetch_object($subCatsQ)) {

                        $checkLast = mysql_query("SELECT c2.resource_id as sec_cat_rid, c3.resource_id as last_cat_rid FROM categories c3 "
                                . " LEFT JOIN categories_content cc ON c3.resource_id = cc.category_resource_id "
                                . " LEFT JOIN _content_proizvodi cp ON cp.resource_id = cc.content_resource_id "
                                . " LEFT JOIN categories c2 ON c2.resource_id = c3.parent_id "
                                . " WHERE (cp.status = 1 OR cp.master_status = 'Active') AND c2.resource_id = '$subCat->resource_id' GROUP BY (c3.resource_id) LIMIT 1") or die(mysql_error());
                        $checkLast = mysql_fetch_object($checkLast);
                        if ($checkLast->last_cat_rid != "") {
                            ?>
                            <li>
                                <a href="/<?= $dataCat->url . "/" . $subCat->url; ?>" title="<?= $subCat->title; ?>"><?= $subCat->title; ?></a>
                            </li>
                            <?php
                            $listLaster = mysql_query("SELECT c3.title, c3.url FROM categories c3 "
                                    . " LEFT JOIN categories_content cc ON c3.resource_id = cc.category_resource_id "
                                    . " LEFT JOIN _content_proizvodi cp ON cp.resource_id = cc.content_resource_id "
                                    . " LEFT JOIN categories c2 ON c2.resource_id = c3.parent_id "
                                    . " WHERE (cp.status = 1 OR cp.master_status = 'Active') AND c2.resource_id = '$subCat->resource_id' GROUP BY (c3.resource_id)") or die(mysql_error());
                            while ($listLast = mysql_fetch_object($listLaster)) {
                                ?>
                                <li>
                                    <a href="/<?= $dataCat->url . "/" . $subCat->url . "/" . $listLast->url; ?>" title="<?= $subCat->title; ?>"><?= $subCat->title . " - " . $listLast->title; ?></a>
                                </li>
                            <?php
                            }
                        } else {
                            $subCatAlls = mysql_query("SELECT c2.resource_id as cat_rid FROM categories c2 "
                                    . " LEFT JOIN categories_content cc ON c2.resource_id = cc.category_resource_id "
                                    . " LEFT JOIN _content_proizvodi cp ON cp.resource_id = cc.content_resource_id "
                                    . " WHERE (cp.status = 1 OR cp.master_status = 'Active') AND c2.resource_id = '$subCat->resource_id' GROUP BY (c2.resource_id) LIMIT 1") or die(mysql_error());
                            $subcicCat = mysql_fetch_object($subCatAlls);
                            if ($subcicCat->cat_rid != "") {
                                ?>
                                <li>
                                    <a href="/<?= $dataCat->url . "/" . $subCat->url; ?>" title="<?= $subCat->title; ?>"><?= $subCat->title; ?></a>
                                </li>
                                <?php
                            }
                        }
                    }
                    ?>
                </ul>
            </div>
            <?php
        }
        ?>
    </div>
</div>