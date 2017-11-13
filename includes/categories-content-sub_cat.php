<h6>Podgrupe kategorije <?= strtolower($catMasterData->title); ?></h6>
<div class="innerCat margin-vertical">
    <ul>
        <?php
        $cats = mysqli_query($conn,"SELECT c.*, c2.title as cat_title, c2.url as cat_url FROM categories c "
                . " LEFT JOIN categories c2 ON c.resource_id = c2.parent_id "
                . " WHERE c.status = 1 AND c2.status = 1 AND c.parent_id = $catMasterData->resource_id ORDER BY c.ordering, c2.ordering");

        $arrayFirstLevel = array();
        $otvoreno = false;
        $maloOtvoreno = false;
        $currentMasterRid = "";
        $currentSubUrl = "";
        $currentSubTitle = '';
        $currentUpperUrl = "";
        while ($catSub = mysqli_fetch_object($cats)) {
            if ($otvoreno && $currentMasterRid != $catSub->resource_id) {
                if ($maloOtvoreno) {

                    echo "</ul>";
                    $maloOtvoreno = false;
                }
                echo "</li>";
                $otvoreno = false;
            }
            if (!$otvoreno && !in_array($catSub->resource_id, $arrayFirstLevel)) {
                echo "<li>";
                array_push($arrayFirstLevel, $catSub->resource_id);
                $otvoreno = true;
                $currentMasterRid = $catSub->resource_id;
                ?>
                <a class="innerCatMaster" href="/<?= $catMasterData->url; ?>/<?= $catSub->url; ?>" title="<?= $catSub->title; ?>"><?= $catSub->title; ?></a>
                <?php
                if ($catSub->cat_url != '' && !$maloOtvoreno) {
                    echo "<ul>";
                    $maloOtvoreno = true;
                }
            }
            ?>
            <li>
                <a href="/<?= $catMasterData->url; ?>/<?= $catSub->url; ?>/<?= $catSub->cat_url; ?>" title="<?= $catSub->cat_title; ?>"><?= $catSub->cat_title; ?></a>
            </li>
            <?php
        }
        if ($otvoreno) {

            echo "</ul>";
        }
        ?>


</div>