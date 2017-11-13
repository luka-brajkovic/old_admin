<?php
include_once ("library/config.php");
include_once ("head.php");
?>
<style type="text/css">#popup{position:fixed;width:100%;background:rgba(0,0,0,0.7);height:100%;z-index:9999;display:none}#popupInner{margin:100px auto;width:600px;background:#FFF;height:auto;padding:20px}#popup a.more{background:#307AB7;display:block;margin:10px auto 0;padding:10px;text-align:center;color:#FFF;width:20%;float:right}</style> 
</head>
<body>
    <div id="popup">
        <div id="popupInner">
            <h4>Upišite e-mail Vašeg naloga</h4>
            <p>Unesite u polje e-mail adresu Vašeg naloga, a u Vaš inbox će Vam stići poruka sa linkom preko kojeg možete postaviti novu šifru.</p>
            <form method="POST" action="" id="forgotPassForm" >
                <input class="box" type="text" value="" placeholder="Unesite e-mail" />
                <input class="box transition" type="submit" value="Pošalji link za promenu šifre" />
            </form>
            <a id="closer" href="javascript:void(0);" onclick="closeThis('#popup');" ><i class="fa fa-times transition"></i></a>
        </div>
    </div>
    <?php
    include_once ("header.php");
    include_once ("includes/registration-content.php");
    include_once ("footer.php");
    ?>
</body>
</html>