<?php
$sliderQuery = mysqli_query($conn,"SELECT title, slika, text, text_naslov, link_ka FROM _content_slider WHERE slika!='' AND status = 1 AND lang = $currentLanguage ORDER BY ordering");
?>
<div id="slider" class="clear margin-vertical">
    <div class="flexslider">
        <ul class="slides"><?php
            while ($slid = mysqli_fetch_object($sliderQuery)) {
                ?>
                <li>
                    <div class='slide-img'>
                        <a href="<?= ($slid->link_ka) ? $slid->link_ka : "javascript:"; ?>" title="<?= $slid->title; ?>">
                            <img title="<?= $slid->title; ?>" src="/uploads/uploaded_pictures/_content_slider/885x417/<?= $slid->slika; ?>" alt='<?= $slid->title; ?>' />
                        </a>    
                        <?php
                        if ($slid->text != "" || $slid->text_naslov != "") {
                            ?>
                            <div class="slider-text">
                                <h2><?= $slid->text_naslov; ?></h2>
                                <p><?= $slid->text; ?></p>
                                <a href="<?= $slid->link_ka; ?>" title="Pogledajte <?= $slid->text_naslov; ?>">pogledajte</a>
                            </div>
                        <?php } ?>
                    </div><!-- slide-img -->
                    <div class="clear"></div>
                </li> <?php
            }
            ?>
        </ul>  
    </div><!-- flexslider -->
</div><!-- slider -->