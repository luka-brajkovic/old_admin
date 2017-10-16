<?php

require_once ("../library/config.php");

$allCats = mysql_query("SELECT c.*, cp.status as cp_stat FROM categories c "
        . " LEFT JOIN categories_content cc ON cc.category_resource_id = c.resource_id "
        . " LEFT JOIN _content_proizvodi cp ON cp.resource_id = cc.content_resource_id "
        . " WHERE c.level = 2 GROUP BY (c.resource_id) ");

while ($cats = mysql_fetch_object($allCats)) {
    echo $cats->title . "<br>";
}