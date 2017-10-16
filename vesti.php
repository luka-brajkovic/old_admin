<?php
include_once ("library/config.php");

$urlAKTIVE = "/aktuelnosti";

$additionalWheres = '';
$getTag = $f->getValue("tag");
if ($getTag != '') {
    $tagCurrent = $f->getValue("tag");
    $additionalWheres = " AND LCASE(REPLACE(tagovi_vesti,' ','-')) LIKE ('%$tagCurrent%') ";
}

$addSeo = "";
$page = isset($_GET['strana']) ? $_GET['strana'] : 1;
if ($page > 1) {
    $addSeo = " - Strana $page";
}

$titleSEO = "Noviteti iz sveta tehnike, najnoviji modeli, najave i predstavljanje uređaja" . $addSeo;
$descSEO = "Pročitajte sve novitete vezane za tehniku nove generacija, šta nas to očekuje, najave, događaji i pregled uređaja $addSeo - " . $configSiteFirm;

include_once ("head.php");
?>
</head>
<body>
    <?php include_once 'header.php'; ?>
    <div class="container white borderGray after">
        <ul class="pagePosition clear" itemtype="http://data-vocabulary.org/BreadcrumbList">
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
                <a title="Aktuelnosti" href="/aktuelnosti" property="item" typeof="WebPage">
                    <span property="name">Aktuelnosti</span>
                    <meta property="position" content="2">
                </a>
            </li>
            <?php
            if ($getTag != '') {
                ?>
                <li>
                    <span><?= $getTag; ?></span>
                </li>
            <?php } ?>
        </ul>
        <div class="padding10 page content" itemscope itemtype="http://schema.org/Blog">
            <?php
            if ($getTag != '') {
                ?>
                <h1 class='borderBottom'>Aktuelnosti - <?= $tagCurrent; ?></h1>
                <?php
            } else {
                ?>            
                <h1 class='borderBottom'>Aktuelnosti, cene, snizenja, popusti</h1>
                <?php
            }

            $holeQuery = "SELECT datum_objave, url, slika, title, system_date, uvod, tagovi_vesti FROM _content_aktuelnosti WHERE status = 1 AND lang = $currentLanguage $additionalWheres ";

            $poStrani = 8;
            $offset = ($page - 1) * $poStrani;
            $limitSql = " LIMIT $offset, $poStrani ";
            $numRows = $db->numRows($holeQuery);
            $brojStrana = ceil($numRows / $poStrani);
            $orderSql = " ORDER BY datum_objave DESC, system_date DESC ";

            $holeQuery .= $orderSql . $limitSql;
            $masterQuery = mysql_query($holeQuery) or die(mysql_error());
            ?>
            <div class="pagHolder clear">
                <div class="paginacija right">
                    <?php
                    $req = $REQUEST;
                    if (strpos($req, "?strana")) {
                        list($req) = explode("?", $req);
                    }

                    if ($brojStrana > 1) {
                        for ($i = 1; $i <= $brojStrana; $i++) {
                            $pag = $req . "?strana=" . $i;
                            ?>
                            <a class="pagination <?php if ($i == $page) echo 'active'; ?>" data-page="<?= $i; ?>" href="<?= $pag; ?>" ><?= $i; ?></a>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
            <div>
                <?php
                while ($vest = mysql_fetch_object($masterQuery)) {
                    ?>
                    <div class="list_vest" itemscope itemprop="blogPost" itemtype="http://schema.org/BlogPosting">
                        <div class="row">
                            <meta itemprop="author" content="<?= $configSiteFirm; ?>">
                            <meta itemprop="datePublished" content="<?= str_replace(" ", "T", $vest->datum_objave); ?>+01:00">
                            <meta itemprop="dateModified" content="<?= str_replace(" ", "T", $vest->datum_objave); ?>+01:00">
                            <div class="none" itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
                                <div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
                                    <meta itemprop="url" content="<?= $configSiteDomain; ?>images/logo.jpg">
                                    <meta itemprop="width" content="250">
                                    <meta itemprop="height" content="136">
                                </div>
                                <meta itemprop="name" content="<?= $configSiteFirm; ?>">
                            </div>
                            <div class="fifth">
                                <div class="inner" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
                                    <a title='<?= $vest->title; ?>' href="/aktuelnosti/<?= $vest->url; ?>">
                                        <?php
                                        if (is_file("uploads/uploaded_pictures/_content_aktuelnosti/300x300/$vest->slika")) {
                                            echo "<img src='/uploads/uploaded_pictures/_content_aktuelnosti/300x300/$vest->slika' alt='$vest->title' />";
                                        } else {
                                            echo "<img src='/images/no-image.jpg' alt='No image' />";
                                        }
                                        ?>
                                    </a>
                                    <meta itemprop="url" content="<?= $configSiteDomain; ?>uploads/blog/640x640/<?= $vest->slika; ?>">
                                    <meta itemprop="width" content="640">
                                    <meta itemprop="height" content="640">
                                </div>
                            </div>
                            <div class="fifth-x4">
                                <h2 itemprop="headline"><?= $vest->title; ?></h2>
                                <em><i class="fa fa-calendar"></i> Objavljeno: <?php
                                    if ($vest->datum_objave != '0000-00-00 00:00:00') {
                                        $datum = $vest->datum_objave;
                                    } else {
                                        $datum = $vest->system_date;
                                    }
                                    echo $f->makeFancyDate($datum);
                                    ?></em>
                                <p itemprop="description"> <?= $vest->uvod; ?></p>
                                <div class="holderFooterVest row">
                                    <div class="quarter-x3 left">
                                        <?php
                                        if ($vest->tagovi_vesti != '') {
                                            $explosion = explode(",", trim($vest->tagovi_vesti));
                                            foreach ($explosion as $tag) {
                                                if ($f->generateUrlFromText($tag) == $tagCurrent) {
                                                    echo "<a class='tag currentTag' title='Sve aktuelnosti " . $tag . "' href='javascript:'><i class='fa fa-tag'></i> $tag</a>";
                                                } else {
                                                    echo "<a class='tag' title='Sve aktuelnosti" . $tag . "' href='/aktuelnosti?tag=" . $f->generateUrlFromText($tag) . "'><i class='fa fa-tag'></i> $tag</a>";
                                                }
                                            }
                                        }
                                        ?>
                                    </div>
                                    <div class="quarter">
                                        <a itemprop="mainEntityOfPage" class="right more" title='<?= $vest->title; ?>' href="/aktuelnosti/<?= $vest->url; ?>">detaljnije</a>
                                    </div>
                                </div>    
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <div class="pagHolder clear">
                <div class="paginacija right">
                    <?php
                    if ($brojStrana > 1) {
                        for ($i = 1; $i <= $brojStrana; $i++) {
                            $pag = $req . "?strana=" . $i;
                            ?>
                            <a class="pagination <?php if ($i == $page) echo 'active'; ?>" data-page="<?= $i; ?>" href="<?= $pag; ?>" ><?= $i; ?></a>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php include_once 'footer.php'; ?>
</body>
</html>

