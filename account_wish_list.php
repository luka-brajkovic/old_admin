<?php
include_once ("library/config.php");

$isWishList = true;

$wishlist = mysqli_query($conn, "SELECT lz.*, cp.*, b.title as b_title, c.url as master_cat_url, c1.url as sub_cat_url FROM list_zelja lz "
        . " JOIN _content_products cp ON cp.resource_id = lz.product_rid "
        . " LEFT JOIN _content_brand b ON b.resource_id = cp.brand "
        . " LEFT JOIN categories_content cc ON cp.resource_id = cc.content_resource_id "
        . " LEFT JOIN categories c1 ON c1.resource_id = cc.category_resource_id "
        . " LEFT JOIN categories c ON c.resource_id = c1.parent_id "
        . " WHERE "
        . " lz.user_rid = $userData->resource_id ");

include_once ("head.php");
?>

</head>
<body>
    <?php
    include_once ("header.php");
    ?>
    <div class="container">
        <div class="content clear productListIndex ">
            <?php if (mysqli_num_rows($wishlist) < 1) { ?>
                <br>
                <h1>Lista želja je prazna</h1>
                <p>Poštovani, Vaša lista želja je prazna, proizvode možete dodati u istu želja klikom na ikonicu srca na svakom proizvodu.</p>
            <?php } else if (!$isLoged) { ?>
                <br>
                <h1>Morate biti prijavljenji</h1>
                <p>Poštovani, da bi ste videli Vaši listu želja morate biti <a href="/prijava" title="Prijavite se">prijavljeni</a>.</p>
            <?php } else { ?>
                <div class="nav_account clear">
                    <?php include_once("includes/account_nav.php"); ?>
                </div>
                <h1>Lista želja</h1>
                <div id="petine" class="productHolder clear">
                    <div class="productListIndex row">
                        <?php
                        while ($item = mysqli_fetch_object($wishlist)) {
                            include ("includes/little_product-wishlist.php");
                        }
                        ?>
                    </div>
                </div>   
            <?php } ?>
        </div>
    </div>
    <?php
    include_once ("footer.php");
    ?>

</body>
</html>