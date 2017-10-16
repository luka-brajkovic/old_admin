<?php
include_once ("library/config.php");

$urlAKTIVE = "/aktuelnosti";
$fancybox = 1;
$url = $f->getValue("url");

$pages = mysql_query("SELECT * FROM _content_aktuelnosti WHERE status = 1 AND lang = $currentLanguage AND url = '$url' LIMIT 0,1") or die(mysql_error());

if (mysql_num_rows($pages) != 1) {
    $f->redirect("/poruka/404");
}

$pageData = mysql_fetch_object($pages);

$cumbView = $pageData->num_views + 1;
mysql_query("UPDATE _content_aktuelnosti SET num_views = '$cumbView' WHERE resource_id = '$pageData->resource_id' AND url = '$url' LIMIT 1");

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
if ($pageData->keys_seo != "") {
    $keysSEO = $pageData->keys_seo  ;
}

if (is_file("uploads/uploaded_pictures/_content_aktuelnosti/640x640/" . $pageData->slika)) {
    $imgSEO = "/uploads/uploaded_pictures/_content_aktuelnosti/640x640/" . $pageData->slika;
}

$htmlTagAddOG = 'prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#"';
$ogType = "article";
?>
<?php include_once ("head.php");
if($configSiteFacebook!=""){
?>
<meta property="article:author" content="<?= $configSiteFacebook; ?>">
<meta property="article:publisher" content="<?= $configSiteFacebook; ?>">
<?php } ?>
</head>
<?php include_once 'header.php'; ?>
<div class="container">
    <ol class="pagePosition clear" vocab="http://schema.org/" typeof="BreadcrumbList">
        <li>
            <span>Vi ste ovde:</span>
        </li>
        <li property="itemListElement" typeof="ListItem">
            <a title="<?= $configSiteTitle; ?>" href="/" property="item" typeof="WebPage">
                <span property="name">Početna</span>
                <meta itemprop="position" content="1">
            </a>
        </li>
        <li property="itemListElement" typeof="ListItem">
            <a href="/aktuelnosti" title="Aktuelnosti" property="item" typeof="WebPage">
                <span property="name">Aktuelnosti</span>
                <meta itemprop="position" content="2">
            </a>
        </li>
        <li property="itemListElement" typeof="ListItem">
            <a href="/aktuelnosti/<?= $pageData->url; ?>" title="<?= $pageData->title; ?>" property="item" typeof="WebPage">
                <span property="name"><?= $pageData->title; ?></span>
                <meta itemprop="position" content="3">
            </a>
        </li>
    </ol>
    <div class="content singleNews">
        <h1 class='borderBottom'><?= $pageData->title; ?></h1>

        <?php
        if (is_file("uploads/uploaded_pictures/_content_aktuelnosti/640x640/$pageData->slika")) {
            echo "<figure class='right imgHolder'>";
            echo "<img src='/uploads/uploaded_pictures/_content_aktuelnosti/640x640/$pageData->slika' alt='$pageData->title' />";
            echo "</figure>";
        }
        ?>
        <em id="date"><i class="fa fa-calendar"></i> <?= "Objavljeno: "; ?> <?php
            if ($item->datum_objave != '0000-00-00 00:00:00') {
                $datum = $pageData->datum_objave;
            } else {
                $datum = $pageData->system_date;
            }
            echo $f->makeFancyDate($datum);
            ?></em>
        <p><strong> <?= $pageData->uvod; ?></strong></p>
        <?php
        echo $pageData->text;

        $f->printGalleryPage($pageData->galerija, $pageData->title, 5);

        if ($pageData->tagovi_vesti != '') {
            echo "<div class='tagsBlog'>";
            echo "<h4 id='tags'>Tagovi aktuelnosti:</h4>";
            $explosion = explode(",", trim($pageData->tagovi_vesti));
            foreach ($explosion as $tag) {
                echo "<a class='tag' title='Sve aktuelnosti " . $tag . "' href='/aktuelnosti?tag=" . $f->generateUrlFromText($tag) . "'><i class='fa fa-tag'></i> $tag</a>";
            }
            echo "</div>";
        }
        echo "<br clear='all'/>";
        ?>
    </div>
</div>
<?php include_once 'footer.php'; ?>
</body>
</html>



