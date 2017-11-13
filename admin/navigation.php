<?php
$contentTypesCollection = new Collection("content_types");
$contentTypesList = $contentTypesCollection->getCollection("WHERE id != 0 ORDER BY ordering");
$contentTypesListCategory = $contentTypesCollection->getCollection("WHERE `category_type` != '0'");
?>
<!-- The navigation bar -->
<div id="navbar">
    <ul class="nav">
        <li><a href="/admin/index.php">Dashboard</a></li>
        <li><a href="#">Content</a>
            <ul>
                <?php
                if ($contentTypesCollection->resultCount >= 0) {
                    foreach ($contentTypesList as $key => $contentType) {
                            ?>
                            <li>
                                <a href="/admin/module_content/index.php?cid=<?= $contentType->id; ?>">
                                    <?= $contentType->title; ?>
                                </a>
                            </li>
                            <?php
                    }
                }
                ?>
            </ul>
        </li>
        <li><a href="#">Categories</a>
            <ul>
                <?php
                if (count($contentTypesListCategory) > 0) {
                    foreach ($contentTypesListCategory as $key => $contentType) {
                        ?>
                        <li>
                            <a href="/admin/module_categories/index.php?cid=<?= $contentType->id; ?>">
                                <?= $contentType->title; ?>
                            </a>
                        </li>
                        <?php
                    }
                }
                ?>
            </ul>
        </li>


        <li><a href="/admin/module_menus/index.php">Menus</a></li>

        <?php
        $mysqli_korpe = mysqli_query($conn,"SELECT id FROM korpa WHERE (`num_views` = 0 OR `num_views` = '') AND status = 1") or die(mysqli_error($conn));
        if (mysqli_num_rows($mysqli_korpe) > 0) {
            $string = "<img width='10' height='10' style='float:right; margin:4px 0 0 5px;' src='/admin/blink2.gif' /> (" . mysqli_num_rows($mysqli_korpe) . ")";
        } else {
            $string = "";
        }
        ?>


        <li><a href="/admin/module_shopping/index.php">Carts <?= $string; ?></a></li>

        <?php
        $mysqli_q = mysqli_query($conn,"SELECT * FROM _content_comments WHERE status != 1 AND odgovor = '' ") or die(mysqli_error($conn));
        if (mysqli_num_rows($mysqli_q) > 0) {
            $string = "<img width='10' height='10' style='float:right; margin:4px 0 0 5px;' src='/admin/blink2.gif' /> (" . mysqli_num_rows($mysqli_q) . ")";
        } else {
            $string = "";
        }
        ?>

        <li><a href="/admin/module_content/index.php?cid=81">Comments <?php echo $string; ?></a></li>

        <li><a href="/admin/newsletter.php?cid=77">Newsletter send</a></li>



    </ul>
</div>
<!-- End of navigation bar" -->