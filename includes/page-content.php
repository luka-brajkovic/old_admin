<div class="container">
	<?php
	$breadcrumbs = array(
		$pageData->title => "/strana/".$pageData->url
	);
	$f->breadcrumbs($breadcrumbs, $csTitle, $csName);
	?>
    <div class="content">
        <h1><?= $pageData->title; ?></h1>
        <?= $pageData->text; ?>
    </div>
</div>