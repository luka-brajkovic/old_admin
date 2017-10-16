<div class="container mainIndex">
    <div class="sliderHolder row">
        <div class="quarter-x3 right">
            <?php include_once ("slider.php"); ?>
            <h1>Preporuke za kupovinu</h1>
            <div class="productHolder row">
                <?php
                $preporukaQuery = mysql_query(""
                        . "SELECT cp.*, b.title as b_title, c.url as master_cat_url, c1.url as sub_cat_url FROM _content_proizvodi cp "
                        . " LEFT JOIN _content_brend b ON b.resource_id = cp.brand "
                        . " LEFT JOIN categories_content cc ON cp.resource_id = cc.content_resource_id "
                        . " LEFT JOIN categories c1 ON c1.resource_id = cc.category_resource_id "
                        . " LEFT JOIN categories c ON c.resource_id = c1.parent_id "
                        . " WHERE cp.product_image!='' AND (cp.status = 1 OR cp.master_status = 'Active') AND cp.lang = $currentLanguage AND cp.preporuka='Da' ORDER BY ordering ASC");
                while ($item = mysql_fetch_object($preporukaQuery)) {
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
            if ($configSiteFacebook != "") {
                ?>
                <a class="facebook" href="<?= $configSiteFacebook; ?>" target="_blank" title="Facebook stranica <?= $configSiteFirm; ?>">
                    <i class="fa fa-facebook"></i>
                </a>
                <?php
            }
            if ($configSiteGooglePlus != "") {
                ?>
                <a class="googlePlus" href="<?= $configSiteGooglePlus; ?>" title="Google Plus stranica <?= $configSiteFirm; ?>" target="_blank">
                    <i class="fa fa-google-plus transition"></i>
                </a>
                <?php
            }
            if ($configSiteTwitter != "") {
                ?>
                <a class="twitter" href="<?= $configSiteTwitter; ?>" title="Twitter stranica <?= $configSiteFirm; ?>" target="_blank">
                    <i class="fa fa-twitter transition"></i>
                </a>
                <?php
            }
            if ($configSiteLinkedIn != "") {
                ?>
                <a class="linkedIn" href="<?= $configSiteLinkedIn; ?>" title="LinkedIn stranica <?= $configSiteFirm; ?>" target="_blank">
                    <i class="fa fa-linkedin transition"></i>
                </a>
            <?php 
            } 
            if ($configSiteYouTube != "") {
                ?>
                <a class="youtube" href="<?= $configSiteYouTube; ?>" title="You Tube stranica <?= $configSiteFirm; ?>" target="_blank">
                    <i class="fa fa-youtube transition"></i>
                </a>
            <?php }
            if ($configSiteVimeo != "") {
                ?>
                <a class="vimeo" href="<?= $configSiteVimeo; ?>" title="Vimeo stranica <?= $configSiteFirm; ?>" target="_blank">
                    <i class="fa fa-vimeo transition"></i>
                </a>
            <?php }
            if ($configSiteInstagram != "") {
                ?>
                <a class="instagram" href="<?= $configSiteInstagram; ?>" title="Instagram stranica <?= $configSiteFirm; ?>" target="_blank">
                    <i class="fa fa-instagram transition"></i>
                </a>
            <?php }
            if ($configSitePinterest != "") {
                ?>
                <a class="pinterest" href="<?= $configSitePinterest; ?>" title="Pinterest stranica <?= $configSiteFirm; ?>" target="_blank">
                    <i class="fa fa-pinterest transition"></i>
                </a>
            <?php } ?>
        </div>
    </div>
    <?php
    $bannerIndex = mysql_query("SELECT slika, title, link_ka FROM _content_banneri WHERE resource_id = 8921 LIMIT 1");
    $bannerIndex = mysql_fetch_object($bannerIndex);
    if (is_file("uploads/uploaded_files/_content_banneri/" . $bannerIndex->slika)) {
        if ($bannerIndex->link_ka != "" && $bannerIndex->link_ka != "#") {
            $link = $bannerIndex->link_ka;
        } else {
            $link = "javascript:";
        }
        echo "<a class='bannerIndex' href='$link'><img src='/uploads/uploaded_files/_content_banneri/$bannerIndex->slika' alt='$bannerIndex->title' title='$bannerIndex->title'></a>";
    }
    ?>
</div>
