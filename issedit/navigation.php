<?php
$contentTypesCollection = new Collection("content_types");
$contentTypesList = $contentTypesCollection->getCollection("WHERE id != 0 ORDER BY ordering");
$contentTypesListCategory = $contentTypesCollection->getCollection("WHERE `category_type` != '0'");
?>
<!-- The navigation bar -->
<div id="navbar">
    <ul class="nav">
        <li><a href="/issedit/index.php">Dashboard</a></li>
        <li><a href="#">Content</a>
            <ul>
                <?php
                if ($contentTypesCollection->resultCount >= 0) {
                    foreach ($contentTypesList as $key => $contentType) {
                            ?>
                            <li>
                                <a href="/issedit/module_content/index.php?cid=<?= $contentType->id; ?>">
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
                            <a href="/issedit/module_categories/index.php?cid=<?= $contentType->id; ?>">
                                <?= $contentType->title; ?>
                            </a>
                        </li>
                        <?php
                    }
                }
                ?>
            </ul>
        </li>


        <li><a href="/issedit/module_menus/index.php">Menus</a></li>

        <?php
        $mysql_korpe = mysql_query("SELECT id FROM korpa WHERE (`num_views` = 0 OR `num_views` = '') AND status = 1") or die(mysql_error());
        if (mysql_num_rows($mysql_korpe) > 0) {
            $string = "<img width='10' height='10' style='float:right; margin:4px 0 0 5px;' src='/issedit/blink2.gif' /> (" . mysql_num_rows($mysql_korpe) . ")";
        } else {
            $string = "";
        }
        ?>


        <li><a href="/issedit/module_shopping/index.php">Carts <?= $string; ?></a></li>

        <?php
        $mysql_q = mysql_query("SELECT * FROM _content_komentari WHERE status != 1 AND odgovor = '' ") or die(mysql_error());
        if (mysql_num_rows($mysql_q) > 0) {
            $string = "<img width='10' height='10' style='float:right; margin:4px 0 0 5px;' src='/issedit/blink2.gif' /> (" . mysql_num_rows($mysql_q) . ")";
        } else {
            $string = "";
        }
        ?>

        <li><a href="/issedit/module_content/index.php?cid=81">Comments <?php echo $string; ?></a></li>

        <li><a href="/issedit/newsletter.php?cid=77">Newsletter send</a></li>



    </ul>
</div>
<!-- End of navigation bar" -->