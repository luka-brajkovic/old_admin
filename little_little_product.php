<div class="littleLittleProduct">
    <div class="littleLittleProductAll clear">
        <figure class="third left">
            <a href="/<?= $item->master_cat_url . "/" . $item->sub_cat_url . "/" . $item->url . "/" . $item->resource_id; ?>" title='<?= htmlspecialchars_decode($item->title); ?>'>
                <?php if (is_file("uploads/uploaded_pictures/_content_proizvodi/" . $dimUrlLit . "/" . $item->product_image)) { ?>
                    <img class="transition" src="/uploads/uploaded_pictures/_content_proizvodi/<?= $dimUrlLit . "/" . $item->product_image; ?>" alt='<?= htmlspecialchars_decode($item->title); ?> slika' title='<?= htmlspecialchars_decode($item->title); ?>' />
                <?php } elseif (is_file("uploads/uploaded_pictures/_content_proizvodi/" . $dimUrlLitSecund . "/" . $item->product_image)) { ?>
                    <img class="transition" src="/uploads/uploaded_pictures/_content_proizvodi/<?= $dimUrlLitSecund . "/" . $item->product_image; ?>" alt='<?= htmlspecialchars_decode($item->title); ?> slika' title='<?= htmlspecialchars_decode($item->title); ?>' />
                <?php } else { ?>
                    <img class="transition" src="/images/no-image.jpg" alt='<?= htmlspecialchars_decode($item->title); ?> slika' title='<?= htmlspecialchars_decode($item->title); ?>' />
                <?php } ?>
            </a>
        </figure>
        <div class="third-x2 right">
            <div class="littleLittleDesc quarterMarginLB">
                <a href="/<?= $item->master_cat_url . "/" . $item->sub_cat_url . "/" . $item->url . "/" . $item->resource_id; ?>" title='<?= htmlspecialchars_decode($item->title); ?>'><?= $item->b_title . " " . htmlspecialchars_decode($item->title); ?></a>
                <?php if ($item->old_price != '' && $item->old_price > 0) { ?> <span><?= number_format($item->old_price, 2, ",", "."); ?></span><?php } ?>
                <strong<?php
                if (($item->old_price != '' && $item->old_price > 0) || $item->akcija == "Da") {
                    echo " class='popustPrice'";
                }
                ?>><?= ($item->master_price > 0) ? number_format($item->master_price, 2, ",", ".") : number_format($item->price, 2, ",", "."); ?> rsd</strong>
            </div>
        </div>
    </div>
</div>