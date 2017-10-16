<?php
include_once ("library/config.php");
if (!$isLoged) {
    $f->redirect('/prijava');
}$urlAKTIVE = "/";
?><?php include_once ("head.php"); ?></head>
<body>
    <?php
    include_once ("header.php");
    ?>
    <div class="container">

        <div class="holderContent clear">
            <div class="quarter-x3 right">
                <div class="quarterMarginMasterL">
                    <h1>Lista zelja</h1>
                    <div class="productHolder clear">
                        <?php
                        $user = $_SESSION["loged_user"];
                        $sql = "SELECT cp.*, b.title as b_title, c.url as master_cat_url, c1.url as sub_cat_url FROM list_zelja lz "
                                . " JOIN _content_proizvodi cp ON cp.resource_id = lz.product_rid "
                                . " LEFT JOIN _content_brend b ON b.resource_id = cp.brand "
                                . " LEFT JOIN categories_content cc ON cp.resource_id = cc.content_resource_id "
                                . " LEFT JOIN categories c1 ON c1.resource_id = cc.category_resource_id "
                                . " LEFT JOIN categories c ON c.resource_id = c1.parent_id "
                                . " WHERE lz.user_rid = $user AND (cp.status = 1 OR cp.master_status = 'Active') AND cp.lang = $currentLanguage";
                        $num = $db->numRows($sql);
                        $preporukaQuery = $db->execQuery($sql);
                        if ($num > 0) {
                            while ($item = mysql_fetch_object($preporukaQuery)) {
                                include ("little_product.php");
                            }
                        } else {
                            echo "<p>Trenutno nema proizvoda</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="quarter indexLeftSide margin-vertical left">
                <?php include_once ("index-content-categories.php"); ?>
                <?php include_once ("left_side-news.php"); ?>
            </div>
        </div>
    </div>
    <?php
    include_once ("footer.php");
    ?></body>
</html>