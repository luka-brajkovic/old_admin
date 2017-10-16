<?php
include_once ("library/config.php");
if (!$isLoged) {
    $f->redirect('/prijava');
}
$step1Class = "pastactive";
$step2Class = "pastactive";
$step3Class = "curactive";
$step4Class = "";
?>

<?php include_once ("head.php"); ?>
</head>
<body>
    <?php
    include_once ("header.php");
    include_once ("cart-pay-content.php");
    include_once ("footer.php");
    ?>
</body>
</html>