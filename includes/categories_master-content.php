<div class="container">
	<?php
	$breadcrumbs = array(
		"Sve kategorije" => "/sve-kategorije"
	);
	$f->breadcrumbs($breadcrumbs, $csTitle, $csName);
	?>
    <h1>Sve kategorije</h1>
    <div class="allCatsCont row">
        <?php
        $proizvodi_akciji = mysqli_query($conn,"SELECT cp.*,c1.title as cat_title,c1.url as cat_url FROM _content_products cp"
                . " LEFT JOIN categories_content cc ON cp.resource_id = cc.content_resource_id "
                . " LEFT JOIN categories c2 ON cc.category_resource_id = c2.resource_id "
                . " LEFT JOIN categories c1 ON c2.parent_id = c1.resource_id "
                . " WHERE (cp.status = 1 OR cp.master_status = 'Active') AND c1.parent_id = 0 AND (cp.akcija = 'Da' OR (cp.price < cp.old_price OR cp.master_price < cp.old_price)) GROUP BY c1.resource_id ORDER BY c1.ordering ") or die(mysqli_error($conn));
        if (mysqli_num_rows($proizvodi_akciji) > 0) {
            ?>
            <div class="quarter margin-vertical">
                <a href="/proizvodi-na-akciji" title="Proizvodi na akciji"><h4 class="transition">Proizvodi na akciji</h4></a>
                <ul>
                    <?php
                    while ($itemAkti = mysqli_fetch_object($proizvodi_akciji)) {
                        ?>
                        <li>
                            <a href="/<?= $itemAkti->cat_url; ?>" title="<?= $itemAkti->cat_title; ?>"><?= $itemAkti->cat_title; ?></a>
                        </li>
                    <?php } ?>
                </ul>  
            </div>
        <?php } ?>
        <?php
        $catsQ = mysqli_query($conn,"SELECT * FROM categories WHERE parent_id = 0 AND lang = 1 AND content_type_id = 72 AND status = 1 ORDER BY ordering");
        while ($dataCat = mysqli_fetch_object($catsQ)) {
            ?>
            <div class="quarter margin-vertical">
                <a href="/<?= $dataCat->url; ?>" title="<?= $dataCat->title; ?>"><h4 class="transition"><?= $dataCat->title; ?></h3></a>
                <ul>
                    <?php
                    $subCatsQ = mysqli_query($conn,"SELECT * FROM categories WHERE parent_id = $dataCat->resource_id AND lang = 1 AND content_type_id = 72 AND status = 1 ORDER BY ordering");
                    while ($subCat = mysqli_fetch_object($subCatsQ)) {

                        $checkLast = mysqli_query($conn,"SELECT c2.resource_id as sec_cat_rid, c3.resource_id as last_cat_rid FROM categories c3 "
                                . " LEFT JOIN categories_content cc ON c3.resource_id = cc.category_resource_id "
                                . " LEFT JOIN _content_products cp ON cp.resource_id = cc.content_resource_id "
                                . " LEFT JOIN categories c2 ON c2.resource_id = c3.parent_id "
                                . " WHERE (cp.status = 1 OR cp.master_status = 'Active') AND c2.resource_id = '$subCat->resource_id' GROUP BY (c3.resource_id) LIMIT 1") or die(mysqli_error($conn));
                        $checkLast = mysqli_fetch_object($checkLast);
                        if ($checkLast->last_cat_rid != "") {
                            ?>
                            <li>
                                <a href="/<?= $dataCat->url . "/" . $subCat->url; ?>" title="<?= $subCat->title; ?>"><?= $subCat->title; ?></a>
                            </li>
                            <?php
                            $listLaster = mysqli_query($conn,"SELECT c3.title, c3.url FROM categories c3 "
                                    . " LEFT JOIN categories_content cc ON c3.resource_id = cc.category_resource_id "
                                    . " LEFT JOIN _content_products cp ON cp.resource_id = cc.content_resource_id "
                                    . " LEFT JOIN categories c2 ON c2.resource_id = c3.parent_id "
                                    . " WHERE (cp.status = 1 OR cp.master_status = 'Active') AND c2.resource_id = '$subCat->resource_id' GROUP BY (c3.resource_id)") or die(mysqli_error($conn));
                            while ($listLast = mysqli_fetch_object($listLaster)) {
                                ?>
                                <li>
                                    <a href="/<?= $dataCat->url . "/" . $subCat->url . "/" . $listLast->url; ?>" title="<?= $subCat->title; ?>"><?= $subCat->title . " - " . $listLast->title; ?></a>
                                </li>
                            <?php
                            }
                        } else {
                            $subCatAlls = mysqli_query($conn,"SELECT c2.resource_id as cat_rid FROM categories c2 "
                                    . " LEFT JOIN categories_content cc ON c2.resource_id = cc.category_resource_id "
                                    . " LEFT JOIN _content_products cp ON cp.resource_id = cc.content_resource_id "
                                    . " WHERE (cp.status = 1 OR cp.master_status = 'Active') AND c2.resource_id = '$subCat->resource_id' GROUP BY (c2.resource_id) LIMIT 1") or die(mysqli_error($conn));
                            $subcicCat = mysqli_fetch_object($subCatAlls);
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