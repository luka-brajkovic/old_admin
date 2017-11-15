<?php
include_once ("library/config.php");
$step1Class = "curactive";
$step2Class = $step3Class = $step4Class = "";

$titleSEO = $csName . " - pregled proizvoda u korpi";
$descSEO = "Pregledajte i uredite proizvode pre same kupovine.";
?>

<?php include_once ("head.php"); ?>
</head>
<body>
    <?php
    include_once ("header.php");
    include_once ("includes/cart-content.php");
    include_once ("footer.php");
    ?>
</body>
</html>