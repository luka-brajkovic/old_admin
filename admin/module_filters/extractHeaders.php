<?php
/* ADD ZA FILTERE */

function getBackFilterHeaders($selectedCategory, $lang, $resource_id) {

    $filter_headers = new Collection("filter_headers");
    $filter_headers_arr = $filter_headers->getCollection("WHERE cat_resource_id = $selectedCategory AND lang = $lang ORDER BY ordering");
    if (count($filter_headers_arr) > 0) {
        $filter_values = new Collection("filter_values");
        $filter_joins = new Collection("filter_joins");
        foreach ($filter_headers_arr as $header) {
            if ($header->title != '') {
                ?>
                <div class="filter_header_holder">
                    <div class="filter_header">
                        <h3><?= $header->title; ?></h3>
                        <?php
                        if ($header->show > 0 && $header->show != '') {
                            $filterJoinsArr = $filter_joins->getCollection("WHERE lang=$lang AND fh_id = '$header->id' AND cat_rid = $selectedCategory AND product_rid = $resource_id");
                            $joinData = $filterJoinsArr[0];
                            if ($header->show == 1) {
                                /* CHECKBOX */
                                $filter_values_arr = $filter_values->getCollection("WHERE fh_id = '$header->id' AND lang = $lang ORDER BY title");
                                if (count($filter_values_arr) > 0) {
                                    foreach ($filter_values_arr as $value) {
                                        $queryIf = mysqli_query($conn,"SELECT * FROM filter_joins WHERE cat_rid = $selectedCategory AND lang = $lang AND product_rid = $resource_id AND fh_id = $header->id AND fv_id = $value->id") or die(mysqli_error($conn));
                                        $dataJoin = mysqli_fetch_object($queryIf);
                                        if ($dataJoin->fv_id == $value->id) {
                                            echo "<p><input id='id-" . $value->id . "' checked='checked' type='checkbox' name='" . $value->id . "_" . $header->id . "_" . $lang . "' value='$value->id' /> <label for='id-" . $value->id . "'> " . htmlspecialchars_decode($value->title) . "</label></p>";
                                        } else {
                                            echo "<p><input id='id-" . $value->id . "' type='checkbox' name='" . $value->id . "_" . $header->id . "_" . $lang . "' value='$value->id' /> <label for='id-" . $value->id . "'> " . htmlspecialchars_decode($value->title) . "</label></p>";
                                        }
                                    }
                                }
                            } else if ($header->show == 2) {
                                /* SELECT */
                                $filter_values_arr = $filter_values->getCollection("WHERE fh_id = '$header->id' AND lang = $lang");
                                if (count($filter_values_arr) > 0) {
                                    echo "<select name='" . $header->id . "_" . $lang . "' >";
                                    echo "<option value=''>Izaberi $header->title</option>";
                                    foreach ($filter_values_arr as $value) {
                                        if (strpos($joinData->fv_id, $value->id . "--,") !== false) {
                                            echo "<option selected = 'selected' value='$value->id'>$value->title</option>";
                                        } else {
                                            echo "<option value='$value->id'>$value->title</option>";
                                        }
                                    }
                                    echo "</select>";
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
                <?php
            }
        }

        return;
    } else {

        $categoriesCol = new Collection("categories");
        $categoriesArr = $categoriesCol->getCollection("WHERE lang = '$lang' AND resource_id = '$selectedCategory'");
        $catData = $categoriesArr[0];

        $parentsCats = $categoriesCol->getCollection("WHERE lang = '$lang' AND resource_id = '$catData->parent_id'");
        $parentCat = $parentsCats[0];

        if (empty($parentCat->id)) {
            
        } else if ($parentCat->id != '') {
            return getBackFilterHeaders($parentCat->resource_id, $lang, $resource_id);
        }
    }
}
