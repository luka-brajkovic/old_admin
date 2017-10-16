<?php
/*
  error_reporting(E_ALL);
  ini_set('display_errors', E_ALL); */
libxml_use_internal_errors(TRUE);
require_once ("../library/config.php");
set_time_limit(-1);
ini_set('memory_limit', -1);

$dom = new DOMDocument();
$dom->load("http://www.ewe.rs/share/backend_231/?user=skycomp&secretcode=322e8");
$nodes = $dom->childNodes;

$nabavljac = "ewe";
$uneseno = 0;
$uso = 0;
$izmenjeno = 0;
$ukupno = 0;
?>
<!DOCTYPE html>
<html lang="sr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    </head>
    <?php
    foreach ($nodes as $n) {
        if ($n->nodeName == "products") {

            $childNodes = $n->getElementsByTagName('product');
            foreach ($childNodes as $product) {
                $uso++;

                $childNodes = $product->childNodes;
                $id = $manufacturer = $name = $url = $category = $subcategory = $price = $vat = $order = "";

                foreach ($childNodes as $node) {

                    if ($node->nodeName == "id") {
                        $id = $node->textContent;
                    }
                    if ($node->nodeName == "manufacturer") {
                        $manufacturer = $node->textContent;
                    }
                    if ($node->nodeName == "name") {
                        $name = $node->textContent;
                        $url = $f->generateUrlFromText($name);
                    }
                    if ($node->nodeName == "category") {
                        $category = $node->textContent;
                    }
                    if ($node->nodeName == "subcategory") {
                        $subcategory = $node->textContent;
                    }
                    if ($node->nodeName == "price") {
                        $price = $node->textContent;
                    }
                    if ($node->nodeName == "vat") {
                        $vat = $node->textContent;
                    }
                    /* RABAT CENA
                      if($node->nodeName=="price_rebate") {
                      $price_rebate = $node->textContent;
                      }
                     */
                }

                /*                 * *************************** UPIS KATEGORIJE I PODKATEGORIJE ********************* */

                $category = htmlspecialchars($category);
                $subcategory = htmlspecialchars($subcategory);
                
                if($category == "Notebook / Tablet"){            
                    $category = "Laptop i Tablet računari";
                    if($subcategory == "Notebook"){
                        $subcategory = "Laptop računari";
                    }
                    if ($subcategory == "Tableti") {
                        $subcategory = "Tablet računari";
                    }
                }
                
                if($category == "Outlet"){
                    if($subcategory == "Laptopovi"){
                        $subcategory = "Laptop računari";
                    }
                    if ($subcategory == "Tableti") {
                        $subcategory = "Tablet računari";
                    }
                }
                
                if($category == "Skeneri"){
                    if($subcategory == "A4"){
                        $subcategory = "A4 Skeneri";
                    }
                    if ($subcategory == "A3") {
                        $subcategory = "A3 Skeneri";
                    }
                }
                
                $content = new Collection("categories");

                $inCats = $content->getCollection("WHERE title = '$category' AND parent_id = 0");
                $inCat = $inCats[0];

                if (empty($inCat->id)) {

                    $resource = new View("resources");
                    $resource->table_name = "categories";
                    $resource->Save();

                    $newCat = new View("categories");
                    $newCat->resource_id = $resource->id;
                    $newCat->title = "$category";
                    $newCat->url = $f->generateUrlFromText($category);
                    $newCat->parent_id = 0;
                    $newCat->content_type_id = 72;
                    $newCat->status = 1;
                    $newCat->level = 1;
                    $newCat->lang = 1;
                    $newCat->ordering = 100;
                    $newCat->Save();
                }

                $MasterCat = mysql_query("SELECT resource_id, procenat_dodavanje_na_cenu, title, do_50000, do_70000, do_85000, do_100000, do_120000, do_150000, do_170000, do_200000, od_200001 FROM categories WHERE title = '$category' AND parent_id = '0' LIMIT 1");
                $MasterCat = mysql_fetch_object($MasterCat);

                $content = new Collection("categories");
                $inSubCats = $content->getCollection("WHERE title = '$subcategory' AND parent_id = '$MasterCat->resource_id' LIMIT 1");
                $inSubCat = $inSubCats[0];

                if (empty($inSubCat->id)) {

                    $resourceForSubcat = new View("resources");
                    $resourceForSubcat->table_name = "categories";
                    $resourceForSubcat->Save();

                    $newSubCat = new View("categories");
                    $newSubCat->resource_id = $resourceForSubcat->id;
                    $newSubCat->title = "$subcategory";
                    $newSubCat->url = $f->generateUrlFromText($subcategory);
                    $newSubCat->parent_id = $MasterCat->resource_id;
                    $newSubCat->content_type_id = 72;
                    $newSubCat->status = 1;
                    $newSubCat->level = 2;
                    $newSubCat->lang = 1;
                    $newSubCat->ordering = 0;
                    $newSubCat->Save();

                    $body = "Nova podkategorija $subcategory je dodata u kategoriji $MasterCat->title, i spremna je za dodavanje procenta, upisujete cist broj bez znaka za rocenat.";
                    $f->sendEmail("office@webdizajnsrbija.rs", "Skycomputer.rs", "office@skycomputer.rs", "Nova Ewe.rs podkategorija na sajtu Skycomputer.rs", $body);
                }

                /*                 * *************************** KRAJ UPISA KATEGORIJE I PODKATEGORIJE ********************* */

                $id = htmlspecialchars($id);

                /*                 * *************************** UPIS BRENDA ********************* */

                $inBrand = new View("_content_brend", "$manufacturer", "title");
                if (empty($inBrand->id)) {

                    $manufacturer = htmlspecialchars($manufacturer);
                    $inBrand = new View("_content_brend", "$manufacturer", "title");
                    if (empty($inBrand->id)) {
                        $manufacturer = trim($manufacturer);
                        $inBrand = new View("_content_brend", "$manufacturer", "title");
                        if (empty($inBrand->id)) {
                            $resourcesis = new View("resources");
                            $resourcesis->table_name = "_content_brend";
                            $resourcesis->Save();

                            $inBrand = new View("_content_brend");
                            $inBrand->resource_id = $resourcesis->id;
                            $inBrand->title = $manufacturer;
                            $inBrand->url = $f->generateUrlFromText($manufacturer);
                            $inBrand->num_views = 0;
                            $inBrand->status = 1;
                            $inBrand->system_date = date("Y-m-d H:i:s");
                            $inBrand->lang = 1;
                            $inBrand->Save();
                        }
                    }
                }
                $trenutniBrend = new View("_content_brend", "$manufacturer", "title");

                /*                 * *************************** KRAJ UPISA BRENDA ********************* */

                /*                 * *************************** UPIS PROIZVODA ********************* */
                /*                 * ****************************************** CENA ******************************************* */
                $price = $price * 1.2;
                $uhvacena_cena = "";

                if ($MasterCat->do_50000 != "" && $price < 50001 && $inSubCat->do_50000 == "") {
                    $price = $price + $MasterCat->do_50000;
                    $uhvacena_cena = "Da";
                } elseif ($MasterCat->do_70000 != "" && $price > 50000 && $price < 70001 && $inSubCat->do_70000 == "") {
                    $price = $price + $MasterCat->do_70000;
                    $uhvacena_cena = "Da";
                } elseif ($MasterCat->do_85000 != "" && $price > 70000 && $price < 85001 && $inSubCat->do_85000 == "") {
                    $price = $price + $MasterCat->do_85000;
                    $uhvacena_cena = "Da";
                } elseif ($MasterCat->do_100000 != "" && $price > 85000 && $price < 100001 && $inSubCat->do_100000 == "") {
                    $price = $price + $MasterCat->do_100000;
                    $uhvacena_cena = "Da";
                } elseif ($MasterCat->do_120000 != "" && $price > 100000 && $price < 120001 && $inSubCat->do_120000 == "") {
                    $price = $price + $MasterCat->do_120000;
                    $uhvacena_cena = "Da";
                } elseif ($MasterCat->do_150000 != "" && $price > 120000 && $price < 150001 && $inSubCat->do_150000 == "") {
                    $price = $price + $MasterCat->do_150000;
                    $uhvacena_cena = "Da";
                } elseif ($MasterCat->do_170000 != "" && $price > 150000 && $price < 170001 && $inSubCat->do_170000 == "") {
                    $price = $price + $MasterCat->do_170000;
                    $uhvacena_cena = "Da";
                } elseif ($MasterCat->do_200000 != "" && $price > 170000 && $price < 200001 && $inSubCat->do_200000 == "") {
                    $price = $price + $MasterCat->do_200000;
                    $uhvacena_cena = "Da";
                } elseif ($MasterCat->od_200001 != "" && $price > 200000 && $inSubCat->od_200001 == "") {
                    $price = $price + $MasterCat->od_200001;
                    $uhvacena_cena = "Da";
                }

                if ($inSubCat->do_50000 != "" && $price < 50001) {
                    $price = $price + $inSubCat->do_50000;
                    $uhvacena_cena = "Da";
                } elseif ($inSubCat->do_70000 != "" && $price > 50000 && $price < 70001) {
                    $price = $price + $inSubCat->do_70000;
                    $uhvacena_cena = "Da";
                } elseif ($inSubCat->do_85000 != "" && $price > 70000 && $price < 85001) {
                    $price = $price + $inSubCat->do_85000;
                    $uhvacena_cena = "Da";
                } elseif ($inSubCat->do_100000 != "" && $price > 85000 && $price < 100001) {
                    $price = $price + $inSubCat->do_100000;
                    $uhvacena_cena = "Da";
                } elseif ($inSubCat->do_120000 != "" && $price > 100000 && $price < 120001) {
                    $price = $price + $inSubCat->do_120000;
                    $uhvacena_cena = "Da";
                } elseif ($inSubCat->do_150000 != "" && $price > 120000 && $price < 150001) {
                    $price = $price + $inSubCat->do_150000;
                    $uhvacena_cena = "Da";
                } elseif ($inSubCat->do_170000 != "" && $price > 150000 && $price < 170001) {
                    $price = $price + $inSubCat->do_170000;
                    $uhvacena_cena = "Da";
                } elseif ($inSubCat->do_200000 != "" && $price > 170000 && $price < 200001) {
                    $price = $price + $inSubCat->do_200000;
                    $uhvacena_cena = "Da";
                } elseif ($inSubCat->od_200001 != "" && $price > 200000) {
                    $price = $price + $inSubCat->od_200001;
                    $uhvacena_cena = "Da";
                }

                if ($uhvacena_cena != "Da") {
                    if ($MasterCat->procenat_dodavanje_na_cenu != "" && $inSubCat->procenat_dodavanje_na_cenu == "") {
                        $price = $price + ($price / 100 * $MasterCat->procenat_dodavanje_na_cenu);
                    }
                    if ($inSubCat->procenat_dodavanje_na_cenu != "") {
                        $price = $price + ($price / 100 * $inSubCat->procenat_dodavanje_na_cenu);
                    }
                }
                /*                 * ****************************************** CENA ******************************************* */
                $inItem = new View("_content_proizvodi", "$id", "product_code");

                if (empty($inItem->id)) {
                    $uneseno++;

                    if ($order == "") {
                        $maxValuOrder = mysql_query("SELECT MAX(ordering) as max_order FROM _content_proizvodi");
                        $maxValuOrder = mysql_fetch_object($maxValuOrder);
                        $order = $maxValuOrder->max_order + 1;
                    } else {
                        $order++;
                    }

                    $resourceForProduct = new View("resources");
                    $resourceForProduct->table_name = "_content_proizvodi";
                    $resourceForProduct->Save();

                    $newItem = new View("_content_proizvodi");
                    $newItem->resource_id = $resourceForProduct->id;
                    $newItem->title = htmlspecialchars($name);
                    $newItem->url = $url;
                    $newItem->ordering = $order;
                    $newItem->num_views = 0;
                    $newItem->status = 1;
                    $newItem->system_date = date("Y-m-d H:i:s");
                    $newItem->updated = date("Y-m-d H:i:s");
                    $newItem->lang = 1;
                    $newItem->product_code = "$id";
                    $newItem->brand = $trenutniBrend->resource_id;
                    $newItem->nabavljac = $nabavljac;
                    $newItem->price = $price;
                    $newItem->Save();

                    /*                     * *************************** POVEZIVANJE KATEGORIJA I PROIZVODA ********************* */

                    $kategorijas = mysql_query("SELECT resource_id FROM categories WHERE title = '$subcategory' AND parent_id = '$MasterCat->resource_id' LIMIT 1");
                    $kategorijaCC = mysql_fetch_object($kategorijas);

                    $proizvodID = mysql_query("SELECT resource_id FROM _content_proizvodi WHERE product_code = '$id' LIMIT 1") or die(mysql_error());
                    $proizvodID = mysql_fetch_object($proizvodID);

                    $catContent = new View("categories_content");
                    $catContent->category_resource_id = $kategorijaCC->resource_id;
                    $catContent->content_resource_id = $proizvodID->resource_id;
                    $catContent->Save();

                    /*                     * *************************** KRAJ POVEZIVANJA KATEGORIJA I PROIZVODA ********************* */
                } else {
                    $izmenjeno++;

                    $inItem->title = htmlspecialchars($name);
                    $inItem->url = $url;
                    $inItem->product_code = "$id";
                    $inItem->brand = $trenutniBrend->resource_id;
                    $inItem->status = 1;
                    $inItem->price = $price;
                    $inItem->updated = date("Y-m-d H:i:s");
                    $inItem->nabavljac = $nabavljac;
                    $inItem->Save();
                }
            }
        }
    }

    $inactive = 0;
    $content = new Collection("_content_proizvodi");
    $inSubCats = $content->getCollection("WHERE updated < DATE_ADD(CURDATE(), INTERVAL -1 DAY) AND nabavljac = 'ewe' AND status != '2'");
    foreach ($inSubCats as $prod) {
        $inactive++;
        $prod->status = 2;
        $prod->Save();
    }

    $sklonjen = 0;
    $inSubCats = $content->getCollection("WHERE technical_description LIKE '%''%' OR title LIKE '%''%' OR marketing_description LIKE '%''%'");
    foreach ($inSubCats as $prod) {
        $sklonjen++;
        $prod->technical_description = str_replace("'", "&#39;", $prod->technical_description);
        $prod->title = str_replace("'", "&#39;", $prod->title);
        $prod->marketing_description = str_replace("'", "&#39;", $prod->marketing_description);
        $prod->Save();
    }

    echo "UBACIVANJE PROIZVODA \r\n\r\n";
    echo $uneseno . " - insert \r\n\r\n";
    echo $izmenjeno . " - updated \r\n\r\n";
    echo $uso . " - ukupno proizvoda \r\n\r\n";
    echo $inactive . " - inactive \r\n\r\n";
    echo $sklonjen . " - uklonjen apostrof \r\n\r\n";
    ?>
</html>