<?php
include_once ("library/config.php");

$urlAKTIVE = "/aktuelnosti";
$fancybox = 1;
$url = $f->getValue("url");

$pages = mysqli_query($conn,"SELECT * FROM _content_blog WHERE status = 1 AND lang = $currentLanguage AND url = '$url' LIMIT 0,1") or die(mysqli_error($conn));

if (mysqli_num_rows($pages) != 1) {
    $f->redirect("/poruka/404");
}

$pageData = mysqli_fetch_object($pages);

$cumbView = $pageData->num_views + 1;
mysqli_query($conn,"UPDATE _content_blog SET num_views = '$cumbView' WHERE resource_id = '$pageData->resource_id' AND url = '$url' LIMIT 1");

$urlAKTIVE = "/aktuelnosti";

if ($pageData->title_seo == "") {
    $titleSEO = $pageData->title;
} else {
    $titleSEO = $pageData->title_seo;
}
if ($pageData->desc_seo == "") {
    $descSEO = $pageData->uvod;
} else {
    $descSEO = $pageData->desc_seo;
}

if (is_file("uploads/uploaded_pictures/_content_blog/640x640/" . $pageData->slika)) {
    $imgSEO = "/uploads/uploaded_pictures/_content_blog/640x640/" . $pageData->slika;
}

$htmlTagAddOG = 'prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#"';
$ogType = "article";

include_once ("head.php");
if ($csFacebook != "") {
    ?>
    <meta property="article:author" content="<?= $csFacebook; ?>">
    <meta property="article:publisher" content="<?= $csFacebook; ?>">
<?php } ?>
</head>
<?php include_once 'header.php'; 
 include_once 'includes/blog-single-content.php';
include_once 'footer.php'; ?>
</body>
</html>



