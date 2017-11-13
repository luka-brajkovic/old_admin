<div class="<?= ($show_type == "row") ? "list_style" : "quarter margin-vertical"; ?> drzga clear">
    <div class="littleProduct clear">
        <a class="imiage" href="/artikal/<?= $item->url . "/" . $item->resource_id; ?>" title="<?= $item->title; ?>">
            <?php if (is_file("uploads/uploaded_pictures/_content_products/$dimUrlLitSecund/" . $item->product_image)): ?>
                <img class="transition" src="/uploads/uploaded_pictures/_content_products/<?= $dimUrlLitSecund."/".$item->product_image; ?>" alt="<?= $product->title; ?> slika" title="<?= $product->title; ?>" />
            <?php else: ?>
                <img class="transition" src="/images/no-product.jpg" alt="<?= $product->title; ?> slika" title="<?= $product->title; ?>" />
            <?php endif; ?>
        </a>
        <div class="prodListHori">
            <div class="sameHeight drziOpis">
                <a href="/artikal/<?= $item->url . "/" . $item->resource_id; ?>" title="<?= $item->title; ?>">
                    <h4><?= $item->title; ?></h4>
                </a>
                <strong class="price"><?= number_format($item->price, 0, ",", "."); ?> rsd</strong>
            </div>
            <div class="prodCard"><hr/>
                <span>Količina: <?= $item->kolicina; ?></span>
            </div>
        </div>
    </div>
</div>