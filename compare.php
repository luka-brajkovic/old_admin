<?php
include_once ("library/config.php");

$naslovna = true;
$isCompare = true;

$fancyboxJS = 1;

if (!isset($_SESSION["compare"])) {
    $f->redirect("/");
}

include_once ("head.php");
?>

</head>
<body>
    <?php
    include_once ("header.php");

    $niz = $_SESSION["compare"];

    $whatCat = mysqli_query($conn,"SELECT cp.*,c4.url as c4_url, c3.url as c3_url "
            . " FROM _content_products cp"
            . " LEFT JOIN categories_content cc ON cp.resource_id = cc.content_resource_id "
            . " LEFT JOIN categories c4 ON cc.category_resource_id = c4.resource_id "
            . " LEFT JOIN categories c3 ON c4.parent_id = c3.resource_id "
            . " WHERE cp.`status` = 1 AND cp.`resource_id` IN (" . implode(",", $niz) . ")") or die(mysqli_error($conn));

    $whatCatArr = mysqli_fetch_array($whatCat);

    $catNumber4 = $whatCatArr["c4_url"];
    $catNumber3 = $whatCatArr["c3_url"];
    ?>
    <div class="container">
        <ul class="pagePosition clear" itemtype="http://data-vocabulary.org/Breadcrumb" itemscope="">
            <li>
                <span>Vi ste ovde:</span>
            </li>
            <li itemprop="itemListElement" itemscope="itemscope" itemtype="http://schema.org/ListItem">
                <a href="/" title="<?= $csTitle; ?>" itemprop="url">
                    <span itemprop="name">Početna</span>
                </a>
            </li>
            <li>
                <span itemprop="name">Uporedi</span>
            </li>
        </ul>

        <h1>Uporedi <a class="right addNew" href="<?= ($catNumber3) ? "/" . $catNumber3 : ""; ?><?= ($catNumber4) ? "/" . $catNumber4 : ""; ?>">Dodajte proizvod + </a>
        </h1>

        <div class="comparasion clear">

            <div class="left">
                <?php
                $compareArr = $_SESSION["compare"];
                ?>
                <table id="compareTable">
                    <tbody>
                        <tr>
                            <?php
                            $counter = 0;
                            foreach ($compareArr as $rid) {
                                $counter++;
                                $rid = str_replace("'", "", $rid);
                                $proizvod = new View("_content_products", $rid, "resource_id");
                                $brend = new View("_content_brand", $proizvod->brand, "resource_id");
                                echo "<td>";
                                ?>
                        <a href="<?= $whatCatArr["c3_url"] . "/" . $whatCatArr["c4_url"] . "/" . $proizvod->url . "/" . $rid; ?>" title="<?= $brend->title . " " . $proizvod->title; ?>">
                            <h3><?= $brend->title . " " . $proizvod->title; ?></h3>
                        </a>
                        <?php
                        if (is_file("uploads/uploaded_pictures/_content_products/$dimUrlLitSecund/$proizvod->product_image")) {
                            ?>

                            <a data-fancybox-group="productTitle" href="/uploads/uploaded_pictures/_content_products/<?= $dimUrlLitBigs . "/" . $proizvod->product_image; ?>" title="<?= $brend->title . " " . $proizvod->title; ?>" class="fancybox">
                                <img width="125" height="100" src='/uploads/uploaded_pictures/_content_products/<?= $dimUrlLitSecund . "/" . $proizvod->product_image; ?>' alt='$brend->title." ".$proizvod->title' />
                            </a>
                            <?php
                        } else {
                            ?>
                            <a data-fancybox-group="productTitle" href="/images/no-image.jpg" title="<?= $brend->title . " " . $proizvod->title; ?>" class="fancybox">
                                <img width="125" height="100" src='/images/no-image.jpg' alt='No image' />
                            </a>
                        <?php } ?>
                        <h4><?= ($proizvod->master_price > 0) ? number_format($proizvod->master_price, 2, ",", ".") : number_format($proizvod->price, 2, ",", "."); ?> rsd <?php if ($proizvod->old_price > 0) { ?><span>Stara cena: <?= number_format($proizvod->old_price, 2, ",", "."); ?> rsd</span>
                            <?php }
                            ?>
                        </h4>
                        <a class="addCart" href="javascript:addToCart('<?= $proizvod->resource_id; ?>','1','<?= ($proizvod->master_price > 0) ? $proizvod->master_price : $proizvod->price; ?>')" title="Dodaj u korpu"><i class="fa fa-cart-plus"></i> Dodaj u korpu</a>
                        <h6>Šifra proizvoda: <?= $proizvod->product_code; ?></h6>
                        <h6>Brend: <?php
                            $brandData = new View("_content_brand", $proizvod->brand, "resource_id");
                            echo $brandData->title;
                            ?></h6>
                        <h6>Kategorija: <?php
                            $categories = new Collection("categories");
                            $categoriesArr = $categories->getCollectionCustom(""
                                    . " SELECT c.* FROM categories c LEFT JOIN categories_content cc ON c.resource_id = cc.category_resource_id"
                                    . " WHERE cc.content_resource_id = $proizvod->resource_id ");
                            $catData = $categoriesArr[0];
                            echo $catData->title;
                            ?></h6>
                        <h6>Garancija: <?= $proizvod->warranty; ?></h6>

                        <?php
                        $filterCompare = mysqli_query($conn,"SELECT fv.title as value_title, fh.title as header_title FROM filter_values fv "
                                . " LEFT JOIN filter_joins fj ON fj.fv_id = fv.id "
                                . " LEFT JOIN filter_headers fh ON fh.id = fj.fh_id "
                                . " LEFT JOIN _content_products cp ON cp.resource_id = fj.product_rid "
                                . " WHERE fj.product_rid = $proizvod->resource_id") or die(mysqli_error($conn));

                        while ($compareFil = mysqli_fetch_object($filterCompare)) {
                            echo "<p>" . $compareFil->header_title . ": " . htmlspecialchars_decode($compareFil->value_title) . "</p>";
                        }
                        ?>
                        <?php echo "</td>"; ?>
                    <?php }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <?php
    include_once ("footer.php");
    ?>

</body>
</html>