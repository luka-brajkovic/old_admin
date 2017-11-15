<footer>
    <div class="footerSnowHolder">
        <div class="container">
			<?php
			if (isset($_COOKIE["user_seen"])) {
				$listRids = explode(",", $_COOKIE["user_seen"]);
				$ocisceniRidovi = "";
				$counter = 1;
				foreach ($listRids as $rid) {
					$rid = str_replace("-", "", $rid);
					if (is_numeric($rid) && $counter <= 4) {
						$ocisceniRidovi .= $rid . ",";
						$counter++;
					}
				}
				if ($ocisceniRidovi != '') {
					?>
					<div id="petine" class="productHolder clear">
						<h5>Proizvodi koje ste gledali: </h5>
						<div class="productListIndex row">
							<?php
							$gledano = mysqli_query($conn, "SELECT cp.*, b.title as b_title, c.url as master_cat_url, c1.url as sub_cat_url FROM _content_products cp "
									. " LEFT JOIN _content_brand b ON b.resource_id = cp.brand "
									. " LEFT JOIN categories_content cc ON cp.resource_id = cc.content_resource_id "
									. " LEFT JOIN categories c1 ON c1.resource_id = cc.category_resource_id "
									. " LEFT JOIN categories c ON c.resource_id = c1.parent_id "
									. " WHERE (cp.status = 1 OR cp.master_status = 'Active') AND cp.resource_id IN (" . rtrim($ocisceniRidovi, ",") . ") ");
							while ($item = mysqli_fetch_object($gledano)) {
								include ("little_product-search.php");
							}
							?>
						</div>
					</div>        
					<?php
				}
			}
			?>
        </div>
    </div>
    <div class="footerDown">
        <div class="container">
            <div class="footerContHolder row">
                <div class="quarter left">
                    <h4>Kupovina</h4>
                    <ul>
						<?php
						$topMenu = mysqli_query($conn, "SELECT url, title FROM menu_items WHERE menu_id = 3 ORDER BY ordering");
						while ($menItem = mysqli_fetch_object($topMenu)) {
							?>
							<li>
								<i class="fa fa-circle"></i>
								<a href="<?= $menItem->url; ?>" title="<?= $menItem->title; ?>"><?= $menItem->title; ?></a>
							</li> 
							<?php
						}
						?>
                    </ul>
                </div>
                <div class="quarter left">
                    <h4>Korisnički nalog</h4>
                    <ul>
						<?php
						$topMenu = mysqli_query($conn, "SELECT url, title FROM menu_items WHERE menu_id = 2 ORDER BY ordering");
						while ($menItem = mysqli_fetch_object($topMenu)) {
							?>
							<li>
								<i class="fa fa-circle"></i>
								<a href="<?= $menItem->url; ?>" title="<?= $menItem->title; ?>"><?= $menItem->title; ?></a>
							</li> 
							<?php
						}
						?>
                    </ul>
                </div>
                <div class="quarter left">
                    <h4>Pratite nas</h4>
                    <ul itemscope itemtype="http://schema.org/Organization">
                        <link itemprop="url" content="<?= rtrim($csDomain, "/"); ?>">
						<?php
						if ($csFacebook != "") {
							?>
							<li>
								<i class="fa fa-circle"></i>
								<a href="<?= $csFacebook; ?>" target="_blank" title="Facebook stranica <?= $csName; ?>">Facebook</a>
							</li>
							<?php
						}
						if ($csGooglePlus != "") {
							?>
							<li>
								<i class="fa fa-circle"></i>
								<a href="<?= $csGooglePlus; ?>" title="Google Plus stranica <?= $csName; ?>" target="_blank">Google Plus</a>
							</li>
							<?php
						}
						if ($csTwitter != "") {
							?>
							<li>
								<i class="fa fa-circle"></i>
								<a href="<?= $csTwitter; ?>" title="Twitter stranica <?= $csName; ?>" target="_blank">Twitter</a>
							</li>
							<?php
						}
						if ($csLinkedIn != "") {
							?>
							<li>
								<i class="fa fa-circle"></i>
								<a href="<?= $csLinkedIn; ?>" title="LinkedIn stranica <?= $csName; ?>" target="_blank">LinkedIn</a>
							</li>
							<?php
						}
						if ($csYouTube != "") {
							?>
							<li>
								<i class="fa fa-circle"></i>
								<a href="<?= $csYouTube; ?>" title="You Tube stranica <?= $csName; ?>" target="_blank">You Tube</a>
							</li>
							<?php
						}
						if ($csVimeo != "") {
							?>
							<li>
								<i class="fa fa-circle"></i>
								<a class="vimeo" href="<?= $csVimeo; ?>" title="Vimeo stranica <?= $csName; ?>" target="_blank">Vimeo</a>
							</li>
							<?php
						}
						if ($csInstagram != "") {
							?>
							<li>
								<i class="fa fa-circle"></i>
								<a class="instagram" href="<?= $csInstagram; ?>" title="Instagram stranica <?= $csName; ?>" target="_blank">Instagram</a>
							</li>
							<?php
						}
						if ($csPinterest != "") {
							?>
							<li>
								<i class="fa fa-circle"></i>
								<a class="pinterest" href="<?= $csPinterest; ?>" title="Pinterest stranica <?= $csName; ?>" target="_blank">Pinterest</a>
							</li>
						<?php } ?>
                    </ul>
                </div>
                <div class="quarter left">
                    <h4>Kontakt</h4>
                    <p><?= $csAddress; ?><br><?= $csZip . " " . $csCity . " - " . $csCountry; ?></p>
                    <p><a href="tel:<?= $csPhone; ?>"><?= $csPhone; ?></a>
						<?php
						if ($csPhone2 != "") {
							?>
	                        <br><a href="tel:<?= $csPhone2; ?>"><?= $csPhone2; ?></a>
						<?php } ?>
                    </p>
                    <p><a href="mailto:<?= $csEmail; ?>"><?= $csEmail; ?></a></p>
                </div>
            </div>
			<div class="copyRight clear">
				<div class="half left">
					<p><?= $csFooter; ?><p>
				</div>
				<div class="half right">
					<p class="right">Izrada sajta <a href="https://www.webdizajnsrbija.rs/" title="Izrada sajta - Web Dizajn Srbija" target="_blank">Web Dizajn Srbija</a></p>
				</div>
			</div>
        </div>
    </div>
	<?php
	if (!isset($isCompare)) {
		$isCompare = "";
	}
	if (!$isCompare) {
		?>
		<div id="compare" <?php
		if (!isset($_SESSION["compare"]) || count($_SESSION["compare"]) == 0) {
			echo 'class="none"';
		}
		?>>
			<div class="container">
				<?php
				if (isset($_SESSION["compare"])) {

					if (count($_SESSION["compare"]) > 0 || !empty($_SESSION["compare"])) {
						$colProdsArr = mysqli_query($conn, "SELECT cp.resource_id, cp.title, cb.title as b_title FROM _content_products cp "
								. " LEFT JOIN _content_brand cb ON cb.resource_id = cp.brand "
								. " WHERE cp.status = 1 AND cp.resource_id IN (" . implode(',', $_SESSION['compare']) . ")") or die(mysqli_error($conn));
						if (mysqli_num_rows($colProdsArr) > 0) {
							while ($item = mysqli_fetch_object($colProdsArr)) {
								?>
								<a title="<?= $item->title; ?>" href="javascript:"><?= substr($item->b_title . " " . $item->title, 0, 55); ?><span data-rid='<?= $item->resource_id; ?>'>x</span></a> 
								<?php
							}
							?>
							<a id="comparasion" href="/uporedi">Uporedi</a>

							<?php
						} else {
							unset($_SESSION["compare"]);
						}
					} else {

						unset($_SESSION["compare"]);
					}
				}
				?>
			</div>
		</div>
	<?php } ?>
</footer>
<a href="#header" id="toTop">
    <i class="fa fa-arrow-circle-up transition"></i>
</a>
<script type="text/javascript">
<?php
include 'js/jquery.php';
if ($fancyboxJS == 1) {
	include 'js/fancybox.php';
	?>

	<?php
} elseif ($naslovna == true) {
	include 'js/slider.php';
}
if ($singleCategories == 1) {
	include 'js/range.php';
	?>
	<?php
}
include 'js/iss.php';
?>
	function addToCart(a, i, t) {
		$("#cart_loader").fadeIn("slow");
		if ($("#proizvod-kolicina").length > 0)
			var i = $("#proizvod-kolicina").val();
		console.log(a), console.log(t), console.log(i), $.ajax({type: "POST", async: !0, url: "/work.php", data: "itemID=" + a + "&price=" + t + "&q=" + i + "&action=add-to-cart", success: function (a) {
				$("#add_to_cart_notifier").fadeIn("slow"), $("#cart_count").html(a), $("#cart_loader").fadeOut("slow")
			}})
	}
	$(document).on("click", "#compare a span", function () {
		var a = $(this).data("rid");
		$.ajax({type: "POST", async: !0, url: "/work.php", data: "rid=" + a + "&action=remove-from-compare", success: function (a) {
				8 === a.length ? $("#compare").fadeOut("fast") : ($("#compare").fadeIn("fast"), $("#compare").html(a))
			}})
	}), $(document).on("click", ".addCompare", function () {
		var a = $(this).data("rid");
		$(this).attr("class", "addCompare compared"), $.ajax({type: "POST", async: !0, url: "/work.php", data: "rid=" + a + "&action=add-to-compare", success: function (a) {
				a.length > 10 ? ($("#compare").html(a), $("#compare").fadeIn("fast")) : alert("Morate dodati proizvode koji su iste podkategorije, najviše 3 proizvoda")
			}})
	}), $(document).ready(function () {
		$(".addWish").click(function () {
			var a = $(this).data("rid");
			$(this).attr("class", "addWish wished"), $.ajax({type: "POST", async: !0, url: "/work.php", data: "rid=" + a + "&action=add-to-wish", success: function (a) {<?php if ($isLoged) { ?>$(".wishHeader a span").html(a), $(this).attr("class", "addWish wished");<?php } ?>$("#whishicList").fadeIn()
				}})
		}), $(".removeWish").click(function () {
			var a = $(this).data("rid");
			$(this).remove(), $.ajax({type: "POST", async: !0, url: "/work.php", data: "rid=" + a + "&action=remove-from-wish", success: function () {
					window.location.href = "/nalog/lista-zelja"
				}})
		});
	});
	$(document).scroll(function () {
		var a = $(this).scrollTop();
		a > 400 ? $("#toTop").fadeIn(300) : $("#toTop").fadeOut(300)
	});
	$(function () {
		$("a[href*=#]:not([href=#])").click(function () {
			if (location.pathname.replace(/^\//, "") == this.pathname.replace(/^\//, "") && location.hostname == this.hostname) {
				var t = $(this.hash);
				if (t = t.length ? t : $("[name=" + this.hash.slice(1) + "]"), t.length)
					return $("html,body").animate({scrollTop: t.offset().top}, 1e3), !1
			}
		})
	});

	var widthScreen = $(window).width();
	widthScreen < 768 ? ($(".categoriesList").attr("class", "categoriesListRes"), $(".clear.right.quarter-x3.masterMenu.clear").attr("class", "resMenu"), $(".resMenu").click(function () {
		var a = $(".resMenu ul").css("display");
		"none" === a ? ($(".resMenu i.little").attr("class", "fa fa-times little"), $(".resMenu ul").slideDown("fast")) : ($(".resMenu i.little").attr("class", "fa fa-bars little"), $(".resMenu ul").slideUp("fast"))
	}), $(".quarter.left.masterMenuProizvodi").attr("class", "resMenuProizvodi"), $(".resMenuProizvodi").click(function () {
		var a = $(".categoriesListRes").css("display");
		"none" === a ? ($(".resMenuProizvodi strong i.little").attr("class", "fa fa-times little"), $(".categoriesListRes").slideDown("fast")) : ($(".resMenuProizvodi strong i.little").attr("class", "fa fa-bars little"), $(".categoriesListRes").slideUp("fast"))
	})) : ($(".productPopUp").click(function () {
		var a = $(".productCats .categoriesList").css("display");
		"none" === a ? $(".productCats .categoriesList").slideDown("fast") : $(".productCats .categoriesList").slideUp("fast")
	}), $(".resMenu").attr("class", "clear right quarter-x3 masterMenu clear"), $(window).load(function () {
		var a = Math.max.apply(null, $(".drziOpis h4").map(function () {
			return $(this).height()
		}).get());
		$(".drziOpis h4").css("height", a + "px")
	}));
	widthScreen < 992 ? ($(".quarter.masterHolderProduct").attr("class", "list_style"), $(".drziOpis h4").css("height", "auto")) : $(window).load(function () {
		var a = Math.max.apply(null, $(".drziOpis h4").map(function () {
			return $(this).height()
		}).get());
		$(".drziOpis h4").css("height", a + "px")
	});
	$(window).resize(function () {
		var a = $(window).width();
		a < 768 ? ($(".categoriesList").attr("class", "categoriesListRes"), $(".clear.right.quarter-x3.masterMenu.clear").attr("class", "resMenu"), $(".quarter.left.masterMenuProizvodi").attr("class", "resMenuProizvodi")) : ($(".productPopUp").click(function () {
			var a = $(".productCats .categoriesList").css("display");
			"none" === a ? ($(".resMenu i.little").attr("class", "fa fa-times little"), $(".resMenu ul").slideDown("fast")) : ($(".resMenu i.little").attr("class", "fa fa-bars little"), $(".resMenu ul").slideUp("fast"))
		}), $(".categoriesListRes").attr("class", "list categoriesList quarterMarginMasterR"), $(".resMenuProizvodi").attr("class", "quarter left masterMenuProizvodi"), $(".resMenu").attr("class", "clear right quarter-x3 masterMenu clear"), $(window).load(function () {
			var a = Math.max.apply(null, $(".drziOpis h4").map(function () {
				return $(this).height()
			}).get());
			$(".drziOpis h4").css("height", a + "px")
		}))
	});
</script>
<?php
if ($csGAnalytic != "" && strpos($csDomain, "wds.in.rs") === FALSE) {
	include ("includes/google-analytics.php");
}
?>

<script>
    function signOut() {
        var auth2 = gapi.auth2.getAuthInstance();
        auth2.signOut().then(function () {
            console.log('User signed out.');
        });
    }
</script>
