<?php
require_once ("../library/config.php");

$dateTime = date('Y-m-dTH:i:sP', time());
$dateTime = str_replace("UTC", "T", $dateTime);

$configSiteDomain = "http://www.skycomputer.rs/";
$content = "";

/* * *********************************** UPIS STRANA ************************************ */

$pages = mysql_query("SELECT url FROM _content_strane WHERE status = 1 AND text != ''") or die(mysql_error());
while ($page = mysql_fetch_object($pages)) {
    $content .= "
<url>
   <loc>" . $configSiteDomain . "strana/" . $page->url . "</loc>
   <lastmod>" . $dateTime . "</lastmod>
   <changefreq>monthly</changefreq>
   <priority>0.6</priority>
</url>";
}

/* * *********************************** UPIS AKTUELNOSTI ************************************ */

$actualAll = mysql_query("SELECT url FROM _content_aktuelnosti WHERE status = 1") or die(mysql_error());
while ($actual = mysql_fetch_object($actualAll)) {
    $content .= "
<url>
   <loc>" . $configSiteDomain . "aktuelnosti/" . $actual->url . "</loc>
   <lastmod>" . $dateTime . "</lastmod>
   <changefreq>monthly</changefreq>
   <priority>0.65</priority>
</url>";
}

/* * *********************************** UPIS ROBNIH MARKI ************************************ */

$brends = mysql_query("SELECT url FROM _content_brend WHERE status = 1") or die(mysql_error());
while ($brend = mysql_fetch_object($brends)) {
    $content .= "
<url>
   <loc>" . $configSiteDomain . "robne-marke/" . $brend->url . "</loc>
   <lastmod>" . $dateTime . "</lastmod>
   <changefreq>daily</changefreq>
   <priority>0.9</priority>
</url>";
}

/* * *********************************** UPIS 1 NIVOA KATEGORIJE ************************************ */

$categoriesAll = mysql_query("SELECT url FROM categories WHERE status = 1 AND level = 1") or die(mysql_error());
while ($categories3 = mysql_fetch_object($categoriesAll)) {
    $content .= "
<url>
   <loc>$configSiteDomain" . $categories3->url . "</loc>
   <lastmod>" . $dateTime . "</lastmod>
   <changefreq>daily</changefreq>
   <priority>0.9</priority>
</url>";
}

/* * *********************************** UPIS 2 NIVOA KATEGORIJE ************************************ */

$categoriesAll = mysql_query("SELECT c.url as c_url, c1.url as c1_url FROM categories c "
        . " LEFT JOIN categories c1 ON c1.resource_id = c.parent_id "
        . " WHERE c.status = 1 AND c.level = 2") or die(mysql_error());
while ($categories3 = mysql_fetch_object($categoriesAll)) {
    $content .= "
<url>
   <loc>$configSiteDomain" . $categories3->c1_url . "/" . $categories3->c_url . "</loc>
   <lastmod>" . $dateTime . "</lastmod>
   <changefreq>daily</changefreq>
   <priority>0.9</priority>
</url>";
}

/* * *********************************** UPIS 3 NIVOA KATEGORIJE ************************************ */

$categoriesAll = mysql_query("SELECT c.url as c_url, c2.url as c2_url, c1.url as c1_url FROM categories c "
        . " LEFT JOIN categories c2 ON c2.resource_id = c.parent_id "
        . " LEFT JOIN categories c1 ON c1.resource_id = c2.parent_id "
        . " WHERE c.status = 1 AND c.level = 3") or die(mysql_error());
while ($categories3 = mysql_fetch_object($categoriesAll)) {
    $content .= "
<url>
   <loc>$configSiteDomain" . $categories3->c1_url . "/" . $categories3->c2_url . "/" . $categories3->c_url . "</loc>
   <lastmod>" . $dateTime . "</lastmod>
   <changefreq>daily</changefreq>
   <priority>0.9</priority>
</url>";
}

/* * *********************************** UPIS PROIZVODA ************************************ */

$categoriesAll = mysql_query("SELECT cp.url as url, cp.resource_id as rid, c2.url as c2_url, c1.url as c1_url FROM _content_proizvodi cp "
        . " LEFT JOIN categories_content cc ON cc.content_resource_id = cp.resource_id "
        . " LEFT JOIN categories c2 ON c2.resource_id = cc.category_resource_id "
        . " LEFT JOIN categories c1 ON c1.resource_id = c2.parent_id "
        . " ") or die(mysql_error());
while ($categories3 = mysql_fetch_object($categoriesAll)) {
    $content .= "
<url>
   <loc>$configSiteDomain" . $categories3->c1_url . "/" . $categories3->c2_url . "/" . $categories3->url . "/" . $categories3->rid . "</loc>
   <lastmod>" . $dateTime . "</lastmod>
   <changefreq>weekly</changefreq>
   <priority>0.85</priority>
</url>";
}

$xmlFile = file_get_contents("xmlcontent.html");
$xmlFile = str_replace(array("[{TIME}]","[{CONTENT}]"), array($dateTime, $content), $xmlFile);
file_put_contents("../../sitemap.xml", $xmlFile);