<div class="catbestHolder">
    <?php
    $catBestQ = mysqli_query($conn,"SELECT * FROM categories WHERE parent_id = $catMasterData->resource_id AND status = 1 ORDER BY ISNULL(istaknuta_pored_slidera), istaknuta_pored_slidera ASC, ordering ASC LIMIT 3");
    while ($catBestData = mysqli_fetch_object($catBestQ)) {
        ?>
        <div class="catbestInner margin-vertical">
            <a href="/<?= $catMasterData->url; ?>/<?= $catBestData->url; ?>" title="<?= $catBestData->title; ?>">
                <strong><?= $catBestData->title; ?></strong>
                <p><?= $f->stripString($catBestData->desc_seo, 7); ?></p>
                <?php
                if (is_file("uploads/uploaded_files/categories/$catBestData->mala_slika")) {
                    echo "<img src='/uploads/uploaded_files/categories/$catBestData->mala_slika' title='$catBestData->title' alt='$catBestData->title' />";
                }
                ?>
            </a>
        </div>
        <?php
    }
    ?>


</div>