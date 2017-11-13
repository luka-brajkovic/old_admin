<?php
require_once ("../library/config.php");

$csDomain = "https://www.skycomputer.rs/";
$content = "";

/* * *********************************** UPIS STRANA ************************************ */

$pages = mysqli_query($conn,"SELECT url FROM _content_pages WHERE status = 1 AND text != ''") or die(mysqli_error($conn));
while ($page = mysqli_fetch_object($pages)) {
    $content .= "
<url>
   <loc>" . $csDomain . "strana/" . $page->url . "</loc>
</url>";
}

/* * *********************************** UPIS AKTUELNOSTI ************************************ */

$actualAll = mysqli_query($conn,"SELECT url FROM _content_blog WHERE status = 1") or die(mysqli_error($conn));
while ($actual = mysqli_fetch_object($actualAll)) {
    $content .= "
<url>
   <loc>" . $csDomain . "aktuelnosti/" . $actual->url . "</loc>
</url>";
}

/* * *********************************** UPIS ROBNIH MARKI ************************************ */

$brends = mysqli_query($conn,"SELECT url FROM _content_brand WHERE status = 1") or die(mysqli_error($conn));
while ($brend = mysqli_fetch_object($brends)) {
    $content .= "
<url>
   <loc>" . $csDomain . "robne-marke/" . $brend->url . "</loc>
</url>";
}

/* * *********************************** UPIS 1 NIVOA KATEGORIJE ************************************ */

$categoriesAll = mysqli_query($conn,"SELECT url FROM categories WHERE status = 1 AND level = 1") or die(mysqli_error($conn));
while ($categories3 = mysqli_fetch_object($categoriesAll)) {
    $content .= "
<url>
   <loc>$csDomain" . $categories3->url . "</loc>
</url>";
}

/* * *********************************** UPIS 2 NIVOA KATEGORIJE ************************************ */

$categoriesAll = mysqli_query($conn,"SELECT c.url as c_url, c1.url as c1_url FROM categories c "
        . " LEFT JOIN categories c1 ON c1.resource_id = c.parent_id "
        . " WHERE c.status = 1 AND c.level = 2") or die(mysqli_error($conn));
while ($categories3 = mysqli_fetch_object($categoriesAll)) {
    $content .= "
<url>
   <loc>$csDomain" . $categories3->c1_url . "/" . $categories3->c_url . "</loc>
</url>";
}

/* * *********************************** UPIS 3 NIVOA KATEGORIJE ************************************ */

$categoriesAll = mysqli_query($conn,"SELECT c.url as c_url, c2.url as c2_url, c1.url as c1_url FROM categories c "
        . " LEFT JOIN categories c2 ON c2.resource_id = c.parent_id "
        . " LEFT JOIN categories c1 ON c1.resource_id = c2.parent_id "
        . " WHERE c.status = 1 AND c.level = 3") or die(mysqli_error($conn));
while ($categories3 = mysqli_fetch_object($categoriesAll)) {
    $content .= "
<url>
   <loc>$csDomain" . $categories3->c1_url . "/" . $categories3->c2_url . "/" . $categories3->c_url . "</loc>
</url>";
}

/* * *********************************** UPIS PROIZVODA ************************************ */

$categoriesAll = mysqli_query($conn,"SELECT cp.url as url, cp.resource_id as rid, c2.url as c2_url, c1.url as c1_url FROM _content_products cp "
        . " LEFT JOIN categories_content cc ON cc.content_resource_id = cp.resource_id "
        . " LEFT JOIN categories c2 ON c2.resource_id = cc.category_resource_id "
        . " LEFT JOIN categories c1 ON c1.resource_id = c2.parent_id "
        . " ") or die(mysqli_error($conn));
while ($categories3 = mysqli_fetch_object($categoriesAll)) {
    $content .= "
<url>
   <loc>$csDomain" . $categories3->c1_url . "/" . $categories3->c2_url . "/" . $categories3->url . "/" . $categories3->rid . "</loc>
</url>";
}

$xmlFile = file_get_contents("xmlcontent.html");
$xmlFile = str_replace(array("[{CONTENT}]","[{DOMAIN}]"), array($content, $csDomain), $xmlFile);
file_put_contents("../../sitemap.xml", $xmlFile);