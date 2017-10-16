
<ul class="list categoriesList quarterMarginMasterR">
    <?php
    $proizvodi_na_akciji = mysql_query("SELECT cp.*,c1.title as cat_title,c1.url as cat_url FROM _content_proizvodi cp"
            . " LEFT JOIN categories_content cc ON cp.resource_id = cc.content_resource_id "
            . " LEFT JOIN categories c2 ON cc.category_resource_id = c2.resource_id "
            . " LEFT JOIN categories c1 ON c2.parent_id = c1.resource_id "
            . " WHERE (cp.status = 1 OR cp.master_status = 'Active') AND c1.parent_id = 0 AND (cp.akcija = 'Da' OR (cp.price < cp.old_price OR cp.master_price < cp.old_price)) GROUP BY c1.resource_id ORDER BY c1.title ") or die(mysql_error());
    if (mysql_num_rows($proizvodi_na_akciji) > 0) {
        ?>
        <li>
            <a  href="/proizvodi-na-akciji" class="<?= ($cat_master_url == "proizvodi-na-akciji") ? "active" : ""; ?> have_sub akcija" title="Proizvodi na akciji">Proizvodi na akciji</a>
        </li>
    <?php } ?>
    <?php
    $items = mysql_query("SELECT * FROM categories WHERE status = 1 AND parent_id = '0' AND content_type_id = 72 ORDER BY ordering");
    $counter3 = 0;
    $counter4 = 0;
    while ($item = mysql_fetch_object($items)) {
        $subItems = mysql_query("SELECT * FROM categories WHERE status = 1 AND parent_id = '$item->resource_id' AND content_type_id = '72' ORDER BY ordering");
        ?>
        <li>
            <?php if (mysql_num_rows($subItems) > 0) { ?>
                <a title="<?= $item->title; ?>" href="/<?= $item->url; ?>" class="<?= ($catMasterDataURL == $item->url) ? "active" : ""; ?> have_sub"><?= $item->title; ?></a>
                <ul class="sub">
                    <?php
                    while ($subItem = mysql_fetch_object($subItems)) {
                        $checkLast = mysql_query("SELECT c3.resource_id as last_cat_rid FROM categories c3 "
                                . " LEFT JOIN categories_content cc ON c3.resource_id = cc.category_resource_id "
                                . " LEFT JOIN _content_proizvodi cp ON cp.resource_id = cc.content_resource_id "
                                . " LEFT JOIN categories c2 ON c2.resource_id = c3.parent_id "
                                . " WHERE (cp.status = 1 OR cp.master_status = 'Active') AND c2.resource_id = '$subItem->resource_id' GROUP BY (c3.resource_id) LIMIT 1") or die(mysql_error());
                        if (mysql_num_rows($checkLast) > 0) {
                            ?>
                            <li>
                                <i class="fa fa-chevron-right"></i>
                                <a title="<?= $subItem->title; ?>" href="/<?= $item->url; ?>/<?= $subItem->url; ?>"  class="<?= ($catMiddleDataURL == $subItem->url) ? "active" : ""; ?>"><?= $subItem->title; ?></a>
                            </li>
                            <?php
                        } else {
                            $subCatAlls = mysql_query("SELECT c2.resource_id as cat_rid FROM categories c2 "
                                    . " LEFT JOIN categories_content cc ON c2.resource_id = cc.category_resource_id "
                                    . " LEFT JOIN _content_proizvodi cp ON cp.resource_id = cc.content_resource_id "
                                    . " WHERE (cp.status = 1 OR cp.master_status = 'Active') AND c2.resource_id = '$subItem->resource_id' GROUP BY (c2.resource_id) LIMIT 1") or die(mysql_error());
                            if (mysql_num_rows($subCatAlls) > 0) {
                                ?>
                                <li>
                                    <i class="fa fa-chevron-right"></i>
                                    <a title="<?= $subItem->title; ?>" href="/<?= $item->url; ?>/<?= $subItem->url; ?>"  class="<?= ($catMiddleDataURL == $subItem->url) ? "active" : ""; ?>"><?= $subItem->title; ?></a>
                                </li>
                                <?php
                            }
                        }
                        ?>
                    <?php } ?>
                </ul>   
                <?php
            } else {
                ?>
                <a  title="<?= $item->title; ?>" href="/<?= $item->url; ?>" class="padRi <?= ($catMasterDataURL == $item->url) ? "active" : ""; ?>"><?= $item->title; ?></a>
            <?php } ?>
        </li>        
    <?php } ?>	   
    <li>
        <a class="sve" href="/sve-kategorije" title="Sve kategorije">Sve kategorije</a>
    </li>
</ul>