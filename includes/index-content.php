<div class="container mainIndex">
    <div class="sliderHolder row">
        <div class="quarter-x3 right">
			<?php include_once ("slider.php"); ?>
            <h1>Preporuke za kupovinu</h1>
            <div class="productHolder row">
				<?php
				$preporukaQuery = mysqli_query($conn, ""
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
</div>
