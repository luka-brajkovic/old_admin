<?php
include_once ("library/config.php");

$urlAKTIVE = "/robne-marke";

$titleSEO = "Sve robne marke proizvoda";
$descSEO = "Kod nas pronađite najbolje proizvode svih vodećih robnih marki.";

include_once ("head.php");
?>

</head>
<body>
    <?php
    include_once ("header.php");
    include_once ("includes/brands-content.php");
    include_once ("footer.php");
    ?>
    <script>

        $(window).scroll(function () {
            if ($(this).scrollTop() > 237) {
                $(".brendsHolder ul").css("position", "fixed");
                $(".brendsHolder ul").css("border-bottom", "1px solid #656565");
            } else {
                $(".brendsHolder ul").css("position", "relative");
                $(".brendsHolder ul").css("border-bottom", "1px solid #FFF");
            }
        });
    </script>
</body>
</html>