<?php
$sessionID = session_id();
$dontShow = false;
$korpa = new View("korpa", $sessionID, 'session_id');
if ($korpa->id != '') {
    $numProizvoda = mysqli_query($conn, "SELECT * FROM proizvodi_korpe WHERE korpa_rid = $korpa->id");
    if (mysqli_num_rows($numProizvoda) == 0) {
        $dontShow = true;
    }
} else {
    $dontShow = true;
}
?>
<div class="cartSteps clear">
    <div class="quarter left">
        <a class="<?= $step1Class; ?>" href="/korpa" title="Moja korpa">

            <strong><i class="fa fa-shopping-cart stepIcon" aria-hidden="true"></i> 1. Vaša korpa <span>Sadržaj vaše korpe</span></strong>
            <i class="fa fa-caret-right arrow"></i></a>
    </div>
    <div class="quarter left">
        <a class="<?= $step2Class; ?>" href="/korpa-prijava" title="Prijava"> 
            <strong><i class="fa fa-user stepIcon" aria-hidden="true"></i> 2. Prijava <span>Nije obavezna</span></strong>
            <i class="fa fa-caret-right arrow"></i></a>
    </div>
    <div class="quarter left">
        <a class="<?= $step3Class; ?>" href="/korpa-placanje" title="Adresiranje"> 
            <strong><i class="fa fa-truck stepIcon" aria-hidden="true"></i> 3. Adresiranje <span>Podaci za isporuku</span></strong>
            <i class="fa fa-caret-right arrow"></i></a>
    </div>
    <div class="quarter right">
        <a class="<?= $step4Class; ?>" href="/korpa-dostava" title="Dostava">
            <strong><i class="fa fa-check stepIcon" aria-hidden="true"></i>  4. Dostava <span>Hvala što kupujete kod nas</span></strong>
            <i class="fa fa-caret-right arrow"></i></a>
    </div>
</div>
<script type="text/javascript">function izbaciIzKorpe(a){var b = confirm("Da li ste sigurni da želite da uklonite ovaj proizvod iz korpe?"); b && $.ajax({type:"POST", async:!0, url:"/work.php", data:"itemID=" + a + "&action=remove-from-cart", success:function(a){location.reload()}})}</script>