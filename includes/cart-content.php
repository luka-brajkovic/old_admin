<div class="container">
    <?php
    include_once ("cart-content-steps.php");
    if ($dontShow) {
        ?>
    <h1>Vaša korpa je prazna</h1>
    <a href="/" class="backHomeLink" title="<?= $csTitle; ?>">Vratite se na početnu stranu ></a>
    <?php
    } else {
        include_once ("cart-content-view.php");
    }
    ?>
</div>