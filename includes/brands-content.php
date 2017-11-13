<div class="container">
	<?php
	$breadcrumbs = array(
		"Robne marke" => "/robne-marke"
	);
	$f->breadcrumbs($breadcrumbs, $csTitle, $csName);
	?>
    <div class="holderContent clear">
        <div class="brendsHolder row">
            <div class="letterHolderFull">
            <ul class="clear full">
                <?php
                $result = mysqli_query($conn,"SELECT cb.title as title, cb.url as url, cb.logo as logo FROM _content_brand cb "
                        . " LEFT JOIN _content_products cp ON cp.brand = cb.resource_id "
                        . " WHERE cp.status = 1 AND cb.status = 1 AND cb.title != '' GROUP BY (cb.resource_id) ORDER BY cb.title") or die(mysqli_error($conn));
                while ($brend = mysqli_fetch_object($result)) {
                    $currentleter = strtoupper(substr($brend->title, 0, 1));
                    if ($lastletter != $currentleter) {
                        ?>
                        <li class="letterList">
                            <a href="#<?= $currentleter; ?>"><?= $currentleter; ?></a>
                        </li>
                        <?php
                        $lastletter = $currentleter;
                    }
                }
                ?>
            </ul>
                </div>
            <?php
            $result = mysqli_query($conn,"SELECT cb.title as title, cb.url as url, cb.logo as logo FROM _content_brand cb "
                    . " LEFT JOIN _content_products cp ON cp.brand = cb.resource_id "
                    . " WHERE cp.status = 1 AND cb.status = 1 AND cb.title != '' GROUP BY (cb.resource_id) ORDER BY cb.title") or die(mysqli_error($conn));
            $counter = 0;
            $sum = 0;
            while ($brend = mysqli_fetch_object($result)) {
                $sum++;
                if ($sum == 1) {
                    $firstRow = "firstRow";
                }
                $currentleter = strtoupper(substr($brend->title, 0, 1));
                if ($lastletter != $currentleter) {
                    echo '<div class="row"></div><div id="' . $currentleter . '" class="letterheader full ' . $firstRow . '">' . $currentleter . '</div>';
                    $lastletter = $currentleter;
                    $counter = 0;
                }
                $firstRow = false;
                if ($counter == 6 || $counter == 11 || $counter == 16 || $counter == 21) {
                    $numb = 1;
                } elseif ($counter == 7 || $counter == 12 || $counter == 17 || $counter == 22) {
                    $numb = 2;
                } elseif ($counter == 8 || $counter == 13 || $counter == 18 || $counter == 23) {
                    $numb = 3;
                } elseif ($counter == 9 || $counter == 14 || $counter == 19 || $counter == 24) {
                    $numb = 4;
                } elseif ($counter == 5 || $counter == 10 || $counter == 15 || $counter == 20 || $counter == 25) {
                    $numb = 0;
                } else {
                    $numb = $counter;
                }
                ?>
                <a href="/robne-marke/<?= $brend->url; ?>" class="fifth type<?= $numb; ?>">
                    <figure>
                        <?php
                        if (is_file("uploads/uploaded_pictures/_content_brand/140x60/" . $brend->logo)) {
                            ?>
                            <img class="trueImage" src="/uploads/uploaded_pictures/_content_brand/140x60/<?= $brend->logo; ?>" alt="<?= $brend->title; ?>" title="<?= $brend->title; ?>">
                        <?php } else { ?>
                            <img class="logoImage" src="/images/logo.jpg" alt="<?= $brend->title; ?>" title="<?= $brend->title; ?>">
                        <?php } ?>
                    </figure>
                    <h2><?= $brend->title; ?></h2>
                </a>
                <?php
                $counter++;
                echo ($counter % 5 == 0) ? '<div class="clear"></div>' : '';
            }
            ?>
        </div>
    </div>
</div>