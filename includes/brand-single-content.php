<div class="container">
	<?php
	$breadcrumbs = array(
		"Robne marke" => "/robne-marke",
		 $brandObj->title => "/robne-marke/".$brandObj->url
	);
	$f->breadcrumbs($breadcrumbs, $csTitle, $csName);

	$poStrani = $f->getValue("po_strani");
	if (isset($poStrani) && is_numeric($poStrani)) {
		$_SESSION['perPage'] = $poStrani;
	} elseif ($_SESSION['perPage']) {
		$poStrani = $_SESSION['perPage'];
	} else {
		$poStrani = 32;
	}

	$page = $f->getValue("page");
	if (!$page || !is_numeric($page)) {
		$page = 1;
	}

	$offset = ($page - 1) * $poStrani;
	$limitSql = " LIMIT $offset, $poStrani";

	$sortiranje = $f->getValue("sortiranje");
	if (!$sortiranje || !is_numeric($sortiranje)) {
		$order = " ORDER BY LENGTH(cp.price), cp.price ASC ";
	} else {
		switch ($sortiranje) {
			case 1:
				$order = " ORDER BY cp.price";
				break;
			case 2:
				$order = " ORDER BY cp.price DESC";
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
	} $sql = "SELECT cp.*, b.title as b_title, c.url as master_cat_url, c1.url as sub_cat_url FROM _content_products cp "
			. " LEFT JOIN _content_brand b ON b.resource_id = cp.brand "
			. " LEFT JOIN categories_content cc ON cp.resource_id = cc.content_resource_id "
			. " LEFT JOIN categories c1 ON c1.resource_id = cc.category_resource_id "
			. " LEFT JOIN categories c ON c.resource_id = c1.parent_id ";
	$where = " WHERE cp.brand = '$brandObj->resource_id' AND (cp.status = 1 OR cp.master_status = 'Active') AND cp.lang = 1";
	$sqlCat = "SELECT DISTINCT cat.resource_id, cat.title, c.title as master_cat_title FROM _content_products cp "
			. " JOIN categories_content cc ON cc.content_resource_id = cp.resource_id "
			. " JOIN categories cat ON cat.resource_id = cc.category_resource_id "
			. " JOIN categories c ON c.resource_id = cat.parent_id " . $where;
	if (isset($_GET['cat'])) {
		$cat = implode(",", $_GET['cat']);
		$where .= " AND cc.category_resource_id IN ($cat)";
	}

	$sqlFinal = $sql . $where . $order . $limitSql;
	$query = $db->execQuery($sqlFinal);
	$numberOfRows = $db->numRows($sql . $where . $order);
	$brojStrana = ceil($numberOfRows / $poStrani);
	include_once("brand-single-content-filter-top.php");
	?>
    <div class="holderContent row">
        <div class="quarter margin-vertical">
			<?php include_once ("filters-brand.php"); ?>
        </div>
        <div class="quarter-x3">
            <div class="productHolder row">
				<?php
				while ($item = mysqli_fetch_object($query)) {
					include ("little_product.php");
				}
				?>
            </div>
            <div class="bottomFilter">
				<?php
				if ($brojStrana > 1) {
					?>
					<div class="filterTop filterBottom">
						<div class="clear">
							<div class="paginacija right">
								<?php
								for ($i = 1; $i <= $brojStrana; $i++) {
									?>
									<a class="pagination <?php if ($i == $page) echo 'active'; ?>" data-page="<?= $i; ?>" href="javascript:void();" ><?= $i; ?></a>
									<?php
								}
								?>
							</div>
						</div>
					</div>
				<?php } ?>
            </div>
        </div>
    </div>
    <div class="leftSide quarter left">
    </div>
</div>