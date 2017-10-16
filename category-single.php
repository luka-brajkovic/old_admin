<?php
include_once ("library/config.php");

/* PRVI NIVO KATEGORIJE */
$cat_master_url = $f->getValue("cat_master");

$singleCategories = 1;
if ($cat_master_url == "proizvodi-na-akciji") {

    $cat_master_url = $f->getValue("sub_cat");

    include_once 'categories-akcija.php';
} else {

    /* DRUGI NIVO KATEGORIJE */
    $cat_sub_url = $f->getValue("sub_cat");
    /* OVO JE AKO IMA 3 NIVO KATEGORIJE */
    $cat_last_url = $f->getValue("last_cat");

    $catMasterData = mysql_query("SELECT * FROM categories WHERE url = '$cat_master_url' LIMIT 1");
    $catMasterData = mysql_fetch_object($catMasterData);

    if ($catMasterData->id == '') {
        $f->redirect("/poruka/404");
    }

    if (isset($_GET['cat'])) {

        $catQuery = "";
        $catQueryArr = array();
        foreach ($_GET['cat'] as $k => $cat) {
            $catQueryArr[] = "cat[$k]=$cat";
        }
        $catQuery = implode("&", $catQueryArr);
        //echo $catQuery;

        $catID = $_GET['cat'][0];
        if (count($_GET['cat']) > 1) {
            if ($cat_last_url != "") {
                $f->redirect('/' . $cat_master_url . '/' . $cat_sub_url . '&' . $catQuery);
            }
        } else {

            $category = new View('categories', $catID, 'resource_id');
            $f->redirect('/' . $cat_master_url . '/' . $cat_sub_url . '/' . $category->url);
        }
    }

    $filterCategory = $f->getValue('filter_category');
    $catSubQ = mysql_query("SELECT * FROM categories WHERE parent_id = $catMasterData->resource_id AND url='$cat_sub_url' AND status = 1 LIMIT 1") or die(mysql_error());

    $catMiddleData = mysql_fetch_object($catSubQ);
    $catMiddleDataURL = $catMiddleData->url;

    if ($catMiddleData->id == '') {
        $f->redirect("/poruka/404");
    }
    if ($cat_last_url != '') {
        /* AKO JE ZADNJA KATEGORIJA */
        $catLastQ = mysql_query("SELECT id, resource_id, url, title FROM categories WHERE parent_id = $catMiddleData->resource_id AND url='$cat_last_url' AND status = 1 LIMIT 0,1");
        $catLastData = mysql_fetch_object($catLastQ);
        $preporukaQuerySQLRangeMin = mysql_query(""
                . " SELECT MIN(cp.price) as min_price FROM _content_proizvodi cp "
                . " LEFT JOIN categories_content cc ON cc.content_resource_id = cp.resource_id "
                . " LEFT JOIN categories c3 ON cc.category_resource_id = c3.resource_id "
                . " LEFT JOIN categories c2 ON c3.parent_id = c2.resource_id "
                . " LEFT JOIN categories c1 ON c2.parent_id = c1.resource_id "
                . " WHERE "
                . " (c3.resource_id = $catLastData->resource_id OR c2.resource_id = $catLastData->resource_id OR c1.resource_id = $catLastData->resource_id ) AND "
                . " (cp.status = 1 OR cp.master_status = 'Active') AND cp.lang = $currentLanguage AND cp.price > 0 ");
        $preporukaQuerySQLRangeMax = mysql_query(""
                . " SELECT MAX(cp.price) as  max_price  FROM _content_proizvodi cp "
                . " LEFT JOIN categories_content cc ON cc.content_resource_id = cp.resource_id "
                . " LEFT JOIN categories c3 ON cc.category_resource_id = c3.resource_id "
                . " LEFT JOIN categories c2 ON c3.parent_id = c2.resource_id "
                . " LEFT JOIN categories c1 ON c2.parent_id = c1.resource_id "
                . " WHERE "
                . " (c3.resource_id = $catLastData->resource_id OR c2.resource_id = $catLastData->resource_id OR c1.resource_id = $catLastData->resource_id ) AND "
                . " (cp.status = 1 OR cp.master_status = 'Active') AND cp.lang = $currentLanguage AND cp.price > 0 ");

        $rangeSpan = mysql_fetch_array($preporukaQuerySQLRangeMin);
        $rangeSpanMin = $rangeSpan[min_price];
        $rangeSpan = mysql_fetch_array($preporukaQuerySQLRangeMax);
        $rangeSpanMax = $rangeSpan[max_price];

        $pageData = $catLastData;
    } else {

        $preporukaQuerySQLRangeMin = mysql_query(""
                . " SELECT MIN(cp.price) as min_price FROM _content_proizvodi cp "
                . " LEFT JOIN categories_content cc ON cc.content_resource_id = cp.resource_id "
                . " LEFT JOIN categories c3 ON cc.category_resource_id = c3.resource_id "
                . " LEFT JOIN categories c2 ON c3.parent_id = c2.resource_id "
                . " WHERE "
                . " (c3.resource_id = $catMiddleData->resource_id OR c2.resource_id = $catMiddleData->resource_id ) AND "
                . " (cp.status = 1 OR cp.master_status = 'Active') AND cp.lang = $currentLanguage AND cp.price > 0 ");
        $preporukaQuerySQLRangeMax = mysql_query(""
                . " SELECT MAX(cp.price) as  max_price  FROM _content_proizvodi cp "
                . " LEFT JOIN categories_content cc ON cc.content_resource_id = cp.resource_id "
                . " LEFT JOIN categories c3 ON cc.category_resource_id = c3.resource_id "
                . " LEFT JOIN categories c2 ON c3.parent_id = c2.resource_id "
                . " WHERE "
                . " (c3.resource_id = $catMiddleData->resource_id OR c2.resource_id = $catMiddleData->resource_id ) AND "
                . " (cp.status = 1 OR cp.master_status = 'Active') AND cp.lang = $currentLanguage AND cp.price > 0 ");

        $rangeSpan = mysql_fetch_array($preporukaQuerySQLRangeMin);
        $rangeSpanMin = $rangeSpan[min_price];
        $rangeSpan = mysql_fetch_array($preporukaQuerySQLRangeMax);
        $rangeSpanMax = $rangeSpan[max_price];

        $pageData = $catMiddleData;
        if ($pageData->id == '') {
            $f->redirect("/poruka/404");
        }
    }

    if ($cat_last_url != "") {
        $canonical = $configSiteDomain . $cat_master_url . "/" . $cat_sub_url . "/" . $cat_last_url;
    } else {
        $canonical = $configSiteDomain . $cat_master_url . "/" . $cat_sub_url;
    }

    $titleSEO = $catMasterData->title . " - " . $pageData->title . " | $configSiteFirm";
    $descSEO = $catMasterData->title . " - " . $pageData->title . ", " . $configSiteDescription;

    $htmlTagAddOG = 'prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# product: http://ogp.me/ns/product#"';
    $ogType = "product.group";

    include_once ("head.php");
    ?>
    </head>
    <body>
        <?php
        include_once ("header.php");
        include_once ("category-single-content.php");
        include_once ("footer.php");
        ?>
        <script type="text/javascript">function buildQueryString(e, i, t, n){if ("cat" === e){var r = $("#category_filter ul li span.active").length; if (0 == r){var a = document.location.href, c = a.split("/"); c.pop(); var o = c.join(), l = o.replace(",", "/"); window.location.href = l}}var a = document.location.href; a = a.split("&"); var u = $.getQueryParameters(); delete u[a[0]], delete u[e], delete u[t], "" != e && "" != i && (u[e] = i), "" != t && "" != n && (u[t] = n), "" != e && "" == i && delete u[e], "" != t && "" == n && delete u[e]; var f = []; for (var h in u)jQuery.inArray(h, f) > 0?delete u[h]:f.push(h), "cat" == e && h.match(/cat/g) && delete u[h], "brand" == e && "" == i && h.match(/brand/g) && delete u[h]; if (u == {})var d = a[0]; else var s = serialize(u), d = a[0] + "&" + s; return console.log(d), d}jQuery.extend({getQueryParameters:function(e){return(e || document.location.href).replace(/(^\?)/, "").split("&").map(function(e){return e = e.split("="), this[e[0]] = e[1], this}.bind({}))[0]}}), serialize = function(e, i){var t = []; for (var n in e)if (e.hasOwnProperty(n)){var r = i?i + "[" + n + "]":n, a = e[n]; t.push("object" == typeof a?serialize(a, r):r + "=" + a)}return t.join("&")}, $(document).ready(function(){$("#po_strani").change(function(){var e = $(this).val(), i = buildQueryString("po_strani", e, "page", "1"); window.location.href = i}), $("#sortiranje").change(function(){var e = $(this).val(), i = buildQueryString("sortiranje", e, "", ""); window.location.href = i}), $(".pagination").click(function(){var e = $(this).data("page"), i = buildQueryString("page", e, "", ""); window.location.href = i}); var e = $(".filters").width(); $(".range-slider").jRange({from:<?= $rangeSpanMin; ?>, to:<?= $rangeSpanMax; ?>, step:1, scale:[<?= $rangeSpanMin; ?>,<?= $rangeSpanMax; ?>], format:"%s", width:e - 4, showLabels:!0, isRange:!0, onstatechange:function(e){var i = e.split(","), t = i[0], n = i[1], r = buildQueryString("min_cena", t, "max_cena", n); window.location.href = r}}), $(".checkClick.brand").click(function(){var e = ""; if ($(".checkClick.brand.active").each(function(i){e = e + "&brand[" + i + "]=" + $(this).parent().find('input[type="checkbox"]').val()}), "" == e)var i = buildQueryString("brand", "", "page", "1"); else{var i = buildQueryString("brand", "", "page", "1"); i += e}window.location.href = i, console.log(i)}), $(".checkClick.cat").click(function(){var e = ""; if ($(".checkClick.cat.active").each(function(i){e = e + "&cat[" + i + "]=" + $(this).parent().find('input[type="checkbox"]').val()}), console.log(e), "" == e)var i = buildQueryString("cat", "", "page", "1"); else{var i = buildQueryString("cat", "", "page", "1"); i += e}window.location.href = i}), $(".checkClick.filter").click(function(){var e = ($(this).parent().parent(), window.location.href), i = e.split("&filters=true"); i = i[0], console.log(i), $(".filter_group ul.check").find(".checkClick.filter.active").length > 0 && (i += "&filters=true", $(".filter_group ul.check").each(function(){if ($(this).find(".checkClick.filter.active").length > 0){var e = $(this).data("id"); i += "&" + e + "="; var t = ""; $(this).find(".checkClick.filter.active").each(function(){var e = $(this).parent().find("input[type='checkbox']").val(); t += "-" + e + "-+"}), t = t.substring(0, t.length - 1), i += t}})), console.log(i), window.location.href = i})}); function showMoreFilters(a){$("#doNotShowFilter-" + a).toggle("fast", "swing"), $(".filterArrowUp-" + a).toggle("fast", "swing"), $(".filterArrowDown-" + a).toggle("fast", "swing"), $("#open-" + a).html("Prikaži manje"), $(".active-" + a).html("Prikaži sve"), $("#open-" + a).toggleClass("active-" + a)}</script>
    </body>
    </html>
<?php } ?>