<div class="container">
    <ol class="pagePosition margin-vertical clear" vocab="http://schema.org/" typeof="BreadcrumbList">
        <li>
            <span>Vi ste ovde:</span>
        </li> 
        <li property="itemListElement" typeof="ListItem">
            <a href="/" title="<?= $csTitle; ?>" property="item" typeof="WebPage">
                <span>Početna</span>
                <meta property="name" content="<?= $csName; ?>">
            </a>
            <meta property="position" content="1">
        </li>   
        <li property="itemListElement" typeof="ListItem">
            <a href="/<?= $niz[1]->url; ?>" title="<?= $niz[1]->title; ?>" property="item" typeof="WebPage">
                <span property="name" class="transition"><?= $niz[1]->title; ?></span>
            </a>
            <meta property="position" content="2">
        </li>    
        <li property="itemListElement" typeof="ListItem">
            <a href="/<?= $niz[1]->url; ?>/<?= $niz[2]->url; ?>" title="<?= $niz[2]->title; ?>" property="item" typeof="WebPage">
                <span property="name" class="transition"><?= $niz[2]->title; ?></span>
            </a>
            <meta property="position" content="3">
        </li>    
        <li>
            <a href="/<?= $niz[1]->url; ?>/<?= $niz[2]->url . "/" . $proizvod->url . "/" . $proizvod->resource_id; ?>" title="<?= $niz[2]->title; ?>">
                <span class="transition"><?= $proizvod->b_title . " " . $proizvod->title; ?></span>
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