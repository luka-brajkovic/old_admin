<div class="<?= ($show_type == "row") ? "list_style" : "quarter margin-vertical"; ?>">
    <div class="littleProduct clear">
        <div>
            <a href="/<?= $item->master_cat_url . "/" . $item->sub_cat_url . "/" . $item->url . "/" . $item->resource_id; ?>" title='<?= htmlspecialchars_decode($item->title); ?>'>
                <span class="imgHolder">
                    <?php if (is_file("uploads/uploaded_pictures/_content_proizvodi/" . $dimUrlLitSecund . "/" . $item->product_image)) { ?>
                        <img class="transition" src="/uploads/uploaded_pictures/_content_proizvodi/<?= $dimUrlLitSecund . "/" . $item->product_image; ?>" alt='<?= htmlspecialchars_decode($item->title); ?> slika' title='<?= htmlspecialchars_decode($item->title); ?>' />
                    <?php } else { ?>
                        <img class="transition" src="/images/no-image.jpg" alt='<?= htmlspecialchars_decode($item->title); ?> slika' title='<?= htmlspecialchars_decode($item->title); ?>' />
                    <?php } ?>

                </span> 
                <?php
                if ($item->old_price > $item->price) {
                    $procenat = 100 - (100 * $item->price / $item->old_price);
                    ?>
                    <p>
                        <span><?= ceil($procenat); ?>%</span>
                        <span>popust</span>
                    </p>
                    <?php
                }
                ?>

            </a>
        </div>
        <div class="littleProductDesc">
            <div class="clear">
                <span class="arrow"></span>
                <div class="drziOpis clear">
                    <h4>
                        <a href="/<?= $item->master_cat_url . "/" . $item->sub_cat_url . "/" . $item->url . "/" . $item->resource_id; ?>" title='<?= htmlspecialchars_decode($item->title); ?>'><?= $item->b_title . " " . htmlspecialchars_decode($item->title); ?></a>
                    </h4>
                    <?php /* <p><?= substr(strip_tags($item->technical_description),0,40); ?></p> */ ?>
                    <div class="priceSum">
                        <?php if ($item->old_price > 0 && $item->old_price != "") { ?> <span class='outer'><span class="inner"><?= number_format($item->old_price, 2, ",", "."); ?> rsd</span></span><?php } ?>
                        <strong class="margin-vertical<?php
                        if (($item->old_price != '' && $item->old_price > 0) || $item->akcija == "Da") {
                            echo " popustPrice";
                        }
                        ?>"><b>Cena:</b><?= ($item->master_price > 0) ? number_format($item->master_price, 2, ",", ".") : number_format($item->price, 2, ",", "."); ?> rsd</strong>
                    </div>
                </div>
                <a class="addCart" href="javascript:addToCart('<?= $item->resource_id; ?>','1','<?= ($item->master_price > 0) ? $item->master_price : $item->price; ?>')" title="Dodaj u korpu"><i class="fa fa-cart-plus"></i> Dodaj u korpu</a>
                <a <?= $color; ?> href="javascript:" class="addCompare right <?php if ($numList == 0) { ?>add-to-wish-list <?php } ?>" data-rid="<?= $item->resource_id; ?>" title="Uporedi"><i class="fa fa-arrows-h"></i></a>
                <a href="javascript:" data-rid="<?= $item->resource_id; ?>" class="removeWish" title="Izbaci iz liste želja">
                    <i class="fa fa-times transition"></i>
                </a>
            </div>
        </div>
    </div>
</div>