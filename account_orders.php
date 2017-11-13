<?php
include_once ("library/config.php");

if (!$isLoged) {
    $_SESSION['infoTitle'] = "<h1>Morate biti prijavljeni</h1>";
    $_SESSION['infoText'] = "<p>Za sadržaj koji tražite morate biti prijavljeni.</p>";
    $f->redirect("/prijava");
}
?>

<?php include_once ("head.php"); ?>

</head>
<body>
    <?php
    include_once ("header.php");
    ?>
    <div class="container">
        <div class="content clear productListIndex ">
            <div class="nav_account clear">
                <?php include_once("includes/account_nav.php"); ?>
            </div>
            <h1>Sve vaše porudžbine</h1>

            <?php
            $korpe = mysqli_query($conn,"SELECT * FROM korpa WHERE user_id = '$userData->id' AND system_date != '0000-00-00 00:00:00' ORDER BY system_date DESC") or die(mysqli_error($conn));
            while ($korpa = mysqli_fetch_array($korpe)) {

                $totalQuery = mysqli_query($conn,"SELECT cp.*,pk.cena,pk.kolicina FROM _content_products cp "
                        . " LEFT JOIN proizvodi_korpe pk ON cp.resource_id = pk.original_rid "
                        . " WHERE korpa_rid = " . $korpa['id'] . "") or die(mysqli_error($conn));
                $total = 0;
                while ($item = mysqli_fetch_object($totalQuery)) {
                    $total += $item->cena * $item->kolicina;
                }
                ?>
                <div class="korpa_list">
                    <h2>Kupovina od: <?= $f->makeFancyDate($korpa["system_date"]); ?></h2>
                    <p>Ime i prezime: <strong><?= $korpa["ime"]; ?> <?= $korpa["prezime"]; ?></strong></p>
                    <?php
                    $cityc = new View("_content_cities", $korpa["grad"], 'resource_id');
                    ?>
                    <p>Adresa: <strong><?= $korpa["adresa"]; ?> <?= $korpa["naselje"]; ?>, <?= $korpa["zip"]; ?> <?= ($cityc->title != "" ) ? $cityc->title : $korpa->grad; ?></strong> <span style="font-size: 20px; color:red;" class="right">Ukupno: <?= number_format($total, 2, ",", " "); ?> rsd</span></p>
                    <div class="productHolder clear" id="petine">
                        <div class="productListIndex row">
                            <?php
                            $korpaProds = mysqli_query($conn,"SELECT cp.*,pk.* FROM _content_products cp "
                                    . " LEFT JOIN proizvodi_korpe pk ON cp.resource_id = pk.original_rid "
                                    . " WHERE korpa_rid = " . $korpa['id'] . "") or die(mysqli_error($conn));
                            while ($item = mysqli_fetch_object($korpaProds)) {
                                include ("includes/little_product_order.php");
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