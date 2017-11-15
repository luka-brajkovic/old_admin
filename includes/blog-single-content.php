<div class="container">
	<?php
	$breadcrumbs = array(
		"Aktuelnosti" => "/aktuelnosti",
		$pageData->title => "/aktuelnosti/" . $pageData->url
	);
	$f->breadcrumbs($breadcrumbs, $csTitle, $csName);
	?>
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