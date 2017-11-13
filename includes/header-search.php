<div class="searchable left">
    <div class="headerSearch">
        <form class="clear" action="/pretraga" method="get">
            <span class="first left">
                <input type="text" name="pretraga_po" value="<?php echo($pretraga) ? $pretraga : ""; ?>" placeholder="Unesite termin za pretragu..." />
            </span>
            <span class="secund left">  
                <select name='kategorija'>
                    <option value="" >Sve kategorije</option>
                    <?php
                    $allCats = mysqli_query($conn,"SELECT * FROM categories WHERE status = 1 AND parent_id = 0 AND lang = $currentLanguage ORDER BY ordering") or die(mysqli_error($conn));
                    while ($catSearch = mysqli_fetch_object($allCats)) {
                        if (!isset($kategorijaSearch)) {
                            $kategorijaSearch = "";
                        }
                        ?>
                        <option value='<?= $catSearch->resource_id; ?>' <?= ($kategorijaSearch == $catSearch->resource_id) ? "selected" : ""; ?>><?= $catSearch->title; ?></option>
                        <?php
                    }
                    ?>
                </select>
                <i class="fa fa-angle-down"></i>
            </span>
            <input class="right" type="submit" value="" title="Traži" />
        </form>
    </div>
</div>