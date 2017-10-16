<?php
include_once ("library/config.php");

if (!$isLoged) {
    $f->redirect("/poruka/prijava");
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
            <h1>Sve vaše porudžbine</h1>

            <?php
            $korpe = mysql_query("SELECT * FROM korpa WHERE user_id = '$userData->id' AND system_date != '0000-00-00 00:00:00' ORDER BY system_date DESC") or die(mysql_error());
            while ($korpa = mysql_fetch_array($korpe)) {

                $totalQuery = mysql_query("SELECT cp.*,pk.cena,pk.kolicina FROM _content_proizvodi cp "
                        . " LEFT JOIN proizvodi_korpe pk ON cp.resource_id = pk.original_rid "
                        . " WHERE korpa_rid = " . $korpa['id'] . "") or die(mysql_error());
                $total = 0;
                while ($item = mysql_fetch_object($totalQuery)) {
                    $total += $item->cena * $item->kolicina;
                }
                ?>
                <div class="korpa_list">
                    <h2>Kupovina od: <?= $f->makeFancyDate($korpa["system_date"]); ?></h2>
                    <p>Ime i prezime: <strong><?= $korpa["ime"]; ?> <?= $korpa["prezime"]; ?></strong></p>
                    <?php
                    $cityc = new View("_content_gradovi", $korpa["grad"], 'resource_id');
                    ?>
                    <p>Adresa: <strong><?= $korpa["adresa"]; ?> <?= $korpa["naselje"]; ?>, <?= $korpa["zip"]; ?> <?= ($cityc->title != "" ) ? $cityc->title : $korpa->grad; ?></strong> <span style="font-size: 20px; color:red;" class="right">Ukupno: <?= number_format($total, 2, ",", " "); ?> rsd</span></p>
                    <div class="productHolder clear" id="petine">
                        <div class="productListIndex row">
                            <?php
                            $korpaProds = mysql_query("SELECT cp.*,pk.* FROM _content_proizvodi cp "
                                    . " LEFT JOIN proizvodi_korpe pk ON cp.resource_id = pk.original_rid "
                                    . " WHERE korpa_rid = " . $korpa['id'] . "") or die(mysql_error());
                            while ($item = mysql_fetch_object($korpaProds)) {
                                include ("little_product_order.php");
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <?php
    include_once ("footer.php");
    ?>     

</body>
</html>