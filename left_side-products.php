<div class="shadow margin-vertical quarterMarginMasterR">
    <div class="leftSideProducts">
        <h2>Najprodavanije</h2>
        <div class="littleLittleProductHolder">
            <?php
            $najprodavanijeQuery = mysql_query("SELECT cp.*, b.title as b_title, c.url as master_cat_url, c1.url as sub_cat_url FROM _content_proizvodi cp "
                    . " LEFT JOIN _content_brend b ON b.resource_id = cp.brand "
                    . " LEFT JOIN categories_content cc ON cp.resource_id = cc.content_resource_id "
                    . " LEFT JOIN categories c1 ON c1.resource_id = cc.category_resource_id "
                    . " LEFT JOIN categories c ON c.resource_id = c1.parent_id "
                    . " WHERE cp.product_image!='' AND (cp.status = 1 OR cp.master_status = 'Active') AND cp.najprodavanije = 'Da' AND cp.lang = $currentLanguage ORDER BY cp.ordering ASC");
            while ($item = mysql_fetch_object($najprodavanijeQuery)) {
                include ("little_little_product.php");
            }
            ?>        
        </div>
    </div>
</div>