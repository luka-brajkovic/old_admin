<?php
include_once ("library/config.php");

$urlAKTIVE = "/aktuelnosti";

$additionalWheres = '';
$getTag = $f->getValue("tag");
if ($getTag != '') {
    $tagCurrent = $f->getValue("tag");
    $additionalWheres = " AND LCASE(REPLACE(tagovi_vesti,' ','-')) LIKE ('%$tagCurrent%') ";
}

$addSeo = "";
$page = isset($_GET['strana']) ? $_GET['strana'] : 1;
if ($page > 1) {
    $addSeo = " - Strana $page";
}

$titleSEO = "Noviteti iz sveta tehnike, najnoviji modeli, najave i predstavljanje uređaja" . $addSeo;
$descSEO = "Pročitajte sve novitete vezane za tehniku nove generacija, šta nas to očekuje, najave, događaji i pregled uređaja $addSeo - " . $csName;

include_once ("head.php");
?>
</head>
<body>
    <?php 
    include_once 'header.php';
    include_once 'includes/blog-content.php';
    include_once 'footer.php'; ?>
</body>
</html>

