<script type="text/javascript">

    function deleteValue(id, header_id, lang) {

        var ret = confirm('Are You Sure That You Want to Erase This Value?');

        if (ret === true) {

            $("#ajax").show();
            $.ajax({
                type: "POST",
                url: "/issedit/module_filters/work.php",
                data: "action=deleteValue&id=" + id + "&header_id=" + header_id + "&lang=" + lang,
                success: function (msg) {
                    $('#td_values_' + header_id + "_" + lang).html(msg);
                    $("#ajax").hide();
                }
            });
        }

    }

    function addFilterValue(id, lang_code) {

        var filter_value = encodeURIComponent($("#filter_value_" + id).val());

        if (filter_value != '') {
            $("#ajax").show();
            $.ajax({
                type: "POST",
                url: "/issedit/module_filters/work.php",
                data: "action=addValue&id=" + id + "&value=" + filter_value + "&lang_code=" + lang_code,
                success: function (msg) {

                    var len = msg.length;

                    if (len < 4) {
                        alert('That value exist!');
                    } else {
                        $('#td_values_' + id + '_' + lang_code).html(msg);
                    }
                    $("#ajax").hide();
                }
            });

        } else {
            alert('You must enter filter value!');
        }

    }

    function makeSelect(id) {

        $("#a_show_" + id).css("display", "none");
        $("#select_show_" + id).css("display", "block");

    }

    function saveSelect(id, key) {

        var oldValue = $("#select_show_" + id).val();

        if (oldValue == key) {

            $("#a_show_" + id).css("display", "block");
            $("#select_show_" + id).css("display", "none");

        } else {

            $("#ajax").show();
            $.ajax({
                type: "POST",
                url: "/issedit/module_filters/work.php",
                data: "action=editShow&id=" + id + "&show=" + oldValue + "&show_types=Nebitno,<?= implode(",", $show_types); ?>",
                success: function (msg) {
                    $('#td_select_' + id).html(msg);
                    $("#ajax").hide();
                }
            });

        }

    }

    function makeInput(id) {

        $("#a_" + id).css("display", "none");
        $("#input_" + id).css("display", "block");

    }

    function addFilterHeader_<?= $language->code; ?>() {

        var temp_title = "";
        var temp_show = "";

        var temp_ceo_string = "";

<?php
foreach ($languages as $language_filter) {
    $stringZaProsledjivanje = "";
    $dataForLanguage = "";
    ?>
            temp_title = $("#filter_header_<?= $language_filter->code; ?>").val();
            temp_show = $("#show_type_<?= $language_filter->code; ?>").val();
    <?php
    $dataForLanguage = "title_" . $language_filter->code . "={TITLE_$language_filter->code}&show_$language_filter->code={SHOW_$language_filter->code}&";
    $stringZaProsledjivanje .= $dataForLanguage;
    ?>
            temp_ceo_string = temp_ceo_string + '<?= $stringZaProsledjivanje; ?>';
            temp_ceo_string = temp_ceo_string.replace("{TITLE_<?= $language_filter->code; ?>}", temp_title);
            temp_ceo_string = temp_ceo_string.replace("{SHOW_<?= $language_filter->code; ?>}", temp_show);

    <?php
}
?>

        var cat_rid = '<?= $rid; ?>';



        var prosledjivanje = temp_ceo_string;

        $("#ajax").show();
        $.ajax({
            type: "POST",
            url: "/issedit/module_filters/work.php",
            data: "action=addFilterHeader&" + prosledjivanje + "&cat_id=" + cat_rid + "&show_types=Nebitno,<?= implode(",", $show_types); ?>",
            success: function (msg) {
                var res = msg.split("<br><br><br>");
<?php
$counter = 0;
foreach ($languages as $language_filter) {
    ?>
                    $('#filters_<?= $language_filter->code; ?>').html(res[<?= $counter; ?>]);
    <?php
    $counter++;
}
?>

                $("#ajax").hide();
            }
        });
    }

    function deleteHeader(id, lang_code, cat_rid, show_types) {

        var ret = confirm('Are You sure that you want to delete filter header?');

        if (ret === true) {
            $("#ajax").show();
            $.ajax({
                type: "POST",
                url: "/issedit/module_filters/work.php",
                data: "action=deleteHeader&id=" + id + "&lang_code=" + lang_code + "&rid=" + cat_rid + "&show_types=Nebitno,<?= implode(",", $show_types); ?>",
                success: function (msg) {
                    $('#tbody_' + lang_code).html(msg);
                    $("#ajax").hide();
                }
            });
        }

    }

    function saveInput(id, oldValue) {

        var newValue = $("#input_" + id).val();

        if (newValue != oldValue) {

            $("#ajax").show();
            $.ajax({
                type: "POST",
                url: "/issedit/module_filters/work.php",
                data: "action=editHeader&id=" + id + "&title=" + newValue,
                success: function (msg) {
                    $('#td_' + id).html(msg);
                    $("#ajax").hide();
                }
            });

        } else {

            $("#input_" + id).css("display", "none");
            $("#a_" + id).css("display", "block");

        }
    }

    function moveUp(header_id, lang_code, cat_rid) {
        $("#ajax").show();
        $.ajax({
            type: "POST",
            url: "/issedit/module_filters/work.php",
            data: "action=moveUp&header_id=" + header_id + "&lang_code=" + lang_code + "&cat_rid=" + cat_rid + "&show_types=Nebitno,<?= implode(",", $show_types); ?>",
            success: function (msg) {
                $('#tbody_' + lang_code).html(msg);
                $("#ajax").hide();
            }
        });
    }

    function moveDown(header_id, lang_code, cat_rid) {
        $("#ajax").show();
        $.ajax({
            type: "POST",
            url: "/issedit/module_filters/work.php",
            data: "action=moveDown&header_id=" + header_id + "&lang_code=" + lang_code + "&cat_rid=" + cat_rid + "&show_types=Nebitno,<?= implode(",", $show_types); ?>",
            success: function (msg) {
                $('#tbody_' + lang_code).html(msg);
                $("#ajax").hide();
            }
        });
    }


</script>