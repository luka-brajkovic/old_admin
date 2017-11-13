<?php
include_once ("library/config.php");

/* PRVI NIVO KATEGORIJE */
$cat_master_url = $f->getValue("cat_master");

$singleCategories = 1;
if ($cat_master_url == "proizvodi-na-akciji") {

    $cat_master_url = $f->getValue("sub_cat");

    include_once 'categories-akcija.php';
} else {

    /* DRUGI NIVO KATEGORIJE */
    $cat_sub_url = $f->getValue("sub_cat");
    /* OVO JE AKO IMA 3 NIVO KATEGORIJE */
    $cat_last_url = $f->getValue("last_cat");

    $catMasterData = mysqli_query($conn,"SELECT * FROM categories WHERE url = '$cat_master_url' LIMIT 1");
    $catMasterData = mysqli_fetch_object($catMasterData);

    if ($catMasterData->id == '') {
        $f->redirect("/poruka/404");
    }

    if (isset($_GET['cat'])) {

        $catQuery = "";
        $catQueryArr = array();
        foreach ($_GET['cat'] as $k => $cat) {
            $catQueryArr[] = "cat[$k]=$cat";
        }
        $catQuery = implode("&", $catQueryArr);
        //echo $catQuery;

        $catID = $_GET['cat'][0];
        if (count($_GET['cat']) > 1) {
            if ($cat_last_url != "") {
                $f->redirect('/' . $cat_master_url . '/' . $cat_sub_url . '&' . $catQuery);
            }
        } else {

            $category = new View('categories', $catID, 'resource_id');
            $f->redirect('/' . $cat_master_url . '/' . $cat_sub_url . '/' . $category->url);
        }
    }

    $filterCategory = $f->getValue('filter_category');
    $catSubQ = mysqli_query($conn,"SELECT * FROM categories WHERE parent_id = $catMasterData->resource_id AND url='$cat_sub_url' AND status = 1 LIMIT 1") or die(mysqli_error($conn));

    $catMiddleData = mysqli_fetch_object($catSubQ);
    $catMiddleDataURL = $catMiddleData->url;

    if ($catMiddleData->id == '') {
        $f->redirect("/poruka/404");
    }
    if ($cat_last_url != '') {
        /* AKO JE ZADNJA KATEGORIJA */
        $catLastQ = mysqli_query($conn,"SELECT id, resource_id, url, title FROM categories WHERE parent_id = $catMiddleData->resource_id AND url='$cat_last_url' AND status = 1 LIMIT 0,1");
        $catLastData = mysqli_fetch_object($catLastQ);
        $preporukaQuerySQLRangeMin = mysqli_query($conn,""
                . " SELECT MIN(cp.price) as min_price FROM _content_products cp "
                . " LEFT JOIN categories_content cc ON cc.content_resource_id = cp.resource_id "
                . " LEFT JOIN categories c3 ON cc.category_resource_id = c3.resource_id "
                . " LEFT JOIN categories c2 ON c3.parent_id = c2.resource_id "
                . " LEFT JOIN categories c1 ON c2.parent_id = c1.resource_id "
                . " WHERE "
                . " (c3.resource_id = $catLastData->resource_id OR c2.resource_id = $catLastData->resource_id OR c1.resource_id = $catLastData->resource_id ) AND "
                . " (cp.status = 1 OR cp.master_status = 'Active') AND cp.lang = $currentLanguage AND cp.price > 0 ");
        $preporukaQuerySQLRangeMax = mysqli_query($conn,""
                . " SELECT MAX(cp.price) as  max_price  FROM _content_products cp "
                . " LEFT JOIN categories_content cc ON cc.content_resource_id = cp.resource_id "
                . " LEFT JOIN categories c3 ON cc.category_resource_id = c3.resource_id "
                . " LEFT JOIN categories c2 ON c3.parent_id = c2.resource_id "
                . " LEFT JOIN categories c1 ON c2.parent_id = c1.resource_id "
                . " WHERE "
                . " (c3.resource_id = $catLastData->resource_id OR c2.resource_id = $catLastData->resource_id OR c1.resource_id = $catLastData->resource_id ) AND "
                . " (cp.status = 1 OR cp.master_status = 'Active') AND cp.lang = $currentLanguage AND cp.price > 0 ");

        $rangeSpan = mysqli_fetch_array($preporukaQuerySQLRangeMin);
        $rangeSpanMin = $rangeSpan[min_price];
        $rangeSpan = mysqli_fetch_array($preporukaQuerySQLRangeMax);
        $rangeSpanMax = $rangeSpan[max_price];

        $pageData = $catLastData;
    } else {

        $preporukaQuerySQLRangeMin = mysqli_query($conn,""
                . " SELECT MIN(cp.price) as min_price FROM _content_products cp "
                . " LEFT JOIN categories_content cc ON cc.content_resource_id = cp.resource_id "
                . " LEFT JOIN categories c3 ON cc.category_resource_id = c3.resource_id "
                . " LEFT JOIN categories c2 ON c3.parent_id = c2.resource_id "
                . " WHERE "
                . " (c3.resource_id = $catMiddleData->resource_id OR c2.resource_id = $catMiddleData->resource_id ) AND "
                . " (cp.status = 1 OR cp.master_status = 'Active') AND cp.lang = $currentLanguage AND cp.price > 0 ");
        $preporukaQuerySQLRangeMax = mysqli_query($conn,""
                . " SELECT MAX(cp.price) as  max_price  FROM _content_products cp "
                . " LEFT JOIN categories_content cc ON cc.content_resource_id = cp.resource_id "
                . " LEFT JOIN categories c3 ON cc.category_resource_id = c3.resource_id "
                . " LEFT JOIN categories c2 ON c3.parent_id = c2.resource_id "
                . " WHERE "
                . " (c3.resource_id = $catMiddleData->resource_id OR c2.resource_id = $catMiddleData->resource_id ) AND "
                . " (cp.status = 1 OR cp.master_status = 'Active') AND cp.lang = $currentLanguage AND cp.price > 0 ");

        $rangeSpan = mysqli_fetch_array($preporukaQuerySQLRangeMin);
        $rangeSpanMin = $rangeSpan[min_price];
        $rangeSpan = mysqli_fetch_array($preporukaQuerySQLRangeMax);
        $rangeSpanMax = $rangeSpan[max_price];

        $pageData = $catMiddleData;
        if ($pageData->id == '') {
            $f->redirect("/poruka/404");
        }
    }

    if ($cat_last_url != "") {
        $canonical = $csDomain . $cat_master_url . "/" . $cat_sub_url . "/" . $cat_last_url;
    } else {
        $canonical = $csDomain . $cat_master_url . "/" . $cat_sub_url;
    }

    $titleSEO = $catMasterData->title . " - " . $pageData->title . " | $csName";
    $descSEO = $catMasterData->title . " - " . $pageData->title . ", " . $csDesc;

    $htmlTagAddOG = 'prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# product: http://ogp.me/ns/product#"';
    $ogType = "product.group";

    include_once ("head.php");
    ?>
    </head>
    <body>
        <?php
        include_once ("header.php");
        include_once ("category-single-content.php");
        include_once ("footer.php");
        include 'js/filter-cat-scripts-min.php';
        ?>
    </body>
    </html>
<?php } ?>