<?php

require_once ("../library/config.php");
die();
$all = mysql_query("SELECT cp.* FROM _content_proizvodi cp  "
        . " LEFT JOIN _content_brend cb ON cp.brand = cb.resource_id "
        . " WHERE cb.title = 'SPEEDLINK' ") or die(mysql_error());
while ($product = mysql_fetch_object($all)) {
    // mysql_query("UPDATE _content_proizvodi SET brand = 15644 WHERE resource_id = $product->resource_id ") or die(mysql_error());
    echo $product->title . " - $product->brand<br>";
}