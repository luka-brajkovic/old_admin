<div class="container">
    <?php
    include_once ("cart-content-steps.php");
    if ($dontShow) {
        $f->redirect("/prijava");
    }
    include_once ("cart-login-content-login.php");
    ?>
</div>