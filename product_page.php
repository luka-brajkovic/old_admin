<?php
if (is_numeric($_GET["rid"]) && isset($_GET["rid"])) {
    $rid = $_GET["rid"];
    $cookie_name = "user_seen";
    if (isset($_COOKIE[$cookie_name])) {
        $pogledani_proizvodi = $_COOKIE[$cookie_name];
        if (strpos($pogledani_proizvodi, "-" . $rid . "-") === false) {
            $pogledani_proizvodi = "-" . $rid . "-," . $pogledani_proizvodi;
            setcookie($cookie_name, $pogledani_proizvodi, time() + (86400 * 30), "/");
        }
    } else {
        $pogledani_proizvodi = "-" . $rid . "-";
        setcookie($cookie_name, $pogledani_proizvodi, time() + (86400 * 30), "/");
    }
}
include_once("library/config.php");

$url = $f->getValue('url');
$rid = $f->getValue('rid');

$fancyboxJS = 1;

$proizvod = mysqli_query($conn,"SELECT cp.*, c1.url as sub_cat_url, c.url as master_cat_url, cb.url as b_url, cb.logo as b_logo, cb.title as b_title FROM _content_products cp "
        . " LEFT JOIN categories_content cc ON cp.resource_id = cc.content_resource_id "
        . " LEFT JOIN categories c1 ON c1.resource_id = cc.category_resource_id "
        . " LEFT JOIN categories c ON c.resource_id = c1.parent_id "
        . " LEFT JOIN _content_brand cb ON cb.resource_id = cp.brand "
        . " WHERE cp.resource_id = '$rid' AND cp.url = '$url' LIMIT 1") or die(mysqli_error($conn));
$proizvod = mysqli_fetch_object($proizvod);

$canonical = $csDomain . $proizvod->master_cat_url . "/" . $proizvod->sub_cat_url . "/" . $url . "/" . $rid;

if (empty($proizvod->resource_id)) {
    $f->redirect("/poruka/404");
}

$cumbView = $proizvod->num_views + 1;
mysqli_query($conn,"UPDATE _content_products SET num_views = '$cumbView' WHERE resource_id = '$rid' AND url = '$url' LIMIT 1");

function breadCrumbs($catID, &$array) {
    $catObj = new View('categories', $catID, 'resource_id');
    $array[$catObj->level] = $catObj;
    if ($catObj->level == 1) {
        return $array;
    } else {
        return breadCrumbs($catObj->parent_id, $array);
    }
}

$conCat = new View('categories_content', $proizvod->resource_id, 'content_resource_id');
$query = $db->execQuery("SELECT category_resource_id FROM categories_content WHERE content_resource_id = $proizvod->resource_id");
$data = mysqli_fetch_row($query);
$catResourceID = $data[0];
$niz = breadCrumbs($catResourceID, $niz);

$titleSEO = $proizvod->b_title . " " . $proizvod->title . " - " . $niz[2]->title . " / " . $niz[1]->title;

if ($proizvod->technical_description != "") {
    $descSEO = $niz[1]->title . " / " . $niz[2]->title . " | " . $proizvod->b_title ." ". $proizvod->title . ": " . substr(strip_tags(str_replace("</td><td>", " ", htmlspecialchars_decode($proizvod->technical_description))), 0, 60) . "...";
} else if ($proizvod->marketing_description != "") {
    $descSEO = $niz[1]->title . " / " . $niz[2]->title . " | " . $proizvod->b_title ." ". $proizvod->title . ": " . substr(strip_tags(str_replace("\r\n", " ", htmlspecialchars_decode($proizvod->marketing_description))), 0, 60) . "...";
}

if (is_file("uploads/uploaded_pictures/_content_products/" . $dimUrlLitShare . "/" . $proizvod->product_image)) {
    $imgSEO = "/uploads/uploaded_pictures/_content_products/" . $dimUrlLitShare . "/" . $proizvod->product_image;
} elseif (is_file("uploads/uploaded_pictures/_content_products/" . $dimUrlLitBigs . "/" . $proizvod->product_image)) {
    $imgSEO = "/uploads/uploaded_pictures/_content_products/" . $dimUrlLitBigs . "/" . $proizvod->product_image;
} else {
    $imgSEO = "/uploads/uploaded_pictures/_content_products/" . $dimUrlLitSecund . "/" . $proizvod->product_image;
}

$greske = $komentarisano = array();
$uso = $emailCom = $nameCom = $messageCom = '';

if ($f->verifyFormToken('form1')) {

    $uso = 'uso';
    $emailCom = $f->getValue("email");
    if (!filter_var($emailCom, FILTER_VALIDATE_EMAIL)) {
        array_push($greske, "email");
    } else {
        $emailCom = $f->test_input($emailCom);
    }

    $nameCom = $f->getValue("name");
    if(!preg_match('/^[\p{L}\s]+$/u',$nameCom) || strlen($nameCom) < 3){
        array_push($greske, "name");
    } else {
        $nameCom = $f->test_input($nameCom);
    }

    $messageCom = $f->getValue("message");
    if (strlen($messageCom) < 4) {
        array_push($greske, "message");
    } else {
        $messageCom = $f->test_input($messageCom);
    }

    $capchaHideOne = $f->getValue("capchaHideOne");
    $capchaHideTwo = $f->getValue("capchaHideTwo");
    $capchaHide = $capchaHideTwo + $capchaHideOne;

    $capcha = $f->getValue("capcha");
    if ($capchaHide != $capcha) {
        array_push($greske, "capcha");
    }

    if (empty($greske)) {

        $bodyc = "Postavljen je komentar, čeka na odobravanje iz admina na proizvodu sa linkom:<br/>" . $canonical;
        
        $f->sendMail($emailCom, $nameCom, $csEmail, $csName, "Postavljen novi komentar", $bodyc, $currentLanguage);

        $paketKontakt = new View("resources");
        $paketKontakt->table_name = "_content_comments";
        $paketKontakt->Save();

        $newEmail = new View("_content_comments");
        $newEmail->resource_id = $paketKontakt->id;
        $newEmail->title = $nameCom;
        $newEmail->url = $f->generateUrlFromText($nameCom);
        $newEmail->system_date = date("Y-m-d H:i:s");
        $newEmail->lang = 1;
        $newEmail->status = 0;
        $newEmail->pitanje = $messageCom;
        $newEmail->proizvod = $proizvod->resource_id;
        $newEmail->email = $emailCom;
        $newEmail->Save();

        array_push($komentarisano, "poslato");
        $emailCom = $nameCom = $messageCom = "";
    }
}

$capcha1 = rand(1, 9);
$capcha2 = rand(1, 9);

$htmlTagAddOG = 'prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# product: http://ogp.me/ns/product#"';
$ogType = "product";

$shareUrl = rtrim($csDomain,"/").$REQUEST;

include_once ("head.php"); ?>

        <meta property="product:price:amount" content="<?= ($proizvod->master_price > 0) ? number_format($proizvod->master_price, 2, ".", "") : number_format($proizvod->price, 2, ".", ""); ?>" /> 
        <meta property="product:price:currency" content="RSD" /> 
        <meta property="product:brand" content="<?= $proizvod->b_title; ?>" /> 
        <meta property="product:category" content="<?= $niz[2]->title; ?>" /><?php 
        if ($proizvod->master_status != 'Active' && $proizvod->status != 1) { ?>
        <meta property="product:availability" content="oos" /><?php 
        } else { ?>

        <meta property="product:availability" content="instock" /><?php 
        } ?>

        <meta property="product:condition" content="new" /> 
</head>
<body>

    <?php if ($uso != '') { ?>
        <div id="popup">
            <div id='popupInner'>
                <?php
                if (!empty($greske)) {
                    echo "<h1>Proverite podatke</h1><p>Niste postavili komentra, molimo Vas proverite unete podatke!</p>";
                } elseif (!empty($komentarisano)) {
                    echo "<h1>Uspešno</h1><p>Uspešno ste postlali pitanje ili komentar. Uskoro će ga odobriti administrator i bićete obavešteni putem email-a.</p>";
                }
                ?>
                <a href='javascript:' onclick='closePopup();' class="more">Zatvori</a>
            </div>
        </div>
        <?php
    }
    include_once ("header.php");
    include_once ("product_page-content.php");
    include_once ("footer.php");
    ?>
    <script type="text/javascript">function openTab(a,b){var c,d,e;for(d=document.getElementsByClassName("tabcontent"),c=0;c<d.length;c++)d[c].style.display="none";for(e=document.getElementsByClassName("tablinks"),c=0;c<e.length;c++)e[c].className=e[c].className.replace(" active","");document.getElementById(b).style.display="block",document.getElementById("opisTab").style.background="#FFF",document.getElementById("specifikacijaTab").style.background="#FFF",document.getElementById("pitanjeTab").style.background="#FFF",document.getElementById(b+"Tab").style.background="#F5F5F5",a.currentTarget.className+=" active"}</script>
</body>
</html>