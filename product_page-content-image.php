<div class="productPageImage quarterMarginRM margin-vertical">
    <ul class="clear">
        <li>
            <?php
            if (is_file("uploads/uploaded_pictures/_content_products/" . $dimUrlLitBigs . "/" . $proizvod->product_image)) {
                ?>
                <a data-fancybox-group="productTitle" href="<?= '/uploads/uploaded_pictures/_content_products/' . $dimUrlLitBigs . "/" . $proizvod->product_image; ?>" title="<?= $proizvod->b_title . " " . $proizvod->title; ?>" class="fancybox">
                    <img itemprop="image" class="transition" src="<?= '/uploads/uploaded_pictures/_content_products/' . $dimUrlLitSecund . "/" . $proizvod->product_image; ?>" alt="<?= $proizvod->title; ?>" title="<?= $proizvod->b_title . " " . $proizvod->title; ?>" />
                    <span>Kliknite da uveličate sliku</span>
                </a>
            <?php } elseif (is_file("uploads/uploaded_pictures/_content_products/" . $dimUrlLitSecund . "/" . $proizvod->product_image)) {
                ?>
                <a data-fancybox-group="productTitle" href="<?= '/uploads/uploaded_pictures/_content_products/' . $dimUrlLitSecund . "/" . $proizvod->product_image; ?>" title="<?= $proizvod->b_title . " " . $proizvod->title; ?>" class="fancybox">
                    <img itemprop="image" class="transition" src="<?= '/uploads/uploaded_pictures/_content_products/' . $dimUrlLitSecund . "/" . $proizvod->product_image; ?>" alt="<?= $proizvod->title; ?>" title="<?= $proizvod->b_title . " " . $proizvod->title; ?>" />
                    <span>Kliknite da uveličate sliku</span>
                </a>
            <?php } else {
                ?>
                <img class="transition" src="/images/no-image.jpg" alt="<?= $proizvod->b_title . " " . $proizvod->title; ?>" title="<?= $proizvod->title; ?>" />
            <?php }
            ?>
        </li>
        <?php
        for ($i = 1; $i < 11; $i++) {
            $slika = "slika_" . $i;
            if (is_file('uploads/uploaded_pictures/_content_products/' . $dimUrlLit . "/" . $proizvod->$slika)) {
                ?>
                <li>
                    <a data-fancybox-group="productTitle" href="<?= '/uploads/uploaded_pictures/_content_products/' . $dimUrlLitBigs . "/" . $proizvod->$slika; ?>" title="<?= $proizvod->title; ?>" class="fancybox">
                        <img class="transition" src="<?= '/uploads/uploaded_pictures/_content_products/' . $dimUrlLit . "/" . $proizvod->$slika; ?>" alt="<?= $proizvod->b_title . " " . $proizvod->title; ?> - Slika <?= $i; ?>" title="<?= $proizvod->b_title . " " . $proizvod->title; ?> - Slika <?= $i; ?>" />
                    </a>
                </li>
                <?php
            }
        }
        ?>
    </ul>
    <?php
    if ($proizvod->product_code != "") {
        ?>
        <span class="password lef">Šifra proizvoda: <?= $proizvod->product_code; ?></span><br>
    <?php } ?>
    <meta itemprop="brand" content="<?= $proizvod->b_title; ?>">
    <span class="password">
        <figure>
            <?php
            if (is_file("uploads/uploaded_pictures/_content_brand/140x60/" . $proizvod->b_logo)) {
                ?>

                <a href="/robne-marke/<?= $proizvod->b_url; ?>">
                    <img class="trueImage" src="/uploads/uploaded_pictures/_content_brand/140x60/<?= $proizvod->b_logo; ?>" alt="<?= $proizvod->b_title; ?>" title="<?= $proizvod->b_title; ?>">
                </a>
            <?php } else { ?>
                Brend proizvoda: <a href="/robne-marke/<?= $proizvod->b_url; ?>"><?= $proizvod->b_title; ?></strong></a>
            <?php } ?>
        </figure>
    </span>	
    <a href="javascript:window.print()" id="print">
        <i class="fa fa-print transition"></i> Štampaj ili preuzmi specifikaciju
    </a>
    <div class="shareProd">
        <ul class="row">
            <li class="fifth">
                <a target="_blank" class="facebook" href="http://www.facebook.com/sharer.php?u=<?= $shareUrl; ?>" title="Facebook" onclick="window.open('http://www.facebook.com/sharer.php?u=<?= $shareUrl; ?>', 'newwindow', 'width=570, height=620'); return false;">
                    <i class="fa fa-facebook"></i>
                </a>
            </li>
            <li class="fifth">
                <a target="_blank" href="https://twitter.com/intent/tweet?url=<?= $shareUrl; ?>" class="twitter" onclick="window.open('https://twitter.com/intent/tweet?url=<?= $shareUrl; ?>', 'newwindow', 'width=570, height=250'); return false;">
                    <i class="fa fa-twitter"></i>
                </a>
            </li>
            <li class="fifth">
                <a class="google" href="http://plus.google.com/share?url=<?= $shareUrl; ?>" title="Google Plus" target="_blank" onclick="window.open('http://plus.google.com/share?url=<?= $shareUrl; ?>', 'newwindow', 'width=515, height=650'); return false;">
                    <i class="fa fa-google-plus"></i>
                </a>
            </li>
            <li class="fifth">
                <a href="http://pinterest.com/pin/create/button/?url=<?= $shareUrl; ?>" class="pinterest" target="_blank" onclick="window.open('http://pinterest.com/pin/create/button/?url=<?= $shareUrl; ?>', 'newwindow', 'width=1000, height=650'); return false;">
                    <i class="fa fa-pinterest" aria-hidden="true"></i>
                </a>
            </li>
            <li class="fifth">
                <a href="http://www.linkedin.com/shareArticle?mini=true&url=<?= $shareUrl; ?>" class="linkedin" target="_blank" onclick="window.open('http://www.linkedin.com/shareArticle?mini=true&url=<?= $shareUrl; ?>', 'newwindow', 'width=550, height=650'); return false;">
                    <i class="fa fa-linkedin" aria-hidden="true"></i>
                </a>
            </li>
        </ul>
    </div>
</div>