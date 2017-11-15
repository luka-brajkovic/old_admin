<?php
include_once ("library/config.php");

$naslovna = false;

$pretraga = $_GET['pretraga_po'];

if ($pretraga) {
	$pretraga = filter_input(INPUT_POST | INPUT_GET, 'pretraga_po', FILTER_SANITIZE_SPECIAL_CHARS);
}

$pretraga_po = "";
$listRec = explode(" ", $pretraga);
foreach ($listRec as $rec) {
	$recCount = strlen($rec);
	if ($recCount > 3) {
		$cut = $recCount - 1;
		$rec = substr($rec, 0, $cut);
		$pretraga_po .= $rec . " ";
	} else {
		$pretraga_po .= $rec . " ";
	}
}
$pretraga_po = trim($pretraga_po);

if ($_GET['kategorija']) {
	$kategorijaSearch = filter_input(INPUT_POST | INPUT_GET, 'kategorija', FILTER_SANITIZE_SPECIAL_CHARS);
	$category = new View('categories', $kategorijaSearch, 'resource_id');
	$add = " AND (c1.resource_id = $kategorijaSearch OR c.resource_id = $kategorijaSearch) ";
}

$errorInfo = false;
if (!$pretraga_po || strlen($pretraga_po) < 2) {
	$errorInfo = true;
}

$page = $f->getValue("strana");
if (!$page || !is_numeric($page)) {
	$page = 1;
}

$poStrani = $f->getValue("po_strani");
if (isset($poStrani) && is_numeric($poStrani)) {
	$_SESSION['perPage'] = $poStrani;
} elseif ($_SESSION['perPage']) {
	$poStrani = $_SESSION['perPage'];
} else {
	$poStrani = 32;
}

$sortiranje = $f->getValue("sortiranje");
if (!$sortiranje || !is_numeric($sortiranje)) {
	$order = " ORDER BY LENGTH(cp.price), cp.price ASC ";
} else {
	switch ($sortiranje) {
		case 1:
			$order = " ORDER BY LENGTH(cp.price), cp.price ASC ";
			break;
		case 2:
			$order = " ORDER BY LENGTH(cp.price) DESC, cp.price DESC";
			break;
		case 3:
			$order = " ORDER BY cp.num_views DESC";
			break;
		case 4:
			$order = " ORDER BY cp.system_date DESC";
			break;
		case 6:
			$order = " ORDER BY b.title ASC";
			break;
		default:
			$order = " ORDER BY LENGTH(cp.price), cp.price ASC ";
			break;
	}
}
$titleSEO = $pretraga . " - Pretraga artikala";
$descSEO = "Pretraga proizvoda na snizenju i akciji po reči $pretraga na internet prodavnici " . $csName;

include_once ("head.php");
?>
</head>
<body>
	<?php
	include_once ("header.php");

	if ($errorInfo) {
		?>
		<div class="container">
			<br>
			<h1>Prekratka reč za pretragu</h1>
			<p>Poštovani, molimo Vas da pretražujete po reči od minimum 3 karaktera.</p>
		</div>
		<?php
	} else {
		$query = "SELECT cp.*, b.title as b_title, c.url as master_cat_url, c1.url as sub_cat_url FROM _content_products cp "
				. " LEFT JOIN _content_brand b ON b.resource_id = cp.brand "
				. " LEFT JOIN categories_content cc ON cp.resource_id = cc.content_resource_id "
				. " LEFT JOIN categories c1 ON c1.resource_id = cc.category_resource_id "
				. " LEFT JOIN categories c ON c.resource_id = c1.parent_id "
				. " WHERE (cp.status = 1 OR cp.master_status = 'Active') AND cp.lang = $currentLanguage $add "
				. " AND (cp.title LIKE '%$pretraga_po%' OR cp.url LIKE '%$pretraga_po%' OR cp.product_code LIKE '%$pretraga_po%' "
				. " OR c.title LIKE '%$pretraga_po%' "
				. " OR c1.title LIKE '%$pretraga_po%'"
				. " OR b.title LIKE '%$pretraga_po%' ) ";

		$executedMainQuery = mysqli_query($conn, $query) or die(mysqli_error($conn));
		$num = mysqli_num_rows($executedMainQuery);
		if ($num < 32) {
			$searchWordExploded = explode(" ", $pretraga_po);
			$searchFieldsArray = array("cp.title", "cp.product_code", "c.title", "c1.title", "b.title");
			$customAdditionalSQL = "";
			foreach ($searchWordExploded as $word) {

				$customAdditionalSQL .= " AND ( ";
				$littleCounter = 0;
				foreach ($searchFieldsArray as $field) {
					$littleCounter++;
					if ($littleCounter > 1) {
						$customAdditionalSQL .= " OR $field LIKE '%$word%' ";
					} else {
						$customAdditionalSQL .= " $field LIKE '%$word%' ";
					}
				}
				$customAdditionalSQL .= " ) ";
			}

			$query = "SELECT cp.*, b.title as b_title, c.url as master_cat_url, c1.url as sub_cat_url FROM _content_products cp "
					. " LEFT JOIN _content_brand b ON b.resource_id = cp.brand "
					. " LEFT JOIN categories_content cc ON cp.resource_id = cc.content_resource_id "
					. " LEFT JOIN categories c1 ON c1.resource_id = cc.category_resource_id "
					. " LEFT JOIN categories c ON c.resource_id = c1.parent_id "
					. " WHERE (cp.status = 1 OR cp.master_status = 'Active') AND cp.lang = $currentLanguage $add $customAdditionalSQL ";

			$executedMainQuery = mysqli_query($conn, $query) or die(mysqli_error($conn));
			$num = mysqli_num_rows($executedMainQuery);
		}
		$numPages = ceil($num / $poStrani);
		$offset = ($page - 1) * $poStrani;
		if (!$offset || !is_numeric($offset)) {
			$offset = 0;
		}
		$limits = " LIMIT $offset, $poStrani ";
		$executedMainQuery = mysqli_query($conn, $query . " " . $order . " " . $limits) or die(mysqli_error($conn));
		?>
		<div class="container">
			<?php
			$breadcrumbs = array(
				"Pretraga" => "javascript:"
			);
			$f->breadcrumbs($breadcrumbs, $csTitle, $csName);

			if ($num == 0) {
				?>
				<h1>Trenutno nemamo proizvoda pod terminom "<?= $pretraga; ?>"</h1>     
				<?php
			} else if ($pretraga_po != "" || $kategorijaSearch != "") {
				?>
				<h1>Artikli <?= ($pretraga != "") ? 'po terminu <strong>"' . $pretraga . '"</strong>' : ''; ?> <?= ($kategorijaSearch != "") ? 'iz kategorije <strong>"' . $category->title . '"</strong>' : ''; ?> su: <span class="right">pronađeno: <?= $num; ?> proizvoda</a></h1>
				<form class="filterTop marginDownFilter" action="" method="get">
					<input type="hidden" name="pretraga_po" value="<?= $pretraga_po; ?>">
					<input type="hidden" name="kategorija" value="<?= $_GET['kategorija']; ?>">
					<div class="row">
						<?php
						if ($num > 12) {
							?>
							<div class="quarter">
								<div class="selectHolder">
									<select id="po_strani" name='po_strani' onchange="this.form.submit()">
										<option <?= $poStrani == 12 ? 'selected="selected"' : ''; ?> value="12">Prikaži 12 po stranici</option>
										<option <?= $poStrani == 24 ? 'selected="selected"' : ''; ?> value="24">Prikaži 24 po stranici</option>
										<option <?= $poStrani == 32 ? 'selected="selected"' : ''; ?> value="32">Prikaži 32 po stranici</option>
										<option <?= $poStrani == 60 ? 'selected="selected"' : ''; ?> value="60">Prikaži 60 po stranici</option>
									</select>
									<i class="fa fa-angle-down"></i>
								</div>
							</div>
		<?php } ?>
						<div class="quarter">
							<div class="selectHolder">
								<select name='sortiranje' id="sortiranje" onchange="this.form.submit()">
									<option <?= $sortiranje == 1 ? 'selected="selected"' : ''; ?> value="1">Sortiraj po: Cena rastuće</option>
									<option <?= $sortiranje == 2 ? 'selected="selected"' : ''; ?> value="2">Sortiraj po: Cena opadajuće</option>
									<option <?= $sortiranje == 3 ? 'selected="selected"' : ''; ?> value="3">Sortiraj po: Najpopularnije</option>
									<option <?= $sortiranje == 4 ? 'selected="selected"' : ''; ?> value="4">Sortiraj po: Najnovije</option>
									<option <?= $sortiranje == 6 ? 'selected="selected"' : ''; ?> value="6">Sortiraj po: Marki</option>
								</select>
								<i class="fa fa-angle-down"></i>
							</div>
						</div>
						<div class="paginacija right">
							<?php
							if ($numPages > 1) {
								for ($i = 1; $i <= $numPages; $i++) {

									list ($pageUrl, $no) = explode("&strana=", $REQUEST);
									?>
									<a class="pagination <?php if ($i == $page) echo 'active'; ?>" data-page="<?= $i; ?>" href="<?= $pageUrl . "&strana=" . $i; ?>" ><?= $i; ?></a>
									<?php
								}
							}
							?>
						</div>
					</div>
				</form>
				<?php
				echo "<div class='productHolder row'>";
				while ($item = mysqli_fetch_object($executedMainQuery)) {
					include ("little_product-search.php");
				}
				echo "</div>";
				?>
				<form class="filterTop marginDownFilter" action="" method="get">
					<input type="hidden" name="pretraga_po" value="<?= $pretraga_po; ?>">
					<input type="hidden" name="kategorija" value="<?= $_GET['kategorija']; ?>">
					<div class="row">
						<?php
						if ($num > 12) {
							?>
							<div class="quarter left">
								<div class="selectHolder">
									<select id="po_strani" name='po-strani' onchange="this.form.submit()">
										<option <?= $poStrani == 12 ? 'selected="selected"' : ''; ?> value="12">Prikaži 12 po stranici</option>
										<option <?= $poStrani == 24 ? 'selected="selected"' : ''; ?> value="24">Prikaži 24 po stranici</option>
										<option <?= $poStrani == 32 ? 'selected="selected"' : ''; ?> value="32">Prikaži 32 po stranici</option>
										<option <?= $poStrani == 60 ? 'selected="selected"' : ''; ?> value="60">Prikaži 60 po stranici</option>
									</select>
									<i class="fa fa-angle-down"></i>
								</div>
							</div>
		<?php } ?>
						<div class="quarter left">
							<div class="selectHolder">
								<select name='sortiranje' id="sortiranje" onchange="this.form.submit()">
									<option <?= $sortiranje == 1 ? 'selected="selected"' : ''; ?> value="1">Sortiraj po: Cena rastuće</option>
									<option <?= $sortiranje == 2 ? 'selected="selected"' : ''; ?> value="2">Sortiraj po: Cena opadajuće</option>
									<option <?= $sortiranje == 3 ? 'selected="selected"' : ''; ?> value="3">Sortiraj po: Najpopularnije</option>
									<option <?= $sortiranje == 4 ? 'selected="selected"' : ''; ?> value="4">Sortiraj po: Najnovije</option>
									<option <?= $sortiranje == 6 ? 'selected="selected"' : ''; ?> value="6">Sortiraj po: Marki</option>
								</select>
								<i class="fa fa-angle-down"></i>
							</div>
						</div>
						<div class="paginacija right">
							<?php
							for ($i = 1; $i <= $numPages; $i++) {
								if ($numPages > 1) {
									list ($pageUrl, $no) = explode("&strana=", $REQUEST);
									?>
									<a class="pagination <?php if ($i == $page) echo 'active'; ?>" data-page="<?= $i; ?>" href="<?= $pageUrl . "&strana=" . $i; ?>" ><?= $i; ?></a>
									<?php
								}
							}
							?>
						</div>
					</div>
				</form>
		<?php } ?>
		</div>    
		<?php
	}
	include_once ("footer.php");
	?>
</body>
</html>

