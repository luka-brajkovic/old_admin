<?php

require("../library/config.php");

//Here we have all values from POST and GET
$action = $_POST['action'];

switch ($action) {

    case 'deleteValue':

        $id = $_POST['id'];

        $header_id = $_POST['header_id'];

        $lang_code = $_POST['lang'];

        $filterJoins = new Collection("filter_joins");
        $filterJoinsArr = $filterJoins->getCollection("WHERE fv_id LIKE '%$id--,%'");
        foreach ($filterJoinsArr as $join) {
            $join->fv_id = str_replace($id . "--,", "", $join->fv_id);
            $join->Save();
        }

        $deleteValue = new View("filter_values", $id);
        $deleteValue->Remove();

        $lang = new View("languages", $lang_code, "code");

        $returnString = "";

        $filter_values = new Collection("filter_values");
        $filter_values_arr = $filter_values->getCollection("WHERE fh_id = '$header_id' AND lang = '$lang->id' ORDER BY title");
        foreach ($filter_values_arr as $filter_value) {
            $returnString .= "<span id='span_value_$filter_value->id'><a href='javascript:void(0);' onclick=\"makeInputValue($filter_value->id)\">$filter_value->title</a>";
            $returnString .= "<a style='float:right;' href='javascript:void(0);' onclick=\"deleteValue('$filter_value->id','$header_id','$lang->code');\">Erase X</a>";
            $returnString .= "</span>";
        }
        $returnString .= "<p><input type='text' name='filter_value_" . $header_id . "' id='filter_value_$header_id' value='' placeholder='Filter value' /><a href='javascript:void(0);'  onclick=\"addFilterValue('$header_id','$lang->code')\">Add +</a></p>";

        echo $returnString;

        break;

    case 'addValue':

        $header_id = $_POST['id'];

        $lang_code = $_POST['lang_code'];

        $value = $_POST['value'];

        $filter_values = new Collection("filter_values");

        $languagesCol = new Collection("languages");
        $languagesArr = $languagesCol->getCollection("WHERE code='$lang_code'");

        $mistake = '';

        foreach ($languagesArr as $lang) {
            $filter_values_arr = $filter_values->getCollection("WHERE url = '" . $f->generateUrlFromTextFilter($value) . "' AND lang = $lang->id AND fh_id = '$header_id'");
            if (count($filter_values_arr) > 0) {
                $mistake = 1;
            }
        }

        if (empty($mistake)) {
            foreach ($languagesArr as $lang) {
                $newValue = new View("filter_values");
                $newValue->lang = $lang->id;
                $newValue->title = $value;
                $newValue->url = $f->generateUrlFromTextFilter($value);
                $newValue->fh_id = $header_id;
                $newValue->Save();
            }

            $filter_values_arr = $filter_values->getCollection("WHERE fh_id = '$header_id' AND lang = '$lang->id' ORDER BY title");
            foreach ($filter_values_arr as $filter_value) {
                $returnString .= "<span id='span_value_$filter_value->id'><a href='javascript:void(0);' onclick=\"makeInputValue($filter_value->id)\">$filter_value->title</a>";
                $returnString .= "<a style='float:right;' href='javascript:void(0);' onclick=\"deleteValue('$filter_value->id','$header_id','$lang->code');\">Erase X</a>";
                $returnString .= "</span>";
            }
            $returnString .= "<p><input type='text' name='filter_value_" . $header_id . "' id='filter_value_$header_id' value='' placeholder='Filter value' /><a href='javascript:void(0);'  onclick=\"addFilterValue('$header_id','$lang->code')\">Add +</a></p>";

            echo $returnString;
        } else {
            echo $mistake;
        }


        break;

    case 'editShow':

        $id = $_POST['id'];

        $show = $_POST['show'];

        $show_types = explode(",", $_POST['show_types']);

        $header = new View("filter_headers", $id);
        $header->show = $show;
        $header->Save();
        $returnString = "";
        foreach ($show_types as $key => $value) {
            if ($key != '0') {
                if ($header->show == $key) {
                    $returnString .= "<a href='javascript:void(0);' id='a_show_$header->id' onclick=\"makeSelect('$header->id')\">" . $value . "</a>";
                    $returnString .= "<select class='hidInputs' id='select_show_$header->id' onblur=\"saveSelect('$header->id','$key')\">";
                    foreach ($show_types as $keyList => $valueList) {
                        if ($keyList != '0') {
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
        }

        echo $returnString;

        break;

    case 'editHeader':

        $id = $_POST['id'];

        $title = $_POST['title'];

        $filter = new View("filter_headers", $id);
        $filter->title = $title;
        $filter->url = $f->generateUrlFromTextFilter($title);
        $filter->Save();
        $returnString = "";
        $returnString .= "<a id='a_$filter->id' href='javascript:void(0);' onclick=\"makeInput('$filter->id')\">" . $filter->title . "</a>";
        $returnString .= "<input type='text' value='$filter->title' class='hidInputs' id='input_$filter->id' onblur=\"saveInput('$filter->id','$filter->title')\" />";

        echo $returnString;

        break;

    case 'addFilterHeader':

        $returnString = "";

        $cat_rec_id = $_POST['cat_id'];

        $rid = $cat_rec_id;

        $show_types = explode(",", $_POST['show_types']);

        $languagesCol = new Collection("languages");
        $languagesArr = $languagesCol->getCollection("WHERE code!=''");

        $filterCollection = new Collection("filter_headers");

        foreach ($languagesArr as $language) {

            $titleHeader = $_POST['title_' . $language->code];
            $showHeader = $_POST['show_' . $language->code];

            $newFilterHeader = new View("filter_headers");
            $newFilterHeader->lang = $language->id;
            $newFilterHeader->title = $titleHeader;
            $newFilterHeader->url = $f->generateUrlFromText($titleHeader);
            $newFilterHeader->cat_resource_id = $cat_rec_id;
            $newFilterHeader->show = $showHeader;
            $newFilterHeader->Save();

            $returnString .= "<table class='returned'><thead><tr><th>Filter Header</th><th>Show Type</th><th>Filter Values</th><th>Up / Down</th><th>Actions</th></tr></thead><tbody id='tbody_$language->code'>";

            include('writing-whole.php');

            $returnString .= "</tbody></table><br><br><br>";
        }

        echo $returnString;

        break;

    case 'deleteHeader':

        $id = $_POST['id'];

        $rid = $_POST['rid'];

        $lang_code = $_POST['lang_code'];

        $show_types = explode(",", $_POST['show_types']);

        $language = new View("languages", $lang_code, "code");

        $filterDelete = new View("filter_headers", $id);
        $filterJoins = new Collection("filter_joins");
        $filterValues = new Collection("filter_values");
        $filterValuesArr = $filterValues->getCollection("WHERE fh_id = $id AND lang = $language->id");
        foreach ($filterValuesArr as $valueRemove) {


            $filterJoinsArr = $filterJoins->getCollection("WHERE fv_id LIKE '%" . $valueRemove->id . "--,%'");
            foreach ($filterJoinsArr as $join) {
                $join->fv_id = str_replace($id . "--,", "", $join->fv_id);
                $join->Save();
            }

            $valueRemove->Remove();
        }

        $filterDelete->Remove();

        include('writing-whole.php');

        echo $returnString;

        break;

    case 'moveUp':

        $id = $_POST['header_id'];
        $cat_rid = $_POST['cat_rid'];
        $rid = $cat_rid;
        $lang_code = $_POST['lang_code'];
        $show_types = explode(",", $_POST['show_types']);

        $language = new View("languages", $lang_code, "code");

        $filterHeaders = new Collection("filter_headers");

        $hitedFH = new View("filter_headers", $id);

        $queryMAX = mysqli_query($conn,"SELECT MAX(ordering) AS maxHeader FROM filter_headers WHERE cat_resource_id = $cat_rid AND lang = $language->id AND ordering < $hitedFH->ordering ");

        while ($dataMAX = mysqli_fetch_array($queryMAX)) {
            if (empty($dataMAX['maxHeader'])) {
                $hitedFH->ordering = 1;
                $hitedFH->Save();
                $counter = 1;
                $filterHeadersArr = $filterHeaders->getCollection("WHERE cat_resource_id = $rid AND lang = $language->id AND id!=$id ORDER BY ordering");
                foreach ($filterHeadersArr as $fh) {
                    $counter++;
                    $fh->ordering = $counter;
                    $fh->Save();
                }
            } else {

                $hitedFH->ordering = $dataMAX['maxHeader'];
                $hitedFH->Save();
                $counter = $dataMAX['maxHeader'];
                $filterHeadersArr = $filterHeaders->getCollection("WHERE cat_resource_id = $rid AND lang = $language->id AND id!=$id AND ordering >= $counter ORDER BY ordering");
                foreach ($filterHeadersArr as $fh) {
                    $counter++;
                    $fh->ordering = $counter;
                    $fh->Save();
                }
            }
        }

        $returnString = "";

        include('writing-whole.php');

        echo $returnString;

        break;

    case 'moveDown':

        $id = $_POST['header_id'];
        $cat_rid = $_POST['cat_rid'];
        $rid = $cat_rid;
        $lang_code = $_POST['lang_code'];
        $show_types = explode(",", $_POST['show_types']);

        $language = new View("languages", $lang_code, "code");

        $filterHeaders = new Collection("filter_headers");

        $hitedFH = new View("filter_headers", $id);

        $queryMAX = mysqli_query($conn,"SELECT MIN(ordering) AS maxHeader FROM filter_headers WHERE cat_resource_id = $cat_rid AND lang = $language->id AND ordering > $hitedFH->ordering ");

        while ($dataMAX = mysqli_fetch_array($queryMAX)) {
            if (empty($dataMAX['maxHeader'])) {
                $filterHeadersArr = $filterHeaders->getCollection("WHERE cat_resource_id = $rid AND lang = $language->id ");
                $numItems = count($filterHeadersArr);
                $hitedFH->ordering = $numItems;
                $hitedFH->Save();
                $counter = $numItems;
                $filterHeadersArr = $filterHeaders->getCollection("WHERE cat_resource_id = $rid AND lang = $language->id AND id!=$id ORDER BY ordering DESC ");
                foreach ($filterHeadersArr as $fh) {
                    $counter--;
                    $fh->ordering = $counter;
                    $fh->Save();
                }
            } else {

                $hitedFH->ordering = $dataMAX['maxHeader'];
                $hitedFH->Save();
                $counter = $dataMAX['maxHeader'];
                $filterHeadersArr = $filterHeaders->getCollection("WHERE cat_resource_id = $rid AND lang = $language->id AND id!=$id AND ordering <= $counter ORDER BY ordering DESC");
                foreach ($filterHeadersArr as $fh) {
                    $counter--;
                    $fh->ordering = $counter;
                    $fh->Save();
                }
            }
        }

        $returnString = "";

        include('writing-whole.php');

        echo $returnString;

        break;
}
?>	