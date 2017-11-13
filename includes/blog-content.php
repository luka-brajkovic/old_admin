<div class="container white borderGray after">
    <ul class="pagePosition clear" itemtype="http://data-vocabulary.org/BreadcrumbList">
        <li>
            <span>Vi ste ovde:</span>
        </li>
        <li property="itemListElement" typeof="ListItem">
            <a title="Početna" href="/" property="item" typeof="WebPage">
                <span>Početna</span>

            </a>
            <meta property="position" content="1">
        </li>
        <li>
            <a title="Aktuelnosti" href="/aktuelnosti">
                <span>Aktuelnosti</span>
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

        $holeQuery = "SELECT datum_objave, url, slika, title, system_date, uvod, tagovi_vesti FROM _content_blog WHERE status = 1 AND lang = $currentLanguage $additionalWheres ";

        $poStrani = 8;
        $offset = ($page - 1) * $poStrani;
        $limitSql = " LIMIT $offset, $poStrani ";
        $numRows = $db->numRows($holeQuery);
        $brojStrana = ceil($numRows / $poStrani);
        $orderSql = " ORDER BY datum_objave DESC, system_date DESC ";

        $holeQuery .= $orderSql . $limitSql;
        $masterQuery = mysqli_query($conn, $holeQuery) or die(mysqli_error($conn));
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
            while ($vest = mysqli_fetch_object($masterQuery)) {
                ?>
                <div class="list_vest" itemscope itemprop="blogPost" itemtype="http://schema.org/BlogPosting">
                    <div class="row">
                        <meta itemprop="author" content="<?= $csName; ?>">
                        <meta itemprop="datePublished" content="<?= str_replace(" ", "T", $vest->datum_objave); ?>+01:00">
                        <meta itemprop="dateModified" content="<?= str_replace(" ", "T", $vest->datum_objave); ?>+01:00">
                        <div class="none" itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
                            <div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
                                <meta itemprop="url" content="<?= $csDomain; ?>images/logo.jpg">
                                <meta itemprop="width" content="250">
                                <meta itemprop="height" content="136">
                            </div>
                            <meta itemprop="name" content="<?= $csName; ?>">
                        </div>
                        <div class="fifth">
                            <div class="inner" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
                                <a title='<?= $vest->title; ?>' href="/aktuelnosti/<?= $vest->url; ?>">
                                    <?php
                                    if (is_file("uploads/uploaded_pictures/_content_blog/300x300/$vest->slika")) {
                                        echo "<img src='/uploads/uploaded_pictures/_content_blog/300x300/$vest->slika' alt='$vest->title' />";
                                    } else {
                                        echo "<img src='/images/no-image.jpg' alt='No image' />";
                                    }
                                    ?>
                                </a>
                                <meta itemprop="url" content="<?= $csDomain; ?>uploads/blog/640x640/<?= $vest->slika; ?>">
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