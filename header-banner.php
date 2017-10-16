<div class="bannerLeft noDisplayMobile">
    <?php
    $bannerLevo = new View("_content_banneri", "3082", "resource_id");
    if ($bannerLevo->status == "1" && is_file("uploads/uploaded_files/_content_banneri/" . $bannerLevo->slika)) {
        ?>
        <a href="<?= ($bannerLevo->link_ka != "" && $bannerLevo->link_ka != "#") ? $bannerLevo->link_ka : "javascript:"; ?>" title="<?= $bannerLevo->title; ?>">
            <img src="/uploads/uploaded_files/_content_banneri/<?= $bannerLevo->slika; ?>?957235" alt="<?= $bannerLevo->title; ?>" title="<?= $bannerLevo->title; ?>" />
        </a>
    <?php } ?>
</div>
<div class="bannerRight noDisplayMobile">
    <?php
    $bannerDesno = new View("_content_banneri", "3083", "resource_id");
    if ($bannerDesno->status == "1" && is_file("uploads/uploaded_files/_content_banneri/" . $bannerDesno->slika)) {
        ?>
        <a href="<?= ($bannerDesno->link_ka != "" && $bannerDesno->link_ka != "#") ? $bannerDesno->link_ka : "javascript:"; ?>" title="<?= $bannerDesno->title; ?>">
            <img src="/uploads/uploaded_files/_content_banneri/<?= $bannerDesno->slika; ?>" alt="<?= $bannerDesno->title; ?>" title="<?= $bannerDesno->title; ?>" />
        </a>
    <?php } ?>
</div>