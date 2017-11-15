<?php
include_once ("library/config.php");

$url = $f->getValue("url");

$pageQuery = mysqli_query($conn,"SELECT * FROM _content_pages WHERE status = 1 AND url = '$url' AND lang = $currentLanguage LIMIT 0,1");
$pageData = mysqli_fetch_object($pageQuery);

if ($pageData->id == '') {
    $f->redirect("/poruka/404");
}

$cumbView = $pageData->num_views + 1;
mysqli_query($conn,"UPDATE _content_pages SET num_views = '$cumbView' WHERE resource_id = '$pageData->resource_id' AND url = '$pageData->url' LIMIT 1");

$urlAKTIVE = "/strana/" . $pageData->url;
$fancyboxJS = 1;

if ($pageData->title_seo == "") {
    $titleSEO = $pageData->title . " - " . $csTitle;
} else {
    $titleSEO = $pageData->title_seo;
}
if ($pageData->desc_seo == "") {
    $descSEO = $pageData->title . " - " . $csDesc;
} else {
    $descSEO = $pageData->desc_seo;
}

include_once ("head.php"); ?></head>
<body>
    <?php
    include_once ("header.php");
    include_once 'includes/page-content.php';
    include_once ("footer.php");
    ?></body>
</html>