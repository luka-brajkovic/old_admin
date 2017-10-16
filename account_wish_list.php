<?php
include_once ("library/config.php");

if (!$isLoged) {
    $f->redirect("/poruka/prijava");
}
$isWishList = true;

$wishlist = mysql_query("SELECT lz.*, cp.*, b.title as b_title, c.url as master_cat_url, c1.url as sub_cat_url FROM list_zelja lz "
        . " JOIN _content_proizvodi cp ON cp.resource_id = lz.product_rid "
        . " LEFT JOIN _content_brend b ON b.resource_id = cp.brand "
        . " LEFT JOIN categories_content cc ON cp.resource_id = cc.content_resource_id "
        . " LEFT JOIN categories c1 ON c1.resource_id = cc.category_resource_id "
        . " LEFT JOIN categories c ON c.resource_id = c1.parent_id "
        . " WHERE "
        . " lz.user_rid = $userData->resource_id ");
if (mysql_num_rows($wishlist) < 1) {
    $f->redirect("/poruka/lista-zelja-prazna");
}
?>

<?php include_once ("head.php"); ?>

</head>
<body>
    <?php
    include_once ("popup.php");
    include_once ("header.php");
    ?>
    <div class="container">
        <div class="content clear productListIndex ">
            <div class="nav_account clear">
                <?php include_once("account_nav.php"); ?>
            </div>
            <h1>Lista želja</h1>

            <?php
            ?>
            <div id="petine" class="productHolder clear">
                <div class="productListIndex row">
                    <?php
                    while ($item = mysql_fetch_object($wishlist)) {
                        include ("little_product-wishlist.php");
                    }
                    ?>
                </div>
            </div>   
        </div>
    </div>
    <?php
    include_once ("footer.php");
    ?>

</body>
</html>