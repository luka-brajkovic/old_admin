<?php

$filterValues = new Collection("filter_values");

$filterCollection = new Collection("filter_headers");
$filterArr = $filterCollection->getCollection("WHERE cat_resource_id = '$rid' AND lang = '$language->id' ORDER BY ordering");

foreach ($filterArr as $header) {

    $returnString .= "<tr>";
    $returnString .= "<td id='td_$header->id'>";
    if ($header->title != '') {
        $returnString .= "<a id='a_$header->id' href='javascript:void(0);' onclick=\"makeInput('$header->id')\">" . $header->title . "</a>";
        $returnString .= "<input type='text' value='$header->title' class='hidInputs' id='input_$header->id' onblur=\"saveInput('$header->id','$header->title')\" />";
    } else {
        $returnString .= "<input type='text' value='$header->title' id='input_$header->id' onblur=\"saveInput('$header->id','$header->title')\" />";
    }


    $returnString .= "</td>";
    $returnString .= "<td id='td_select_$header->id'>";
    foreach ($show_types as $key => $value) {
        if ($header->show == $key) {
            $returnString .= "<a href='javascript:void(0);' id='a_show_$header->id' onclick=\"makeSelect('$header->id')\">" . $value . "</a>";
            $returnString .= "<select class='hidInputs' id='select_show_$header->id' onblur=\"saveSelect('$header->id','$key')\">";
            foreach ($show_types as $keyList => $valueList) {
                if ($keyList != 0) {
                    if ($header->show == $keyList) {
                        $returnString .= "<option selected='selected' value='$keyList'>$valueList</option>";
                    } else {
                        $returnString .= "<option value='$keyList'>$valueList</option>";
                    }
                }
            }
            $returnString .= "</select>";
        }
    }

    $returnString .= "</td>";

    $returnString .= "<td id='td_values_" . $header->id . "_" . $language->code . "'>";
    /* ADDING VALUES */
    $filter_values_arr = $filterValues->getCollection("WHERE fh_id = '$header->id' AND lang = '$language->id' ORDER BY title");
    foreach ($filter_values_arr as $filter_value) {
        $returnString .= "<span id='span_value_$filter_value->id'><a href='javascript:void(0);' onclick=\"makeInputValue($filter_value->id)\">$filter_value->title</a>";
        $returnString .= "<a style='float:right;' href='javascript:void(0);' onclick=\"deleteValue('$filter_value->id','$header->id','$language->code');\">Erase X</a>";
        $returnString .= "</span>";
    }
    $returnString .= "<p><input type='text' name='filter_value_" . $header->id . "' id='filter_value_$header->id' value='' placeholder='Filter value' /><a href='javascript:void(0);'  onclick=\"addFilterValue('$header->id','$language->code')\">Add +</a></p>";
    $returnString .= "</td>";
    $returnString .= "<td>";
    $returnString .= "<a href='javascript:void(0);' onclick=\"moveUp('$header->id','$language->code','$rid')\">move up</a> | ";
    $returnString .= "<a href='javascript:void(0);' onclick=\"moveDown('$header->id','$language->code','$rid')\">move down</a>";
    $returnString .= "</td>";
    $returnString .= "<td>";
    $returnString .= "<a href='javascript:void(0);' onclick=\"deleteHeader('$header->id','$language->code','$rid')\">Delete Header X</a>";
    $returnString .= "</td>";
    $returnString .= "</tr>";
}