<?php
include_once ("library/config.php");
$titleSEO = "Prijavite se na našu online prodavnicu - " . $csName;
$descSEO = "Prijavite se za najbolje cene, popuste, promocije i nagrade na našoj online prodavnici " . $csName . ", sva tehniku ma jednom mestu!";
include_once ("head.php");
?>
<style type="text/css">
    #popup {position:fixed; width:100%; background: rgba(0,0,0,0.7); height:100%; z-index:9999; display: none;}
    #popupInner {margin:100px auto; width:600px; background: #FFF; height:auto; padding:20px; }
    #popup a.more {background:#307AB7; display:block; margin:10px auto 0 auto; padding:10px; text-align: center; color:#FFF; width: 20%; float:right;}
</style>

<meta name="google-signin-client_id" content="925914991864-jvk1vgh0o1rhr8n0q5d12v14gg8jdjpr.apps.googleusercontent.com">

<script src="https://apis.google.com/js/platform.js" async defer></script>
</head>
<body>
    <?php
    if ($REQUEST == '/postojeci/prijava') {
        ?>
        <div id="popup">
            <div id='popupInner'>
                <h1>Postojeći email</h1>
                <p>Poštovani, Vaš email već postoji u našoj bazi podataka, molimo Vas prijavite se!</p>
                <a href='javascript:' onclick='closePopup();' class="more">Zatvori</a>
            </div>
        </div>
    <?php } ?>
    <div id="popup">
        <div id="popupInner">
            <h4>Upišite e-mail Vašeg naloga</h4>
            <p>Unesite u polje e-mail adresu Vašeg naloga, a u Vaš inbox će Vam stići poruka sa linkom preko kojeg možete postaviti novu šifru.</p>
            <form method="POST" action="" id="forgotPassForm" >
                <input class="box" type="text" value="" placeholder="Unesite e-mail" />
                <input class="box transition" type="submit" value="Pošalji link za promenu šifre" />
            </form>
            <a class="more" href="javascript:" onclick='closePopup();' >Zatvori</a>
        </div>
    </div>


    <?php
    include_once ("header.php");
    include_once ("includes/login-content.php");
    include_once ("footer.php");
    ?>
</body>
</html>