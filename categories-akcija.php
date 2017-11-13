<?php
$catMasterData = new View("categories", $cat_master_url, "url");
$pageData = $catMasterData;
$urlJe = "proizvodi-na-akciji";

$titleSEO = $catMasterData->title . " - Proizvodi na akciji";
$descSEO = "Proizvodi na akciji iz kategorije $catMasterData->title, uvek jeftiniji od drugih, $csName";
?>

<?php include_once ("head.php"); ?>
</head>
<body>
    <?php
    include_once ("header.php");
    include_once ("includes/categories-content-akcija.php");
    include_once ("footer.php");
    ?>
</body>
</html>