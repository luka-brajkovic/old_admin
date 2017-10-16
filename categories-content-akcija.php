<div class="container clear">
    <ul class="pagePosition clear" itemtype="http://data-vocabulary.org/BreadcrumbList">
        <li>
            <span>Vi ste ovde:</span>
        </li>
        <li itemprop="itemListElement" itemscope="itemscope" itemtype="http://schema.org/ListItem">
            <a href="/" title="<?php echo $configSiteTitle; ?>" itemprop="url">
                <span itemprop="name">Početna</span>
            </a>
        </li>
        <li itemprop="itemListElement" itemscope="itemscope" itemtype="http://schema.org/ListItem">
            <a title="Proizvodi na akciji" href="/proizvodi-na-akciji" itemprop="url">
                <span itemprop="name">Proizvodi na akciji</span>
                <meta itemprop="position" content="1">
            </a>
        </li>
        <li>
            <span><?php echo $catMasterData->title; ?></span>
        </li>
    </ul>
    <h1><?php echo $catMasterData->title; ?> - proizvodi na akciji</h1>
    <div class="holderContent clear" >
        <div id="akcija_custom" class="productHolder">

            <div class="productListIndex clear">
                <?php
                $proizvodi_na_akciji = mysql_query("SELECT cp.*, b.title as b_title, c.url as master_cat_url, c1.url as sub_cat_url FROM _content_proizvodi cp "
                        . " LEFT JOIN _content_brend b ON b.resource_id = cp.brand "
                        . " LEFT JOIN categories_content cc ON cp.resource_id = cc.content_resource_id "
                        . " LEFT JOIN categories c1 ON c1.resource_id = cc.category_resource_id "
                        . " LEFT JOIN categories c ON c.resource_id = c1.parent_id "
                        . " WHERE cp.status = 1  AND (cp.akcija = 'Da' OR (cp.price < cp.old_price OR cp.master_price < cp.old_price)) ORDER BY cp.num_views DESC ") or die(mysql_error());
                while ($item = mysql_fetch_object($proizvodi_na_akciji)) {
                    include ("little_product.php");
                }
                ?>        
            </div>
        </div>
    </div>


</div>