<?php
include_once ("library/config.php");

$url = $f->getValue("url");

$pageQuery = mysql_query("SELECT * FROM _content_strane WHERE status = 1 AND url = '$url' AND lang = $currentLanguage LIMIT 0,1");
$pageData = mysql_fetch_object($pageQuery);

if ($pageData->id == '') {
    $f->redirect("/poruka/404");
}

$cumbView = $pageData->num_views + 1;
mysql_query("UPDATE _content_strane SET num_views = '$cumbView' WHERE resource_id = '$pageData->resource_id' AND url = '$pageData->url' LIMIT 1");

$urlAKTIVE = "/strana/" . $pageData->url;
$fancyboxJS = 1;

if ($pageData->title_seo == "") {
    $titleSEO = $pageData->title . " - " . $configSiteTitle;
} else {
    $titleSEO = $pageData->title_seo;
}
if ($pageData->desc_seo == "") {
    $descSEO = $pageData->title . " - " . $configSiteDescription;
} else {
    $descSEO = $pageData->desc_seo;
}
if ($pageData->keys_seo != "") {
    $keysSEO = $pageData->keys_seo;
}

include_once ("head.php"); ?></head>
<body>
    <?php
    include_once ("header.php");
    ?>
    <div class="container">
        <ul class="pagePosition clear" vocab="http://schema.org/" typeof="BreadcrumbList">
            <li>
                <span>Vi ste ovde:</span>
            </li>
            <li property="itemListElement" typeof="ListItem">
                <a title="Početna" href="/" property="item" typeof="WebPage">
                    <span property="name">Početna</span>
                    <meta property="position" content="1">
                </a>
            </li>
            <li property="itemListElement" typeof="ListItem">
                <a title="<?= $pageData->title; ?>" href="/strana/<?= $pageData->url; ?>" property="item" typeof="WebPage">
                    <span property="name"><?= $pageData->title; ?></span>
                    <meta property="position" content="2">
                </a>
            </li>
        </ul>
        <div class="content">
            <h1><?= $pageData->title; ?></h1>
            <?= $pageData->text; ?>
        </div>
    </div>
    <?php
    include_once ("footer.php");
    ?></body>
</html>