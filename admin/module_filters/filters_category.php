<h1>Filters for this category</h1>

<style type="text/css">

   	a.add_button {background:#454545; color:#FFF; text-decoration:none;  display:inline-block; width:100px; line-height:30px; text-align:center;}

   	table.returned {width: 100%; border: 1px solid #606060; margin-top: 20px; border-collapse: collapse; padding:0; }
   	table.returned thead tr th {background:#454545; padding:10px; color:#FFF; font-size:14px; text-align:left; }
   	table.returned td {padding: 10px;  border:1px solid #606060; font-size:14px; text-align:left; vertical-align:top; }
   	td span {display:block; background:#CCC; color:#000; padding:5px; margin-bottom:1px;}
   	td p {margin:10px 0 0 0;}
   	td a {text-decoration:none;}

   	.hidInputs {display:none;}

   	#ajax {width:100%; position:absolute; top:0; left:0; min-height:500px; height:100%; background: rgba(255,255,255,0.8); text-align:center; display: none;}
   	#ajax img{margin-top:100px; display: block; margin:250px auto;}

</style>

<?php $show_types = array(1 => 'Checkbox', 2 => 'Select'); ?>

<?php include("javascript.php"); ?>



<input type="text" id='filter_header_<?= $language->code; ?>' name='filter_header_<?= $language->code; ?>' value="" placeholder="Filter Header" />

<select id='show_type_<?= $language->code; ?>' name='show_type_<?= $language->code; ?>'>
    <?php
    foreach ($show_types as $key => $value) {
        echo "<option value='$key'>$value</option>";
    }
    ?>
</select>
<input type="hidden" name="filter_cat_rec_id" id="filter_cat_rec_id" value="<?= $rid; ?>" />
<a class='add_button' href='javascript:void(0);' onclick="addFilterHeader_<?= $language->code; ?>();">Add Filter Header</a>
<br/>
<div id='ajax'>
   	<img src='/admin/module_filters/loader.gif' alt='loader' />
</div>
<div id='filters_<?= $language->code; ?>'>
    <?php
    $filter_values = new Collection("filter_values");

    $returnString = "<table class='returned'><thead><tr><th>Filter Header</th><th>Show Type</th><th>Filter Values</th><th>Up / Down</th><th>Actions</th></tr></thead><tbody id='tbody_$language->code'>";

    $filterCollection = new Collection("filter_headers");
    $filterArr = $filterCollection->getCollection("WHERE cat_resource_id = $rid AND lang = $language->id ORDER BY ordering");
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
                    if ($header->show == $keyList) {
                        $returnString .= "<option selected='selected' value='$keyList'>$valueList</option>";
                    } else {
                        $returnString .= "<option value='$keyList'>$valueList</option>";
                    }
                }
                $returnString .= "</select>";
            }
        }
        $returnString .= "</td>";
        $returnString .= "<td id='td_values_" . $header->id . "_" . $language->code . "'>";
        /* ADDING VALUES */
        $filter_values_arr = $filter_values->getCollection("WHERE fh_id = '$header->id' AND lang = '$language->id' ORDER BY title");
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
    $returnString .= "</tbody></table>";
    echo $returnString;
    ?>
</div>
<br/><br/>
<div class="clear"></div>