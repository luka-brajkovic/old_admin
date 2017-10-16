<?php
include_once ("library/config.php");
$cat_master_url = $f->getValue("cat_master");
if ($cat_master_url == "proizvodi-na-akciji") {
    $getKategoriju = $f->getValue("choosecat");
    $titleSEO = "Svi proizvodi na akciji - $configSiteFirm";
    $descSEO = "Proizvodi iz svih kategorija po akcijskim cenama, najjeftinije samo u našem online shopu i prodavnici, $configSiteFirm";
    if ($getKategoriju != "") {
        $brandDatas = new View("categories", $getKategoriju, "resource_id");
        $add = " c.resource_id = $getKategoriju AND ";
        $titleSEO = "$brandDatas->title - proizvodi na akciji | $configSiteFirm";
        $descSEO = "Proizvodi iz kategorije $brandDatas->title po akcijskim cenama, najjeftinije samo u našem online shopu i prodavnici, $configSiteFirm";
    }
    $urlJe = "proizvodi-na-akciji";
} else {
    $categories = new Collection("categories");
    $categoriesArr = $categories->getCollection("WHERE parent_id = 0 AND status = 1 AND url = '$cat_master_url' LIMIT 1");
    $catMasterData = $categoriesArr[0];
    $catMasterDataURL = $catMasterData->url;

    $pageData = $catMasterData;

    if ($catMasterData->id == '') {
        $f->redirect("/poruka/404");
    }

    $preporukaQuery = mysql_query(""
            . "SELECT cp.*, b.title as b_title, c.url as master_cat_url, c1.url as sub_cat_url FROM _content_proizvodi cp "
            . " LEFT JOIN _content_brend b ON b.resource_id = cp.brand "
            . " LEFT JOIN categories_content cc ON cp.resource_id = cc.content_resource_id "
            . " LEFT JOIN categories c1 ON c1.resource_id = cc.category_resource_id "
            . " LEFT JOIN categories c ON c.resource_id = c1.parent_id "
            . " WHERE "
            . " (c.resource_id = $catMasterData->resource_id OR c1.resource_id = $catMasterData->resource_id ) AND "
            . " product_image!='' AND (cp.status = 1 OR cp.master_status = 'Active') AND cp.lang = $currentLanguage AND  (cp.akcija = 'Da' OR cp.price < cp.old_price OR cp.master_price < cp.old_price) ORDER BY cp.ordering ASC") or die(mysql_error());

    if ($pageData->title_seo != "") {
        $titleSEO = $pageData->title_seo;
    } else {
        if (mysql_num_rows($preporukaQuery) > 0) {
            $titleSEO = $pageData->title . " - proizvodi na akciji | $configSiteFirm";
        } else {
            $titleSEO = $pageData->title . " - najprodavaniji proizvodi | $configSiteFirm";
        }
    }

    if ($pageData->desc_seo != "") {
        $descSEO = $pageData->desc_seo;
    } else {
        $descSEO = "Proizvodi iz kategorije $pageData->title po akcijskim cenama, najjeftinije samo u našem online shopu i prodavnici, $configSiteFirm";
    }
}
?>

<?php include_once ("head.php"); ?>
</head>
<body>
    <?php
    include_once ("header.php");
    include_once ("categories-content.php");
    include_once ("footer.php");
    ?>
</body>
</html>