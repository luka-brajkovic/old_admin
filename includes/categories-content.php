<div class="container">
    <div class="row">
        <?php
        if ($cat_master_url == "proizvodi-na-akciji") { /* OVO SU SAMO PROIZVODI NA AKCIJI */
            ?>
            <div class="holderContent clear full">
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
                        <a href="/proizvodi-na-akciji" property="item" typeof="WebPage">
                            <span property="name"><?= ($getKategoriju != "") ? $brandDatas->title . " - proizvodi na akciji" : "Svi proizvodi na akciji"; ?></span>
                        </a>
                        <meta property="position" content="2">
                    </li>
                </ol>
                <div class="akcPag paginacija right">
                    <?php
                    $holeQuery = "SELECT cp.*, b.title as b_title, c.url as master_cat_url, c1.url as sub_cat_url FROM _content_products cp "
                            . " LEFT JOIN _content_brand b ON b.resource_id = cp.brand "
                            . " LEFT JOIN categories_content cc ON cp.resource_id = cc.content_resource_id "
                            . " LEFT JOIN categories c1 ON c1.resource_id = cc.category_resource_id "
                            . " LEFT JOIN categories c ON c.resource_id = c1.parent_id "
                            . " WHERE $add (cp.status = 1 OR cp.master_status = 'Active') AND (cp.akcija = 'Da' OR cp.price < cp.old_price OR cp.master_price < cp.old_price)  ";

                    $poStrani = 32;
                    $page = $f->getValue("strana");
                    if (!$page || !is_numeric($page)) {
                        $page = 1;
                    }
                    $offset = ($page - 1) * $poStrani;
                    $limitSql = " LIMIT $offset, $poStrani ";
                    $numRows = $db->numRows($holeQuery);
                    $brojStrana = ceil($numRows / $poStrani);
                    $orderSql = " ORDER BY cp.ordering ASC ";

                    $holeQuery .= $orderSql . $limitSql;
                    $preporukaQuery = mysqli_query($conn,$holeQuery) or die(mysqli_error($conn));

                    for ($i = 1; $i <= $brojStrana; $i++) {
                        if ($brojStrana > 1) {
                            $req = $REQUEST;
                            if (strpos($req, "&strana")) {
                                list($req) = explode("&", $req);
                            }
                            $pag = $req . "&strana=" . $i;
                            ?>
                            <a class="pagination <?php if ($i == $page) echo 'active'; ?>" data-page="<?= $i; ?>" href="<?= $pag; ?>" ><?= $i; ?></a>
                            <?php
                        }
                    }
                    ?>
                </div>
                <form class="right quarter" action="" method="post">
                    <div class="quarter akcijaFilter">
                        <input type="hidden" name="cat_master" value="proizvodi-na-akciji">
                        <select name="choosecat" onchange="this.form.submit()">
                            <?php
                            $proizvodi_na_akciji = mysqli_query($conn,"SELECT cp.*,c1.title as cat_title,c1.resource_id as cat_rid FROM _content_products cp"
                                    . " LEFT JOIN categories_content cc ON cp.resource_id = cc.content_resource_id "
                                    . " LEFT JOIN categories c2 ON cc.category_resource_id = c2.resource_id "
                                    . " LEFT JOIN categories c1 ON c2.parent_id = c1.resource_id "
                                    . " WHERE cp.status = 1 AND (cp.akcija = 'Da' OR cp.price < cp.old_price OR cp.master_price < cp.old_price) GROUP BY (c1.resource_id) ORDER BY c1.ordering ") or die(mysqli_error($conn));
                            ?>
                            <option value="">Sve kategorije</option>
                            <?php
                            while ($itemAkcija = mysqli_fetch_object($proizvodi_na_akciji)) {
                                ?>
                                <option value="<?= $itemAkcija->cat_rid; ?>" <?= ($getKategoriju == $itemAkcija->cat_rid) ? "selected" : ""; ?>><?= $itemAkcija->cat_title; ?></option>
                                <?php
                            }
                            ?>

                        </select>
                        <i class="fa fa-angle-down"></i>
                    </div>
                </form>
                <h1><?= ($getKategoriju != "") ? $brandDatas->title . " - proizvodi na akciji" : "Svi proizvodi na akciji"; ?></h1>
                <div id="akcija_custom" class="productHolder">
                    <div class="productListIndex row">
                        <?php
                        while ($item = mysqli_fetch_object($preporukaQuery)) {
                            include ("little_product.php");
                        }
                        ?>        
                    </div>
                </div>
                <div class="akcPag paginacija right clear">
                    <?php
                    for ($i = 1; $i <= $brojStrana; $i++) {
                        if ($brojStrana > 1) {
                            $req = $REQUEST;
                            if (strpos($req, "&strana")) {
                                list($req) = explode("&", $req);
                            }
                            $pag = $req . "&strana=" . $i;
                            ?>
                            <a class="pagination <?php if ($i == $page) echo 'active'; ?>" data-page="<?= $i; ?>" href="<?= $pag; ?>" ><?= $i; ?></a>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>          
        <?php } else {
            ?>
            <div class="quarter margin-vertical">
                <?php
                $catMiddleDataURL = "";
                include_once ("index-content-categories.php");
                ?>
            </div>
            <div class="holderContent clear quarter-x3">
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
                    <?php if ($cat_master_url == "proizvodi-na-akciji") { ?>
                        <li>
                            <a href="/proizvodi-na-akciji">
                                <span>Proizvodi na akciji</span>
                            </a>
                        </li>
                    <?php } else {
                        ?>
                        <li>
                            <a href="/<?= $catMasterData->url; ?>">
                                <span><?= $catMasterData->title; ?></span>
                            </a>
                        </li>
                    <?php }
                    ?>
                </ol>
                <div class="catProductAll">
                    <?php
                    $subCatAll = mysqli_query($conn,"SELECT title, url, resource_id FROM categories WHERE parent_id = '$catMasterData->resource_id' AND status = 1 ORDER BY ordering") or die(mysqli_error($conn));
                    if (mysqli_num_rows($subCatAll) > 0) {
                        ?>
                        <h1><?= $catMasterData->title; ?> - podkategorije</h1>

                        <div class="catHolder row">
                            <?php
                            while ($subCat = mysqli_fetch_object($subCatAll)) {

                                $checkLast = mysqli_query($conn,"SELECT c3.resource_id as last_cat_rid FROM categories c3 "
                                        . " LEFT JOIN categories_content cc ON c3.resource_id = cc.category_resource_id "
                                        . " LEFT JOIN _content_products cp ON cp.resource_id = cc.content_resource_id "
                                        . " LEFT JOIN categories c2 ON c2.resource_id = c3.parent_id "
                                        . " WHERE (cp.status = 1 OR cp.master_status = 'Active') AND c2.resource_id = '$subCat->resource_id' GROUP BY (c3.resource_id) LIMIT 1") or die(mysqli_error($conn));
                                if (mysqli_num_rows($checkLast) > 0) {
                                    ?>
                                    <div class="subCatMaster quarter left">
                                        <div class="ispod">
                                            <a href="/<?= $catMasterData->url . "/" . $subCat->url; ?>" title="<?= $subCat->title; ?>"><?= $subCat->title; ?></a>
                                        </div>
                                    </div>
                                    <?php
                                } else {
                                    $subCatAlls = mysqli_query($conn,"SELECT c2.resource_id as cat_rid FROM categories c2 "
                                            . " LEFT JOIN categories_content cc ON c2.resource_id = cc.category_resource_id "
                                            . " LEFT JOIN _content_products cp ON cp.resource_id = cc.content_resource_id "
                                            . " WHERE (cp.status = 1 OR cp.master_status = 'Active') AND c2.resource_id = '$subCat->resource_id' GROUP BY (c2.resource_id) LIMIT 1") or die(mysqli_error($conn));
                                    if (mysqli_num_rows($subCatAlls) > 0) {
                                        ?>
                                        <div class="subCatMaster quarter left">
                                            <div class="ispod">
                                                <a href="/<?= $catMasterData->url . "/" . $subCat->url; ?>" title="<?= $subCat->title; ?>"><?= $subCat->title; ?></a>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>   

                            <?php }
                            ?>
                        </div>
                        <?php
                    }

                    $numRo = mysqli_num_rows($preporukaQuery);
                    if ($numRo > 15) {
                        ?>
                        <h1><?= $catMasterData->title; ?> - proizvodi na akciji</h1>
                        <div class="productHolder clear row">
                        <?php } elseif ($numRo > 0 && $numRo < 16) {
                            ?>
                            <h1><?= $catMasterData->title; ?> - proizvodi na akciji i najprodavaniji</h1>
                            <div class="productHolder clear row">
                            <?php } else { ?>
                                <h1><?= $catMasterData->title; ?> - najprodavaniji proizvodi</h1>
                                <div class="productHolder row">
                                    <?php
                                    $preporukaQuery = mysqli_query($conn,""
                                            . "SELECT cp.*, b.title as b_title, c.url as master_cat_url, c1.url as sub_cat_url FROM _content_products cp "
                                            . " LEFT JOIN _content_brand b ON b.resource_id = cp.brand "
                                            . " LEFT JOIN categories_content cc ON cp.resource_id = cc.content_resource_id "
                                            . " LEFT JOIN categories c1 ON c1.resource_id = cc.category_resource_id "
                                            . " LEFT JOIN categories c ON c.resource_id = c1.parent_id "
                                            . " WHERE (c.resource_id = $catMasterData->resource_id OR c1.resource_id = $catMasterData->resource_id ) AND "
                                            . " cp.product_image!='' AND (cp.status = 1 OR cp.master_status = 'Active') AND cp.lang = $currentLanguage ORDER BY cp.ordering ASC LIMIT 0,16") or die(mysqli_error($conn));
                                    $numRo = mysqli_num_rows($preporukaQuery);
                                }

                                $addedProd = "";
                                while ($item = mysqli_fetch_object($preporukaQuery)) {
                                    $addedProd .= $item->resource_id . ", ";
                                    include ("little_product.php");
                                }

                                if ($numRo < 16) {
                                    $addedProd = rtrim($addedProd, ", ");
                                    if ($addedProd != "") {
                                        $addNotIn = " AND cp.resource_id NOT IN ($addedProd) ";
                                    }
                                    $limitacija = 16 - $numRo;
                                    $preporukaQueryDopuna = mysqli_query($conn,""
                                            . "SELECT cp.*, b.title as b_title, c.url as master_cat_url, c1.url as sub_cat_url FROM _content_products cp "
                                            . " LEFT JOIN _content_brand b ON b.resource_id = cp.brand "
                                            . " LEFT JOIN categories_content cc ON cp.resource_id = cc.content_resource_id "
                                            . " LEFT JOIN categories c1 ON c1.resource_id = cc.category_resource_id "
                                            . " LEFT JOIN categories c ON c.resource_id = c1.parent_id "
                                            . " WHERE (c.resource_id = $catMasterData->resource_id OR c1.resource_id = $catMasterData->resource_id ) AND "
                                            . " cp.product_image!='' AND (cp.status = 1 OR cp.master_status = 'Active') $addNotIn AND cp.lang = $currentLanguage ORDER BY cp.num_views DESC LIMIT 0,$limitacija") or die(mysqli_error($conn));
                                    while ($item = mysqli_fetch_object($preporukaQueryDopuna)) {
                                        include ("little_product.php");
                                    }
                                }
                                ?>        
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>