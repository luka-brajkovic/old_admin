<?php
if ($f->verifyFormToken('newsletter')) {

	$email = $f->getValue("email");
	$emailmd5 = md5($email);

	if (filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE) {
		$_SESSION['infoTitle'] = "<h1>Neuspešna prijava</h1>";
		$_SESSION['infoText'] = "<p>NISTE uspeli da se registrujete na našu newsletter listu. Molimo Vas da proverite email koji ste uneli.</p>";
	} else {

		$postojiEmail = $db->getValue("resource_id", "_content_newsletter", title, $email);

		if ($postojiEmail == "") {
			$paketNewsletter = new View("resources");
			$paketNewsletter->table_name = "_content_newsletter";
			$paketNewsletter->Save();

			$newEmail = new View("_content_newsletter");
			$newEmail->resource_id = $paketNewsletter->id;
			$newEmail->title = $email;
			$newEmail->url = $f->generateUrlFromText($email);
			$newEmail->system_date = date("Y-m-d H:i:s");
			$newEmail->lang = 1;
			$newEmail->status = 0;
			$newEmail->Save();

			$bodyUser = "Poštovani, <br /><br />klikom na dugme LINK ispod će te aktivirati Vašu prijavu na newsletter na sajtu $csName.<br /><br />"
					. "<a href='".$csDomain."aktivacija-newsletter/$emailmd5'>LINK</a>"
					. "<br /><br /><i>ukoliko ne vidite dugme iznad, kopirajte ovajl link u Vaš internet pretraživač: ".$csDomain."aktivacija-newsletter/$emailmd5</i>"
					. "<br /><br /><br /><i style='font-size:14px;'>Ukoliko se niste Vi prijavili molimo Vas da ignorišite ovu poruku.<i><br /><br /><br />Srdačan pozdrav od tima sajta $csName";
			$f->sendMail($csEmail, "$csName", $email, "", "Aktivacija newsletter-a " . $csName, $bodyUser, $currentLanguage);

			$_SESSION['infoTitle'] = "<h1>Aktivacijoni link</h1>";
			$_SESSION['infoText'] = "<p>Na Vaš email je poslat aktivacioni link, klikom na njega će te potvrditi i aktivirati Vašu prijavu na newsletter.</p>";
		} else {
			$maili = new Collection("_content_newsletter");
			$mailArr = $maili->getCollection("WHERE  title = '$email'");
			$newEmail = $mailArr[0];

			if ($newEmail->status == 1) {
				$_SESSION['infoTitle'] = "<h1>Email postoji</h1>";
				$_SESSION['infoText'] = "<p>Vaš email je već u našem sistemu, hvala Vam na poverenju.</p>";
			} else {
				$bodyUser = "Poštovani, <br /><br />klikom na dugme LINK ispod će te aktivirati Vašu prijavu na newsletter na sajtu $csName.<br /><br />"
						. "<a href='".$csDomain."aktivacija-newsletter/$emailmd5'>LINK</a>"
					. "<br /><br /><i>ukoliko ne vidite dugme iznad, kopirajte ovajl link u Vaš internet pretraživač: ".$csDomain."aktivacija-newsletter/$emailmd5</i>"
						. "<br /><br /><br /><i style='font-size:14px;'>Ukoliko se niste Vi prijavili molimo Vas da ignorišite ovu poruku.<i><br /><br /><br />Srdačan pozdrav od tima sajta $csName";
				$f->sendMail($csEmail, "$csName", $email, "", "Aktivacija newsletter-a " . $csName, $bodyUser, $currentLanguage);

				$_SESSION['infoTitle'] = "<h1>Aktivacijoni link</h1>";
				$_SESSION['infoText'] = "<p>Na Vaš email je poslat aktivacioni link, klikom na njega će te potvrditi i aktivirati Vašu prijavu na newsletter.</p>";
			}
		}
	}
}

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
				<?php include_once ("includes/header-search.php"); ?>
				<?php include_once ("includes/header-cart.php"); ?>
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
								include_once 'includes/index-content-categories.php';
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