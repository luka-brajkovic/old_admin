<?php
/*
  <!--------------- SNOW EFECT ------------->
  <canvas id="canvas"></canvas>
  <style>
  #canvas {position: fixed;height: 100%;width: 100%;}
  </style>
  <script>window.onload = function () {
  function a() {
  t.clearRect(0, 0, d, o), t.fillStyle = "rgba(237, 237, 237, 1)", t.beginPath();
  for (var a = 0; h > a; a++) {
  var n = e[a];
  t.moveTo(n.x, n.y), t.arc(n.x, n.y, n.r, 0, 2 * Math.PI, !0)
  }
  t.fill(), r()
  }
  function r() {
  M += .01;
  for (var a = 0; h > a; a++) {
  var r = e[a];
  r.y += Math.cos(M + r.d) + 1 + r.r / 2, r.x += 2 * Math.sin(M), (r.x > d + 5 || r.x < 0 || r.y > o) && (a % 3 > 0 ? e[a] = {x: Math.random() * d, y: -10, r: r.r, d: r.d} : Math.sin(M) > 0 ? e[a] = {x: -5, y: Math.random() * o, r: r.r, d: r.d} : e[a] = {x: d + 5, y: Math.random() * o, r: r.r, d: r.d})
  }
  }
  var n = document.getElementById("canvas"), t = n.getContext("2d"), d = window.innerWidth, o = window.innerHeight;
  n.width = d, n.height = o;
  for (var h = 25, e = [], i = 0; h > i; i++)
  e.push({x: Math.random() * d, y: Math.random() * o, r: 4 * Math.random() + 1, d: Math.random() * h});
  var M = 0;
  setInterval(a, 33)
  };</script>
  <!--------------- END SNOW EFECT ------------->
 */
if ($configSiteShop == 1) {
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
                    <a href="/" title="<?= $configSiteTitle; ?>">
                        <img src="/images/logo.jpg" alt="<?= $configSiteTitle; ?>" title="<?= $configSiteTitle; ?>" />
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
                                $topMenu = mysql_query("SELECT url, title FROM menu_items WHERE menu_id = 2 ORDER BY ordering");
                                while ($menItem = mysql_fetch_object($topMenu)) {
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
                        <?php include_once ("header-banner.php"); ?>
                    </div>
                </div>
                </nav>
                </header>