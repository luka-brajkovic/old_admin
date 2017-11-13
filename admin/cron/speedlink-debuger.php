<?php

require_once ("../library/config.php");
die();
$all = mysqli_query($conn,"SELECT cp.* FROM _content_products cp  "
        . " LEFT JOIN _content_brand cb ON cp.brand = cb.resource_id "
        . " WHERE cb.title = 'SPEEDLINK' ") or die(mysqli_error($conn));
while ($product = mysqli_fetch_object($all)) {
    // mysqli_query($conn,"UPDATE _content_products SET brand = 15644 WHERE resource_id = $product->resource_id ") or die(mysqli_error($conn));
    echo $product->title . " - $product->brand<br>";
}