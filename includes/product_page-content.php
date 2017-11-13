<div class="container">
	<?php
	$breadcrumbs = array(
		$niz[1]->title => "/" . $niz[1]->url,
		$niz[2]->title => "/" . $niz[1]->url . "/" . $niz[2]->url,
		$proizvod->b_title . " " . $proizvod->title => "/" . $niz[1]->url . "/" . $niz[2]->url . "/" . $proizvod->url . "/" . $proizvod->resource_id
	);
	$f->breadcrumbs($breadcrumbs, $csTitle, $csName);
	?>   
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