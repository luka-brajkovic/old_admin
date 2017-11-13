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