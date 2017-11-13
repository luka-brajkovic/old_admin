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
		<?php
		$breadcrumbs = array(
			$bread["cat_sub_title1"] => $bread["cat_sub_url1"],
			$maincat["title"] => $maincat["url"]
		);


		$breadCount = count($breadcrumbs);
		$counter = 0;
		foreach ($breadcrumbs as $name => $link) {
			$link = ltrim($link, "/");
			$counter++;
			if ($link != "") {
				if ($breadCount == $counter) {
					?>
					<span class=“navigation_page”><?= $name; ?></span>
				<?php } else { ?>
					<a class=“home” href=“<?= WEB_URL . $link; ?>” title=“<?= $name; ?>“><?= $name; ?></a>
					<span class=“navigation-pipe”>&nbsp;</span>
					<?php
				}
			}
		}
		?>  
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