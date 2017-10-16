<?php
include_once ("library/config.php");
$urlAKTIVE = "/robne-marke";

$brandUrl = $f->getValue('url');

$brandObj = new View('_content_brend', $brandUrl, 'url');

if ($brandObj->resource_id == "") {
    $f->redirect("/poruka/404");
}

$cumbView = $brandObj->num_views + 1;
mysql_query("UPDATE _content_brend SET num_views = '$cumbView' WHERE resource_id = '$brandObj->resource_id' AND url = '$brandObj->url' LIMIT 1");

$titleSEO = $brandObj->title . " proizvodi";
$descSEO = "Sve $brandObj->title proizode možete pronaći kod nas, kao i mnogih drugih svetskih brendova po povoljnim cenama.";
$imgSEO = "/uploads/uploaded_pictures/_content_brend/630x354/" . $brandObj->logo;

include_once ("head.php");
?></head>
<body>
    <?php
    include_once ("header.php");
    include_once ("brand-single-content.php");
    include_once ("footer.php");
    ?>
    <script type="text/javascript">function buildQueryString(e, i, t, n){if ("cat" === e){var r = $("#category_filter ul li span.active").length; if (0 == r){var a = document.location.href, c = a.split("/"); c.pop(); var o = c.join(), l = o.replace(",", "/"); window.location.href = l}}var a = document.location.href; a = a.split("&"); var u = $.getQueryParameters(); delete u[a[0]], delete u[e], delete u[t], "" != e && "" != i && (u[e] = i), "" != t && "" != n && (u[t] = n), "" != e && "" == i && delete u[e], "" != t && "" == n && delete u[e]; var f = []; for (var h in u)jQuery.inArray(h, f) > 0?delete u[h]:f.push(h), "cat" == e && h.match(/cat/g) && delete u[h], "brand" == e && "" == i && h.match(/brand/g) && delete u[h]; if (u == {})var d = a[0]; else var s = serialize(u), d = a[0] + "&" + s; return console.log(d), d}jQuery.extend({getQueryParameters:function(e){return(e || document.location.href).replace(/(^\?)/, "").split("&").map(function(e){return e = e.split("="), this[e[0]] = e[1], this}.bind({}))[0]}}), serialize = function(e, i){var t = []; for (var n in e)if (e.hasOwnProperty(n)){var r = i?i + "[" + n + "]":n, a = e[n]; t.push("object" == typeof a?serialize(a, r):r + "=" + a)}return t.join("&")}, $(document).ready(function(){$("#po_strani").change(function(){var e = $(this).val(), i = buildQueryString("po_strani", e, "page", "1"); window.location.href = i}), $("#sortiranje").change(function(){var e = $(this).val(), i = buildQueryString("sortiranje", e, "", ""); window.location.href = i}), $(".pagination").click(function(){var e = $(this).data("page"), i = buildQueryString("page", e, "", ""); window.location.href = i}); $(".checkClick.brand").click(function(){var e = ""; if ($(".checkClick.brand.active").each(function(i){e = e + "&brand[" + i + "]=" + $(this).parent().find('input[type="checkbox"]').val()}), "" == e)var i = buildQueryString("brand", "", "page", "1"); else{var i = buildQueryString("brand", "", "page", "1"); i += e}window.location.href = i, console.log(i)}), $(".checkClick.cat").click(function(){var e = ""; if ($(".checkClick.cat.active").each(function(i){e = e + "&cat[" + i + "]=" + $(this).parent().find('input[type="checkbox"]').val()}), console.log(e), "" == e)var i = buildQueryString("cat", "", "page", "1"); else{var i = buildQueryString("cat", "", "page", "1"); i += e}window.location.href = i}), $(".checkClick.filter").click(function(){var e = ($(this).parent().parent(), window.location.href), i = e.split("&filters=true"); i = i[0], console.log(i), $(".filter_group ul.check").find(".checkClick.filter.active").length > 0 && (i += "&filters=true", $(".filter_group ul.check").each(function(){if ($(this).find(".checkClick.filter.active").length > 0){var e = $(this).data("id"); i += "&" + e + "="; var t = ""; $(this).find(".checkClick.filter.active").each(function(){var e = $(this).parent().find("input[type='checkbox']").val(); t += "-" + e + "-+"}), t = t.substring(0, t.length - 1), i += t}})), console.log(i), window.location.href = i})});</script>


</body>
</html>