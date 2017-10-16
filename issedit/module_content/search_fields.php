<?php
$wholeQuery = "SELECT ";

$selects = " cp.* ";

$joins = "";

$where = " WHERE cp.id != ''  ";

$order = "";

$langsQu = mysql_query("SELECT * FROM languages WHERE code!='' ");

$lang = $_GET["lang"];

if (mysql_num_rows($langsQu) > 1) {
    ?>
    <select name='lang' id='lang'>
        <?php while ($thisLang = mysql_fetch_object($langsQu)) {
            ?>
            <option <?= ($thisLang->id == $lang) ? "selected='selected'" : ""; ?> value='<?= $thisLang->id; ?>'><?= $thisLang->title; ?></option>
            <?php
        }
        ?>
    </select>    
    <?php
}



if (empty($lang)) {
    $lang = 1;
}

$where .= " AND cp.lang = $lang ";
?>

<?php if ($contentTypeContent->category_type != 0) { ?>
    <select name="sort_category" id="sort_category" class="w120">
        <option value="">All categories</option>

        <?php
        $sort_category = $_GET["sort_category"];
        $selects .= " , c.title as cat_title ";
        $joins .= " JOIN categories_content cc ON cp.resource_id = cc.content_resource_id JOIN categories c ON cc.category_resource_id = c.resource_id ";
        if ($sort_category != '') {
            $where .= " AND c.resource_id = $sort_category ";
        }
        ?>

        <?php $f->getCategoriesOptions($contentTypeContent->id, $sort_category, 0, 4); ?>
    </select>
<?php } ?>
<?php
$fieldsCol = new Collection("content_type_fields");
$fieldsArr = $fieldsCol->getCollection("WHERE content_type_id = $cid AND searchable = 1");

$imgFields = $fieldsCol->getCollection("WHERE content_type_id = $cid AND show_in_list = 1 AND field_type = 'image'");
if (count($imgFields) > 0) {
    $imgData = $imgFields[0];
}

$tableCustomFields = array();
$counter = 0;
foreach ($fieldsArr as $field) {
    $counter++;


    $this_sort = "";
    if ($_GET['sort_' . $field->column_name] != '') {
        $this_sort = $_GET['sort_' . $field->column_name];

        /* BUILDAM CUSTOM WHERE ZA UPIT */
    }
    if ($field->field_type == "select" || $field->field_type == "select_table") {
        ?>
        <select name='sort_<?= $field->column_name; ?>' id='sort_<?= $field->column_name; ?>' >
            <option value="">All <?= $field->column_name; ?></option>
            <?php
            switch ($field->field_type) {



                case "select":
                    $tableCustomFields[$field->title] = array($field->field_type, $field->column_name);
                    if ($this_sort != '') {

                        $where .= " AND cp." . $field->column_name . " = '" . $this_sort . "' ";
                    }
                    $explode = explode(",", $field->default_value);
                    foreach ($explode as $value) {
                        $selected = "";
                        if ($this_sort == $value) {
                            $selected = 'selected="selected"';
                        }
                        echo "<option $selected value='$value'>$value</option>";
                    }

                    break;

                case "select_table":
                    $tableCustomFields[$field->title] = array($field->field_type, $field->column_name);
                    list($table_name, $key, $valueField) = explode(",", $field->default_value);

                    $selects .= ", $table_name.title AS cust$counter ";

                    $joins .= " LEFT JOIN $table_name ON cp.$field->column_name = $table_name.$key ";

                    if ($this_sort != '') {

                        $where .= " AND cp." . $field->column_name . " = '" . $this_sort . "' ";
                    }

                    $newCollection = new Collection($table_name);

                    $newArray = $newCollection->getCollection("WHERE status = 1");

                    foreach ($newArray as $item) {
                        $selected = "";
                        if ($this_sort == $item->$key) {
                            $selected = 'selected="selected"';
                        }
                        echo "<option $selected value='" . $item->$key . "'>" . $item->$valueField . "</option>";
                    }

                    break;
            }
            ?>
        </select>    
        <?php
    } else if ($field->field_type == "text" || $field->field_type == "textarea") {
        $tableCustomFields[$field->title] = array($field->field_type, $field->column_name);
    }
}
?>
<select name="per_page">
    <?php
    if ($_GET['per_page'] != '') {
        $per_page = $_GET['per_page'];
    }
    ?>
    <option value="">Items per page</option>
    <option <?= ($per_page == "25") ? "selected='selected'" : ""; ?> value="25">25</option>
    <option <?= ($per_page == "50") ? "selected='selected'" : ""; ?> value="50">50</option>
    <option <?= ($per_page == "100") ? "selected='selected'" : ""; ?> value="100">100</option>
    <option <?= ($per_page == "500") ? "selected='selected'" : ""; ?> value="500">500</option>
    <option <?= ($per_page == "1000") ? "selected='selected'" : ""; ?> value="1000">1000</option>
    <option <?= ($per_page == "10000") ? "selected='selected'" : ""; ?> value="10000">10000</option>
</select>

<select name="status">
    <?php
    if ($_GET['status'] != '') {
        $status = $_GET['status'];

        $statusSQL = " AND cp.status = $status ";
    }
    ?>
    <option value="">All </option>
    <option <?= ($status == 1) ? "selected" : ""; ?> value="1">Active</option>
    <option <?= ($status == 2) ? "selected" : ""; ?> value="2">Inactive</option>
</select>


<input type="hidden" name="cid" value="<?= $cid; ?>" />

<input type="submit" class="button" value="Filter" />

<?php
/* PAGINATION */
$page = $_GET['page'];
if (empty($page)) {
    $page = 1;
}
if (empty($per_page)) {
    $per_page = 100;
}
$start_from = ($page - 1) * $per_page;

$limits = " LIMIT $start_from,$per_page";

if (empty($_GET['phrase'])) {
    $orderCustom = " GROUP BY (cp.resource_id) ORDER BY cp.ordering ";  

    $bigQuery = $wholeQuery . $selects . " FROM " . $contentTypeContent->table_name . " cp " . $joins . $where . $statusSQL . $orderCustom;
      
    
    $wholeQuery = $wholeQuery . $selects . " FROM " . $contentTypeContent->table_name . " cp " . $joins . $where . $statusSQL . $orderCustom . $limits;
} else {

    $phrase = $_GET['phrase'];

    /* UKOLIKO SE RADI PRETRAGA PREKO SEARCH-a */

    $where = " WHERE cp.id != '' AND cp.lang = 1 ";
    $where .= " AND ( cp.title LIKE '%$phrase%' OR cp.url LIKE '%$phrase%' OR cp.resource_id LIKE '%$phrase%' ";

    if ($contentTypeContent->category_type != 0) {
        $where .= " OR c.title LIKE '%$phrase%' OR c.url LIKE '%$phrase%' ";
    }
    foreach ($fieldsArr as $field) {
        switch ($field->field_type) {
            case "select":
            case "text":
            case "textarea":
                if ($phrase != '') {

                    $where .= " OR cp." . $field->column_name . " LIKE '%" . $phrase . "%' ";
                }


                break;

            case "select_table":

                list($table_name, $key, $valueField) = explode(",", $field->default_value);



                if ($phrase != '') {

                    $where .= " OR $table_name.title LIKE '%" . $phrase . "%' ";
                }

                break;
        }
    }
    $where .= " ) GROUP BY (cp.resource_id) ";



    $bigQuery = $wholeQuery . $selects . " FROM " . $contentTypeContent->table_name . " cp " . $joins . $where;
    $wholeQuery = $wholeQuery . $selects . " FROM " . $contentTypeContent->table_name . " cp " . $joins . $where . $limits;
}
?>
<br/><br/>
<?php 
$numProds = mysql_num_rows(mysql_query($bigQuery));

$numPages = ceil($numProds / $per_page);
?>
<script src="editable.js" type="text/javascript"></script>

<script type="text/javascript">
    function paginStart(toPage) {
        $("#page").val(toPage);
        $(".left_form").submit();
    }

    $(document).ready(function () {
        $('#check_all').change(function () {
            if ($(this).is(":checked")) {
                $(this).closest('table').find('input[type="checkbox"]:not(input[name="check_all"])').attr('checked', 'checked');
            } else {
                $(this).closest('table').find('input[type="checkbox"]:not(input[name="check_all"])').removeAttr('checked');
            }
        });

        $('.editable').editable(function (value, settings) {

            var id = $(this).attr('id');

            $.ajax({
                url: "work.php?action=editable",
                type: 'GET',
                data: {id: id, value: value}
            }).done(function (data) {
                console.log(data);
                return value;
            });
            return value;
        }, {
            type: 'text',
            submit: 'OK',
            indicator: 'Saving...',
        });

    });

</script>
<input type="hidden" name="phrase" value="<?= ($phrase != "") ? $phrase : ''; ?>"/>
<?php
echo "<input type='hidden' name='page' id='page' value='$page' />";
if ($numPages > 1) {
    echo "<div class='pagin'>";
    for ($i = 1; $i <= $numPages; $i++) {
        $class = '';
        if ($i == $page) {
            $class = 'class="active"';
        }
        echo "<a $class href='javascript:void(0);' onclick='paginStart($i);'>$i</a>";
    } 
    echo "</div>";
}
?>
<style type="text/css">
    .w590 {width: 50% !important;}
    table.fullwidth tbody tr:nth-child(even){background:#f3f4f4;}
    table.fullwidth tbody tr:hover {background:#80d1ff !important;}
    .pagin a {border-radius:15px; width:30px; height:30px; font-size:14px; text-decoration:none; color:#000; border:1px solid #000; text-align:center; line-height:30px; margin:0 1px; display:inline-block;}
    .pagin a:hover {background:#CCC;}
    .pagin a.active {background:#000; color:#FFF;}
    .button {cursor: pointer;}
</style>
