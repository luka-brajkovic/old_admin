<style type="text/css">

    .filter_header_holder {float:left; width:20%; box-sizing: border-box;padding: 0px 5px;margin-bottom:10px;}
    .filter_header_holder:nth-of-type(5n+1) {clear: both}
    .filter_header {border:1px solid #CCC; padding-bottom:10px;}
    .filter_header h3 {background:#454545; line-height:30px; font-size:14px; color:#FFF; padding:0 10px; display:block; margin-bottom:10px;}
    .filter_header p {margin-bottom:0; padding-bottom:0;}
    .filter_header select {width:auto; margin-left:10px; display:block;}
    .filter_header label {width: auto;cursor: pointer}

</style>
<?php
include_once('extractHeaders.php');

if ($contentTypeEdit->category_type == 1) {

    $selectedCategory = $db->getValue("category_resource_id", "categories_content", "content_resource_id", $rid);

    if ($selectedCategory != '') {

        getBackFilterHeaders($selectedCategory, $language->id, $item->resource_id);
    }
}
if ($contentTypeEdit->category_type == 2) {

    $resCategories = new Collection("categories_content");
    $resCategoriesCollection = $resCategories->getCollection("WHERE content_resource_id = '$rid'");
    $selectedArray = array();
    foreach ($resCategoriesCollection as $resCat) {
        $selectedCategory = $resCat->category_resource_id;
        $selectedTitle = $db->getValue2("title", "categories", "resource_id", $selectedCategory, "lang", $language->id);
        echo "<hr>";
        echo "<hr>";
        echo "<h2>Filteri za $selectedTitle</h2>";

        if ($selectedCategory != '') {

            getBackFilterHeaders($selectedCategory, $language->id, $item->resource_id);
        }
    }
}
?>