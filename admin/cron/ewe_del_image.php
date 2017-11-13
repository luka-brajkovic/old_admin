<?php

/*
  error_reporting(E_ALL);
  ini_set('display_errors', E_ALL); */
libxml_use_internal_errors(TRUE);
require_once ("../library/config.php");
set_time_limit(-1);
ini_set('memory_limit', -1);

$dimensions = new Collection("content_type_dimensions");
$dimsArr = $dimensions->getCollection("WHERE content_type_id = 72 AND url NOT LIKE 'g%' ORDER BY LENGTH(url),url");
$dimData = $dimsArr[0];
$dimUrlLit = $dimData->url;

$dimDatasecund = $dimsArr[2];
$dimUrlLitSecund = $dimDatasecund->url;

$dimDataShare = $dimsArr[3];
$dimUrlLitShare = $dimDataShare->url;

$dimDataBigs = $dimsArr[4];
$dimUrlLitBigs = $dimDataBigs->url;

$dimDataLittle = $dimsArr[1];
$dimDataLitList = $dimDataLittle->url;

$obrisano = 0;
$proizvoda = 0;
$content = new Collection("_content_products");
$inProd = $content->getCollection("WHERE status != 1");
foreach ($inProd as $product) {
    $proizvoda++;
    $dimenzije_array = array("$dimUrlLit", "$dimUrlLitSecund", "$dimUrlLitShare", "$dimUrlLitBigs", "$dimDataLitList");
    $slike_array = array(/* "$product->product_image", */ "$product->slika_1", "$product->slika_2", "$product->slika_3", "$product->slika_4", "$product->slika_5", "$product->slika_6", "$product->slika_7", "$product->slika_8", "$product->slika_9", "$product->slika_10");
    foreach ($dimenzije_array as $dim) {
        foreach ($slike_array as $slika) {
            if ($slika != "") {
                if (is_file("/home/skycompu/public_html/uploads/uploaded_pictures/_content_products/$dim/$slika")) {
                    $obrisano++;
                    unlink("/home/skycompu/public_html/uploads/uploaded_pictures/_content_products/$dim/$slika");
                }
            }
        }
    }
    if (is_file("/home/skycompu/public_html/uploads/uploaded_pictures/_content_products/$dimUrlLit/$product->product_image")) {
        $obrisano++;
        unlink("/home/skycompu/public_html/uploads/uploaded_pictures/_content_products/$dimUrlLit/$product->product_image");
    }
}
echo $proizvoda . " - UKUPNO NEAKTIVNIH PROIZVODA \r\n";
echo $obrisano . " - UKUPNO OBRISANO SLIKA";
