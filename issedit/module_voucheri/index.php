<?php
require("../library/config.php");
$f->checkLogedAdmin("module");
$infomsg = $f->getValue("infomsg");
$cid = $f->getValue("cid");

function createPagination($url_link, $page, $resultCount, $limit) {

    $totalPages = ceil($resultCount / $limit);
    if ($totalPages > 1) {



        $firstPage = ($page == 1) ? 0 : 1;
        $lastPage = ($page == $totalPages) ? 0 : $totalPages;
        $prevPage = ($page - 1 <= 0) ? 0 : $page - 1;
        $nextPage = ($page + 1 <= $totalPages) ? $page + 1 : 0;

        if ($page < $totalPages) {
            $i = 1;
            $countTill = $totalPages;
        } else {
            $i = $page - $totalPages + 1;
            $countTill = $totalPages;
        }

        // printing previous page link



        if ($prevPage != 0) {

            echo "<a class='leftArrow' href=\"" . $url_link . "/strana/" . $prevPage . "\">◄</a>";
        } else {
            ?>

            <?php
        }


        // printing middle pages

        for ($i; $i <= $countTill; $i++) {

            if ($page == $i) {
                echo "<a href=\"#\" id='current' class='number'>" . $i . "</a> ";
            } else {
                echo "<a class='number' href=\"" . $url_link . "/strana/" . $i . "\">" . $i . "</a> ";
            }
        }


        // printing next page link

        if ($nextPage != 0) {
            echo "<a class='rightArrow' href=\"" . $url_link . "/strana/" . $nextPage . "\">►</a>";
        } else {
            ?>

            <?php
        }
    }
}

$module_name = "voucheri";
$tablePrint = new Tableprint();

$contentTypeContent = new View("content_types", $cid);
$langCollection = new Collection("languages");
$languages = $langCollection->getCollection();

$status = $_GET["status"];
if ($status == '') {
    $status = 2;
} else {
    $status = $status;
}



$sortData = new View("sort_table", 1);

$url_link = "index.php?status=$status";

$page = $_GET["page"];
if (!$page) {
    $page = 1;
}



if ($sortData->asc_desc == 2) {
    $sort = "DESC";
} else {
    $sort = "ASC";
}

$voucheriCollection = new Collection("_content_voucheri");
$voucheriArrayCount = $voucheriCollection->getCollection("WHERE status = $status");

$resultCount = count($voucheriArrayCount);

$perPage = $sortData->per_page;

if ($perPage == 0) {
    $limit = '';
    $perPage = $resultCount;
} else {
    $limit = "LIMIT $page,$perPage";
}

$voucheriCollection = new Collection("_content_voucheri");
$voucheriArray = $voucheriCollection->getCollection("WHERE status = $status ORDER BY system_date $sort $limit");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />		
        <title><?= ADMIN_TITLE; ?></title>
        <?php include("../head.php"); ?>
        <link type="text/css" href="style.css" rel="stylesheet" />
        <script type="text/javascript" src="ajaxi.js"></script>
        <script type="text/javascript">


        </script>
    </head>
    <body>
        <!-- Container -->
        <input type="hidden" value="<?= $status; ?>" id="status" name="status"/>
        <div id="container">

            <?php include("../header.php"); ?>

            <!-- Background wrapper -->
            <div id="bgwrap">

                <!-- Main Content -->
                <div id="content">
                    <div style="margin-left: 0;" id="main">
                        <h1>Voucheri</h1>
                        <div class="sort-buttons">

                            <ul class="left buttons">

                                <li>
                                    <a <?= ($status == 2) ? " class='aktivan' " : ""; ?> href="index.php?status=2">Neaktivni vaučeri</a>
                                </li>
                                <li>
                                    <a <?= ($status == 1) ? " class='aktivan' " : ""; ?> href="index.php?status=1">Odobreni vaučeri</a>
                                </li>
                                <li>
                                    <a <?= ($status == 3) ? " class='aktivan' " : ""; ?> href="index.php?status=3">Stornirani vaučeri</a>
                                </li>
                            </ul>
                            <div class="search_div right">
                                <label for="input_search">PRONADJI <?= "po: pozivu na broj, id ponude, id uplatnice, broj kupona, korisnik vaucera, ime uplatioca, prezime uplatioca, email uplatioca"; ?></label>
                                <input type="text" id="input_search" onkeyup="search();" value="" />
                            </div>
                        </div> 
                        <?php
                        if (isset($_SESSION["poslat_vaucer"])) {
                            ?>
                            <div style="background:#57c5c7; margin:10px 0; border:2px solid #349698; width:100%;" id="alerter">

                                <h2 style="padding:20px; float:left; width:60%;">USPESNO STE IZVRSILI OPERACIJU SA VAUCEROM</h2>
                                <a style="float:right; background: #FFF; display:block; margin:10px 10px 0 0; color:#000; text-decoration:none; padding:10px;" href='javascript:void();' onclick="this.parentNode.style.display = 'none';">ZATVORI PROZOR x</a>
                                <div class="clear"></div>
                            </div>
                            <?php
                            unset($_SESSION["poslat_vaucer"]);
                        }
                        ?>
                        <div class="paginacija">
                            <?php
                            if ($resultCount > 0) {
                                echo createPagination($url_link, $page, $resultCount, $perPage);
                            }
                            ?>
                        </div>
                        <div class="voucheri">
                            <table width="100%" id="voucheri_table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>POZIV NA BROJ</th>
                                        <th>ID PONUDE</th>
                                        <th>VREDNOST</th>
                                        <th>DATUM GENERISANJA</th>
                                        <th>ID UPLATNICE</th>
                                        <th>BROJ VOUCHERA</th>
                                        <th>PLACENO PUTEM</th>
                                        <th>UPLATILAC</th>
                                        <th>ODOBRENO</th>
                                        <th>E-mail uplatioca</th>
                                        <th>Salje se na:</th>
                                        <th>AKCIJE</th>
                                    </tr>
                                </thead>
                                <tbody id="ajax_search">
                                    <?php
                                    foreach ($voucheriArray as $voucher) {
                                        $korisnik = new View("users", $voucher->korisnik);
                                        ?>
                                        <tr>
                                            <td>
                                                <?= $voucher->resource_id; ?>	
                                            </td>
                                            <td>
                                                <?= $voucher->title; ?>	
                                            </td>
                                            <td>
                                                <?= $voucher->id_ponude; ?>	
                                            </td>
                                            <td>
                                                <?= $voucher->vrednost; ?>	
                                            </td>
                                            <td>
                                                <?= $f->makeFancyDate($voucher->system_date); ?>	
                                            </td>
                                            <td>
                                                <?= $voucher->id_uplatnice; ?>	
                                            </td>
                                            <td>
                                                <?= $voucher->broj_kupona; ?>	
                                            </td>
                                            <td>
                                                <?php
                                                if ($voucher->placeno_putem == 1) {
                                                    echo "Uplatnica";
                                                }
                                                ?>	
                                            </td>
                                            <td>
                                                <?= $korisnik->ime . " " . $korisnik->prezime; ?>	
                                            </td>
                                            <td>
                                                <?php
                                                if ($voucher->status == 2) {
                                                    echo "NIJE";
                                                }
                                                ?>
                                                <?php
                                                if ($voucher->status == 1) {
                                                    echo $f->makeFancyDate($voucher->odobreno);
                                                }
                                                ?>	
    <?php
    if ($voucher->status == 3) {
        echo "STORNIRAN<br>" . $f->makeFancyDate($voucher->odobreno);
    }
    ?>		
                                            </td>
                                            <td>
                                                <?= $voucher->email; ?>		
                                            </td>
                                            <td>
    <?= $voucher->salje_se_na; ?>		
                                            </td>
                                            <td>
    <?php
    if ($voucher->status == 1) {
        ?>
                                                    <a href="work.php?action=storniraj_voucher&rid=<?= $voucher->resource_id; ?>" onclick="javascript:return confirm('Da li ste sigurni?');">Storniraj kupon</a>
                                                    <?

                                                    }
                                                    if($voucher->status==2){
                                                    ?>
                                                    <a href="work.php?action=odobri_voucher&rid=<?= $voucher->resource_id; ?>" onclick="javascript:return confirm('Da li ste sigurni?');">
                                                        <img src='correct.png' />
                                                    </a>
                                                    <?
                                                    }
                                                    if($voucher->status==3){
                                                    echo "Voučer je storniran";
                                                    }
                                                    ?>		
                                                </td>
                                            </tr>
        <?php
    }
    ?>


                                    </tbody>
                                </table>

                            </div>
                        </div>   
                    </div>
                    <!-- End of Main Content -->





                </div>
                <!-- End of bgwrap -->

            </div>
            <!-- End of Container -->

    <?php include("../footer.php"); ?>

    </body>
</html>