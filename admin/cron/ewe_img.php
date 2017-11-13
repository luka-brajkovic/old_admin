<?php
/*
  error_reporting(E_ALL);
  ini_set('display_errors', E_ALL); */
libxml_use_internal_errors(TRUE);
require_once("../library/config.php");
set_time_limit(-1);
ini_set('memory_limit', -1);

function prevuciSliku($urlPicture, $urlItem) {
    $apsolute_path = "/home/skycompu/public_html/";
    $rand = substr(md5($urlItem), 0, 2);
    $image_name = substr($urlItem, 0, 60) . "-" . $rand.".jpg";
    $dimenzije_array = array("71x57", "382x306", "630x354", "640x512", "210x168");

    $dimsArr = mysqli_query($conn,"SELECT * FROM content_type_dimensions WHERE content_type_id = 72 AND url NOT LIKE 'g%' ORDER BY LENGTH(url),url");
    while ($dims = mysqli_fetch_object($dimsArr)) {
        list($dimW, $dimH) = explode("x", $dims->url);
        $image_destination = $apsolute_path . "uploads/uploaded_pictures/_content_products/$dims->url/";
        $exploded = explode(".", $urlPicture);
        $imageType = end($exploded);
        $image_name = Functions::cropPictureISSFromURL($urlPicture, "$dimW", "$dimH", $imageType, $image_destination, $image_name);
    }
    return $image_name;
}

$dom = new DOMDocument();
$dom->load("https://www.ewe.rs/share/backend_231/?user=skycomp&secretcode=322e8&images=1");
$nodes = $dom->childNodes;

$sumaSlika = $uso = $upisujeSliku = 0;

foreach ($nodes as $n) {
    if ($n->nodeName == "products") {

        $childNodes = $n->getElementsByTagName('product');

        foreach ($childNodes as $product) {
            $uso++;
            $childNodes = $product->childNodes;
            $imagesArray = array();

            foreach ($childNodes as $node) {
                if ($node->nodeName == "id") {
                    $id = $node->textContent;
                    $id = htmlspecialchars($id);
                }

                if ($node->nodeName == "images") {
                    foreach ($node->childNodes as $imgNode) {
                        if ($imgNode->nodeValue != '' && $imgNode->nodeName == "image") {
                            array_push($imagesArray, $imgNode->nodeValue);
                        }
                    }
                }
            }

            /*             * *************************** SECA SLIKA *********************** */

            $inItem = new View("_content_products", "$id", "product_code");

            if (!empty($inItem->id) && count($imagesArray) > 0 && $inItem->status != 2 && !is_file("/home/skycompu/public_html/uploads/uploaded_pictures/_content_products/74x59/" . $inItem->product_image)) {
                $upisujeSliku++;

                foreach ($imagesArray as $key => $value) {
                    if ($key == 0) {
                        $inItem->product_image = prevuciSliku($value, $inItem->url . $key);
                    } else if ($key < 11) {
                        $slika = "slika_" . $key;
                        $inItem->$slika = prevuciSliku($value, $inItem->url . $key);
                    }
                    $inItem->Save();
                }
            }
            /*             * *************************** KRAJ SECE SLIKA *********************** */
        }
    }
}

echo "UBACIVANJE SLIKA \r\n\r\n";
echo "Ubacena bar jedna slika u $upisujeSliku proizvod \r\n\r\n";
echo $uso . " - Ukupno proizvoda \r\n\r\n";
echo "END";
?>