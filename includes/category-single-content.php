<div class="container">
    <ol class="pagePosition clear" vocab="http://schema.org/" typeof="BreadcrumbList">
        <li>
            <span>Vi ste ovde:</span>
        </li>
        <li property="itemListElement" typeof="ListItem">
            <a href="/" title="<?= $csTitle; ?>" property="item" typeof="WebPage">
                <span>Početna</span>
                
            </a>
            <meta property="position" content="1">
        </li>
        <li property="itemListElement" typeof="ListItem">
            <a href="/<?= $catMasterData->url; ?>" title="<?= $catMasterData->title; ?>" property="item" typeof="WebPage">
                <span property="name"><?= $catMasterData->title; ?></span>
            </a>
            <meta property="position" content="2">
        </li>
        <?php
        if ($catLastData->id != '') {
            ?>
            <li property="itemListElement" typeof="ListItem">
                <a href="/<?= $catMasterData->url; ?>/<?= $catMiddleData->url; ?>" title="<?= $catMiddleData->title; ?>" property="item" typeof="WebPage">
                    <span property="name"><?= $catMiddleData->title; ?></span>
                    <meta property="position" content="3">
                </a>
            </li>
            <li>
                <a href="/<?= $catMasterData->url; ?>/<?= $catMiddleData->url; ?>/<?= $catLastData->url; ?>" title="<?= $catMiddleData->title; ?>">
                    <span><?= $catLastData->title; ?></span>
                </a>
            </li>
            <?php
        } else {
            ?>
            <li>
                <a href="/<?= $catMasterData->url; ?>/<?= $catMiddleData->url; ?>" title="<?= $catMiddleData->title; ?>">
                    <span><?= $catMiddleData->title; ?></span>
                </a>
            </li>
            <?php
        }
        ?>
    </ol>
    <?php
    if (isset($_SESSION["show_type"])) {
        $show_type = $_SESSION["show_type"];
    } else {
        $show_type = "list";
    }
    $subSubCatView = "";
    $preporukaQuerySQL = "SELECT cp.url, cp.title, cp.product_image, cp.master_price, cp.old_price, cp.gratis_id, cp.gratis_id_2, cp.resource_id, cp.price, b.title as b_title, c.url as master_master_url, c1.url as master_cat_url, c2.url as sub_cat_url FROM _content_products cp "
            . " LEFT JOIN _content_brand b ON b.resource_id = cp.brand "
            . " LEFT JOIN categories_content cc ON cp.resource_id = cc.content_resource_id "
            . " LEFT JOIN categories c2 ON c2.resource_id = cc.category_resource_id "
            . " LEFT JOIN categories c1 ON c1.resource_id = c2.parent_id "
            . " LEFT JOIN categories c ON c.resource_id = c1.parent_id ";

    $sortiranje = $f->getValue("sortiranje");
    if (!$sortiranje || !is_numeric($sortiranje)) {
        $order = " ORDER BY cp.price";
    } else {
        switch ($sortiranje) {
            case 1:
                $order = " ORDER BY cp.price ";
                break;
            case 2:
                $order = " ORDER BY cp.price DESC";
                break;
            case 3:
                $order = " ORDER BY cp.num_views DESC";
                break;
            case 4:
                $order = " ORDER BY cp.system_date DESC";
                break;
            case 6:
                $order = " ORDER BY b.title";
                break;
            default:
                $order = " ORDER BY cp.price ";
                break;
        }
    }

    $brendovi = mysqli_query($conn,"SELECT COUNT(id) as count_ids FROM _content_brand WHERE status = 1 AND lang = $currentLanguage") or die(mysqli_error($conn));
    $brojBrendovaArr = mysqli_fetch_array($brendovi);
    $brojBrendova = $brojBrendovaArr['count_ids'];

    $dummyString = "";
    for ($i = 0; $i < $brojBrendova; $i++) {
        $dummyString .= "brand%5B" . $i . "%5D, ";
    }

    $dummyArray = explode(", ", $dummyString);
    array_pop($dummyArray);

    $non_filters = array("min_cena", "max_cena", "filters", "page", "po_strani", "sortiranje", "brand[0]", "brand[1]", "brand[2]", "brand[3]", "brand[4]", "brand[5]", "brand[6]", "brand[7]", "brand[8]", "brand[9]", "brand[10]", "brand[11]", "brand[12]", "brand[13]");
    $non_filters = array_merge($non_filters, $dummyArray);

    
    $filtersAll = $f->getValue("filters");
    /* FILTERI DODAVANJE U QYERY i WHERE STATEMENT */
    if ($filtersAll == "true") {
        $urlEX = explode("&filters=true&", $REQUEST);
        $filtersHeadersAndValues = explode("&", $urlEX[1]);
        $counter = 0;
        foreach ($filtersHeadersAndValues as $partHV) {
            list($key, $value) = explode("=", $partHV);
            if (!in_array($key, $non_filters)) {
                $counter++;
                $preporukaQuerySQL .= " LEFT JOIN filter_joins fj$counter ON cp.resource_id = fj$counter.product_rid  ";
            }
        }
    }

    if ($catLastData->id != '') {
        $where = " WHERE "
                . " (c2.resource_id = $catLastData->resource_id OR c.resource_id = $catLastData->resource_id OR c1.resource_id = $catLastData->resource_id ) AND "
                . " (cp.status = 1 OR cp.master_status = 'Active') AND cp.lang = $currentLanguage AND cp.price > 0 ";
        /* if ($filtersAll == "true") { 
            DODATNO ZA FILTERE (MORAM DA PITAM ZBOG NIVOA KATEGORIJE)
        } */
    } else {
        $where = " WHERE "
                . " (c2.resource_id = $catMiddleData->resource_id OR c.resource_id = $catMiddleData->resource_id OR c1.resource_id = $catMiddleData->resource_id ) AND "
                . " (cp.status = 1 OR cp.master_status = 'Active') AND cp.lang = $currentLanguage AND cp.price > 0 ";
        if ($filtersAll == "true") { /* DODATNO ZA FILTERE (MORAM DA PITAM ZBOG NIVOA KATEGORIJE) */
            $urlEX = explode("&filters=true&", $REQUEST);
            $filtersHeadersAndValues = explode("&", $urlEX[1]);
            $counter = 0;
            foreach ($filtersHeadersAndValues as $partHV) {
                list($key, $value) = explode("=", $partHV);
                if (!in_array($key, $non_filters) && !in_array($value, $non_filters)) {
                    $counter++;
                    list($f_header, $f_values) = explode("=", $partHV);
                    $f_values_arr = explode("+", $f_values);
                    if ($counter == 1) {
                        $where .= " AND ( ( fj1.fh_id = '" . $f_header . "' AND ( ";
                    } else {
                        $where .= " AND ( fj$counter.fh_id = '" . $f_header . "' AND ( ";
                    }
                    $valuesForWhere = "";
                    foreach ($f_values_arr as $value_single) {
                        $value_single = str_replace("-", "", $value_single);
                        $valuesForWhere .= " fj$counter.fv_id = '$value_single' OR ";
                    }
                    $valuesForWhere = rtrim($valuesForWhere, " OR ");
                    $where .= $valuesForWhere . " ) )";
                }
            }
            $where .= "  ) ";
        }
    }

    $getMinPrice = $_GET['min_cena'];

    if (isset($getMinPrice) && $getMinPrice != "" && $getMinPrice > 1) {
        $where .= " AND cp.price >= " . $getMinPrice;
    }

    $getMaxPrice = $_GET['max_cena'];

    if (isset($getMaxPrice) && $getMaxPrice != "" && $getMaxPrice > 1) {
        $where .= " AND cp.price <= " . $getMaxPrice;
    }

    if (isset($_GET['cat'])) {
        $cat = implode(",", $_GET['cat']);
        $where .= " AND cc.category_resource_id IN ($cat)";
    }

    if (isset($_GET['brand'])) {
        $brandArray = array();
        foreach ($_GET['brand'] as $brandSintle) {
            $brandArray[] = "$brandSintle";
        }
        $brandSQL = implode(",", $brandArray);
        if (count($brandArray) == 1) {
            $where .= " AND cp.brand = $brandSQL";
        } else {
            $where .= " AND cp.brand IN ($brandSQL)";
        }
    }

    $poStrani = $f->getValue("po_strani");
    if (isset($poStrani) && is_numeric($poStrani)) {
        $_SESSION['perPage'] = $poStrani;
    } elseif ($_SESSION['perPage']) {
        $poStrani = $_SESSION['perPage'];
    } else {
        $poStrani = 32;
    }

    $page = $f->getValue("page");
    if (!$page || !is_numeric($page)) {
        $page = 1;
    }

    $offset = ($page - 1) * $poStrani;
    $limitSql = " LIMIT $offset, $poStrani";

    $preporukaQuerySQL .= $where;

    //$preporukaQuerySQL .= " GROUP BY (cp.resource_id) " . $order;
    $preporukaQuerySQL .= $order;
    
    $preporukaQueryForPagination = mysqli_query($conn,$preporukaQuerySQL) or die(mysqli_error($conn));
    $numRows = mysqli_num_rows($preporukaQueryForPagination);

    $preporukaQuerySQL .= $limitSql;
    
    $preporukaQuery = mysqli_query($conn,$preporukaQuerySQL) or die(mysqli_error($conn));

    $brojStrana = ceil($numRows / $poStrani);

    include_once("category-single-content-filter-top.php");
    ?>
    <div class="holderContent row">

        <div class="quarter-x3 right">
            <div class="productHolder productListIndex row">
                <?php
                if ($numRows > 0) {
                    while ($item = mysqli_fetch_object($preporukaQuery)) {
                        include ("little_product.php");
                    }
                } else {
                    ?>
                    <div class="full">
                        <h2 >Trenutno nemamo proizvoda u ovoj kategoriji ili pod ovim parametrima filtera</h2>
                    </div>
                <?php }
                ?>            </div>
            <div class="bottomFilter">
                <?php
                if ($brojStrana > 1) {
                    ?>
                    <div class="filterTop filterBottom">
                        <div class="clear">
                            <div class="paginacija right">
                                <?php
                                for ($i = 1; $i <= $brojStrana; $i++) {
                                    ?>
                                    <a class="pagination <?php if ($i == $page) echo 'active'; ?>" data-page="<?= $i; ?>" href="javascript:void();" ><?= $i; ?></a>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="quarter margin-vertical catSingle left">
            <?php
            if ($catLastData->id != '') {
                $preporukaQueryBrand = mysqli_query($conn,""
                        . "SELECT DISTINCT (cp.brand), b.title as b_title, b.url as b_url, b.resource_id as b_rid FROM _content_products cp "
                        . " LEFT JOIN _content_brand b ON cp.brand = b.resource_id "
                        . " LEFT JOIN categories_content cc ON cc.content_resource_id = cp.resource_id "
                        . " LEFT JOIN categories c3 ON cc.category_resource_id = c3.resource_id "
                        . " LEFT JOIN categories c2 ON c3.parent_id = c2.resource_id "
                        . " WHERE "
                        . " (c3.resource_id = $catLastData->resource_id OR c2.resource_id = $catLastData->resource_id ) AND "
                        . " product_image!='' AND (cp.status = 1 OR cp.master_status = 'Active') AND cp.lang = $currentLanguage ORDER BY b.title") or die(mysqli_error($conn));
                /*
                  $preporukaQueryPrice = mysqli_query($conn,""
                  . "SELECT min(cp.price) as min_price, max(cp.price) as max_price FROM _content_products cp "
                  . " LEFT JOIN categories_content cc ON cc.content_resource_id = cp.resource_id "
                  . " LEFT JOIN categories c3 ON cc.category_resource_id = c3.resource_id "
                  . " LEFT JOIN categories c2 ON c3.parent_id = c2.resource_id "
                  . " WHERE "
                  . " (c3.resource_id = $catMiddleData->resource_id OR c2.resource_id = $catMiddleData->resource_id ) AND "
                  . "  (cp.status = 1 OR cp.master_status = 'Active') AND cp.lang = $currentLanguage AND cp.price > 0") or die(mysqli_error($conn)); */
            } else {
                $preporukaQueryBrand = mysqli_query($conn,""
                        . "SELECT DISTINCT (cp.brand), b.title as b_title, b.url as b_url, b.resource_id as b_rid FROM _content_products cp "
                        . " LEFT JOIN _content_brand b ON cp.brand = b.resource_id "
                        . " LEFT JOIN categories_content cc ON cc.content_resource_id = cp.resource_id "
                        . " LEFT JOIN categories c3 ON cc.category_resource_id = c3.resource_id "
                        . " LEFT JOIN categories c2 ON c3.parent_id = c2.resource_id "
                        . " WHERE "
                        . " (c3.resource_id = $catMiddleData->resource_id OR c2.resource_id = $catMiddleData->resource_id ) AND "
                        . " product_image!='' AND (cp.status = 1 OR cp.master_status = 'Active') AND cp.lang = $currentLanguage ORDER BY b.title") or die(mysqli_error($conn));
                /*
                  $preporukaQueryPrice = mysqli_query($conn,""
                  . "SELECT min(cp.price) as min_price, max(cp.price) as max_price FROM _content_products cp "
                  . " LEFT JOIN categories_content cc ON cc.content_resource_id = cp.resource_id "
                  . " LEFT JOIN categories c3 ON cc.category_resource_id = c3.resource_id "
                  . " LEFT JOIN categories c2 ON c3.parent_id = c2.resource_id "
                  . " WHERE "
                  . " (c3.resource_id = $catMiddleData->resource_id OR c2.resource_id = $catMiddleData->resource_id ) AND "
                  . "  (cp.status = 1 OR cp.master_status = 'Active') AND cp.lang = $currentLanguage AND cp.price > 0 ") or die(mysqli_error($conn));
                  $prices = mysqli_fetch_array($preporukaQueryPrice); */
            }
            include_once ("filters.php");
            ?>
        </div>
        <div class="leftSide quarter left">
        </div>
    </div>
</div>