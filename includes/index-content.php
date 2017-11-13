<div class="container mainIndex">
    <div class="sliderHolder row">
        <div class="quarter-x3 right">
            <?php include_once ("slider.php"); ?>
            <h1>Preporuke za kupovinu</h1>
            <div class="productHolder row">
                <?php
                $preporukaQuery = mysqli_query($conn,""
                        . "SELECT cp.*, b.title as b_title, c.url as master_cat_url, c1.url as sub_cat_url FROM _content_products cp "
                        . " LEFT JOIN _content_brand b ON b.resource_id = cp.brand "
                        . " LEFT JOIN categories_content cc ON cp.resource_id = cc.content_resource_id "
                        . " LEFT JOIN categories c1 ON c1.resource_id = cc.category_resource_id "
                        . " LEFT JOIN categories c ON c.resource_id = c1.parent_id "
                        . " WHERE cp.product_image!='' AND (cp.status = 1 OR cp.master_status = 'Active') AND cp.lang = $currentLanguage AND cp.preporuka='Da' ORDER BY ordering ASC");
                while ($item = mysqli_fetch_object($preporukaQuery)) {
                    include ("little_product.php");
                }
                ?>
            </div>
        </div>
        <div class="quarter margin-vertical left">
            <?php
            include_once ("index-content-categories.php");
            include_once ("left_side.php");
            ?>
        </div>
    </div>
    <div class="footerNewsletter row">
        <div class="third-x2 clear">
            <span class="left">Prijavite se na newsletter:</span>
            <form action="/" method="post" class="clear">
                <input class="left" value="" name="email" placeholder="upiši tvoj e-mail" type="text">
                <input class="left" name="okinuto" value="Prijavi" type="submit">
            </form>
        </div>
        <div class="third">
            <h6>Pratite nas</h6>
            <?php
            if ($csFacebook != "") {
                ?>
                <a class="facebook" href="<?= $csFacebook; ?>" target="_blank" title="Facebook stranica <?= $csName; ?>">
                    <i class="fa fa-facebook"></i>
                </a>
                <?php
            }
            if ($csGooglePlus != "") {
                ?>
                <a class="googlePlus" href="<?= $csGooglePlus; ?>" title="Google Plus stranica <?= $csName; ?>" target="_blank">
                    <i class="fa fa-google-plus transition"></i>
                </a>
                <?php
            }
            if ($csTwitter != "") {
                ?>
                <a class="twitter" href="<?= $csTwitter; ?>" title="Twitter stranica <?= $csName; ?>" target="_blank">
                    <i class="fa fa-twitter transition"></i>
                </a>
                <?php
            }
            if ($csLinkedIn != "") {
                ?>
                <a class="linkedIn" href="<?= $csLinkedIn; ?>" title="LinkedIn stranica <?= $csName; ?>" target="_blank">
                    <i class="fa fa-linkedin transition"></i>
                </a>
            <?php 
            } 
            if ($csYouTube != "") {
                ?>
                <a class="youtube" href="<?= $csYouTube; ?>" title="You Tube stranica <?= $csName; ?>" target="_blank">
                    <i class="fa fa-youtube transition"></i>
                </a>
            <?php }
            if ($csVimeo != "") {
                ?>
                <a class="vimeo" href="<?= $csVimeo; ?>" title="Vimeo stranica <?= $csName; ?>" target="_blank">
                    <i class="fa fa-vimeo transition"></i>
                </a>
            <?php }
            if ($csInstagram != "") {
                ?>
                <a class="instagram" href="<?= $csInstagram; ?>" title="Instagram stranica <?= $csName; ?>" target="_blank">
                    <i class="fa fa-instagram transition"></i>
                </a>
            <?php }
            if ($csPinterest != "") {
                ?>
                <a class="pinterest" href="<?= $csPinterest; ?>" title="Pinterest stranica <?= $csName; ?>" target="_blank">
                    <i class="fa fa-pinterest transition"></i>
                </a>
            <?php } ?>
        </div>
    </div>
</div>
