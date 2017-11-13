<div class="container">
    <ol class="pagePosition clear" vocab="http://schema.org/" typeof="BreadcrumbList">
        <li>
            <span>Vi ste ovde:</span>
        </li>
        <li>
            <a title="<?= $csTitle; ?>" href="/">
                <span>Početna</span>

            </a>
            <meta itemprop="position" content="1">
        </li>
        <li property="itemListElement" typeof="ListItem">
            <a href="/aktuelnosti" title="Aktuelnosti" property="item" typeof="WebPage">
                <span property="name">Aktuelnosti</span>
                <meta itemprop="position" content="2">
            </a>
        </li>
        <li>
            <a href="/aktuelnosti/<?= $pageData->url; ?>" title="<?= $pageData->title; ?>">
                <span><?= $pageData->title; ?></span>
            </a>
        </li>
    </ol>
    <div class="content singleNews">
        <h1 class='borderBottom'><?= $pageData->title; ?></h1>

        <?php
        if (is_file("uploads/uploaded_pictures/_content_blog/640x640/$pageData->slika")) {
            echo "<figure class='right imgHolder'>";
            echo "<img src='/uploads/uploaded_pictures/_content_blog/640x640/$pageData->slika' alt='$pageData->title' />";
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

        $f->printGallery($pageData->galerija, $pageData->title, 5);

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