<div class="container">
    <ol class="pagePosition margin-vertical clear" vocab="http://schema.org/" typeof="BreadcrumbList">
        <li>
            <span>Vi ste ovde:</span>
        </li> 
        <li property="itemListElement" typeof="ListItem">
            <a href="<?= rtrim($configSiteDomain, "/"); ?>" title="<?= $configSiteTitle; ?>" property="item" typeof="WebPage">
                <span property="name">Početna</span>
                <meta property="position" content="1">
            </a>
        </li>   
        <li property="itemListElement" typeof="ListItem">
            <a href="/<?= $niz[1]->url; ?>" title="<?= $niz[1]->title; ?>" property="item" typeof="WebPage">
                <span property="name" class="transition"><?= $niz[1]->title; ?></span>
                <meta property="position" content="2">
            </a>
        </li>    
        <li property="itemListElement" typeof="ListItem">
            <a href="/<?= $niz[1]->url; ?>/<?= $niz[2]->url; ?>" title="<?= $niz[2]->title; ?>" property="item" typeof="WebPage">
                <span property="name" class="transition"><?= $niz[2]->title; ?></span>
                <meta property="position" content="3">
            </a>
        </li>    
        <li property="itemListElement" typeof="ListItem">
            <a href="/<?= $niz[1]->url; ?>/<?= $niz[2]->url . "/" . $proizvod->url . "/" . $proizvod->resource_id; ?>" title="<?= $niz[2]->title; ?>" property="item" typeof="WebPage">
                <span property="name" class="transition"><?= $proizvod->b_title . " " . $proizvod->title; ?></span>
                <meta property="position" content="4">
            </a>
        </li>    
    </ol>
    <div class="productPageHolder">
        <div class="productTop row" itemscope itemtype="http://schema.org/Product">
            <div class="third">
                <?php include("product_page-content-image.php"); ?>
            </div>
            <div class="third-x2">
                <?php include_once("product_page-content-description.php"); ?>
            </div>
        </div>  
        <?php include_once("product_page-content-down.php"); ?>
    </div>
</div>