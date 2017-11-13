<?php
if (isset($_SESSION['infoTitle']) && $_SESSION['infoTitle'] != "") {
    ?>
    <div id="popup">
        <div id='popupInner'>
            <?= $_SESSION['infoTitle'] . $_SESSION['infoText']; ?>
            <a href='javascript:' onclick='closePopup();' class="more">Zatvori</a>
        </div>
    </div>  
    <?php
    $_SESSION['infoTitle'] = "";
    $_SESSION['infoText'] = "";
}
if ($csShop == 1) {
    ?>
    <div id="cart_loader">
        <div class="loaderHolder">
            <i class="fa fa-spinner fa-pulse"></i>
        </div>
    </div>
    <div id="add_to_cart_notifier">
        <div id="popupInner">
            <h1>Uspešno!</h1>
            <p>Proizvod je dodat u korpu!<br/>U gornjem desnom uglu web sajta možeš videti broj proizvoda u korpi.</p>
            <p class='clear'>
                <a href="javascript:" onclick="closeCart()">Nastavi kupovinu</a>
                <a href="/korpa">Završi kupovinu</a>
            </p>
        </div>
    </div>
    <?php if ($isLoged) { ?>
        <div id="whishicList">
            <div id="popupInner">
                <h1>Uspešno</h1>
                <p>Proizvod je dodat u listu želja!<br/>U gornjem desnom uglu web sajta možeš videti broj proizvoda u listi želja i pregledati je.</p>
                <a class="more" href="javascript:" onclick="document.getElementById('whishicList').style.display = 'none';
                        return false;">Zatvori</a>
            </div>
        </div>
    <?php } else { ?>
        <div id="whishicList">
            <div id="popupInner">
                <h1>Morate biti prijavljeni</h1>
                <p>Za sadržaj koji tražite morate biti prijavljeni na sistem.</p>
                <p>Registracija može da se obavi preko dugmeta u gornjem desnom uglu sajta.</p>
                <a class="more" href="javascript:" onclick="document.getElementById('whishicList').style.display = 'none';
                        return false;">Zatvori</a>
            </div>
        </div>
        <?php
    }
}
?>
<header class="clear" id="header">
    <div class="container">
        <div class="row">
            <div class="quarter left quarterLogo">
                <div class="logo">
                    <a href="/" title="<?= $csTitle; ?>">
                        <img src="/images/logo.<?= $logoEx . "?t=" . filemtime("images/logo.jpg"); ?>" alt="<?= $csTitle; ?>" title="<?= $csTitle; ?>" />
                    </a>
                </div>
            </div>
            <div class="quarter-x3 right">
                <?php include_once ("header-search.php"); ?>
                <?php include_once ("header-cart.php"); ?>
            </div>
        </div>
    </div>
    <nav class="menus">
        <div class="container">
            <div class="row">
                <?php
                if (!isset($urlAKTIVE)) {
                    $urlAKTIVE = "";
                }
                if ($urlAKTIVE == "/" || ($cat_master_url != "" && $cat_sub_url == "" && $urlJe != "proizvodi-na-akciji")) {
                    ?>
                    <div class="quarter left masterMenuProizvodi">
                        <strong class="quarterMarginMasterR">Proizvodi <i class="fa fa-bars little"></i></strong>
                    <?php } else { ?>
                        <div class="quarter left masterMenuProizvodi">
                            <strong class="quarterMarginMasterR productPopUp">Proizvodi <i class="fa fa-sort-desc big"></i><i class="fa fa-bars little"></i></strong>
                            <div class="productCats">
                                <?php
                                include_once 'index-content-categories.php';
                                echo "</div>";
                            }
                            ?>
                        </div>
                        <div class="clear right quarter-x3 masterMenu clear" itemscope itemtype="http://schema.org/SiteNavigationElement">
                            <i class="inavigation">Navigacija...</i>
                            <i class="fa fa-bars little"></i>
                            <ul class="right">
                                <?php
                                $topMenu = mysqli_query($conn, "SELECT url, title FROM menu_items WHERE menu_id = 1 ORDER BY ordering");
                                while ($menItem = mysqli_fetch_object($topMenu)) {
                                    ?>
                                    <li itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                                        <a itemprop="url" href="<?= $menItem->url; ?>" title="<?= $menItem->title; ?>">
                                            <span itemprop="name" class="transition<?= ($urlAKTIVE == $menItem->url) ? " active" : ""; ?>"><?= $menItem->title; ?></span>
                                        </a>
                                    </li> 
                                    <?php
                                }
                                ?>
                            </ul>
                        </div>  
                    </div>
                </div>
                </nav>
                </header>