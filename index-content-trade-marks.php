<div class="shadow margin-vertical">
    <div class="markeIndex">
        <h1>Robne marke</h1>
        <div class="tabovi">
            <div class='tabs clear'>
                <?php
                $queryBrend = mysql_query("SELECT id, title, logo FROM _content_brend WHERE naslovna = 'Da' AND status = 1 ORDER BY title LIMIT 6") or die(mysql_error());
                while ($data = mysql_fetch_array($queryBrend)) {
                    ?>
                    <div class="sixths">
                        <a href='#tab<?= $data['id']; ?>' title="<?= $data['title']; ?>">
                            <img src="/uploads/uploaded_pictures/_content_brend/80x34/<?= $data['logo']; ?>" title="<?= $data['title']; ?>" alt="<?= $data['title']; ?>" />
                        </a>
                    </div>
                    <?php
                }
                ?>
            </div>
            <?php
            $queryBrends = mysql_query("SELECT id, title, url, opis, logo, resource_id FROM _content_brend WHERE naslovna = 'Da' AND status = 1 ORDER BY title LIMIT 6") or die(mysql_error());
            while ($data = mysql_fetch_array($queryBrends)) {
                ?>
                <div id='tab<?= $data['id']; ?>'>  
                    <div class="tabHolder clear">
                        <div class="quarter left">
                            <div class="tabDesc">
                                <img src="/uploads/uploaded_pictures/_content_brend/140x60/<?= $data['logo']; ?>" title="<?= $data['title']; ?>" alt="<?= $data['title']; ?>" />
                                <p><?= $data['opis']; ?></p>
                                <a href="/robne-marke/<?= $data['url']; ?>" title="<?= $data['title']; ?>">Svi proizvodi brenda <strong><?= $data['title']; ?></strong> <i class="fa fa-chevron-right"></i></a>
                            </div>
                        </div>
                        <div class="quarter-x3 right clear">

                            <?php
                            $queryProizvodiBrend = mysql_query(""
                                    . "SELECT cp.*, b.title as b_title, c.url as master_cat_url, c1.url as sub_cat_url FROM _content_proizvodi cp "
                                    . " LEFT JOIN _content_brend b ON b.resource_id = cp.brand "
                                    . " LEFT JOIN categories_content cc ON cp.resource_id = cc.content_resource_id "
                                    . " LEFT JOIN categories c1 ON c1.resource_id = cc.category_resource_id "
                                    . " LEFT JOIN categories c ON c.resource_id = c1.parent_id "
                                    . " WHERE cp.brand = '" . $data['resource_id'] . "' AND cp.status = 1 ORDER BY cp.num_views DESC LIMIT 4") or die(mysql_error());
                            $counterBrend = 1;
                            while ($dataProizvodBrend = mysql_fetch_array($queryProizvodiBrend)) {
                                if ($counterBrend == 1) {
                                    echo '<div class="half left">';
                                }
                                ?>

                                <div class="littleProductTab">
                                    <a class="littleProductTabAll clear" href="/<?= $dataProizvodBrend['master_cat_url'] . "/" . $dataProizvodBrend['sub_cat_url'] . "/" . $dataProizvodBrend['url'] . "/" . $dataProizvodBrend['resource_id']; ?>" title="<?= $dataProizvodBrend['title']; ?>">
                                        <figure class="third left">
                                            <img class="transition" src="/uploads/uploaded_pictures/_content_proizvodi/321x257/<?= $dataProizvodBrend['product_image']; ?>" alt="<?= $dataProizvodBrend['title']; ?>" title="<?= $dataProizvodBrend['title']; ?>" />
                                        </figure>
                                        <div class="third-x2 right">
                                            <div class="littleProductTabDesc quarterMarginLB">
                                                <strong class="title"><?= $dataProizvodBrend['title']; ?></strong>
                                                <?php if ($dataProizvodBrend['old_price'] > 0 && $dataProizvodBrend['old_price'] != "") { ?> <span class='outer'><span class="inner"><?= number_format(round($dataProizvodBrend['old_price'], 0), 2, ",", "."); ?> rsd</span></span><?php } ?>
                                                <strong<?= (($dataProizvodBrend['old_price'] != '' && $dataProizvodBrend['old_price'] > 0) || $dataProizvodBrend['akcija'] == "Da") ? " popustPrice" : ""; ?>><?= ($dataProizvodBrend['master_price'] != "") ? number_format(round($dataProizvodBrend['master_price'], 0), 2, ",", ".") : number_format(round($dataProizvodBrend['price'], 0), 2, ",", "."); ?> rsd</strong>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <?php
                                if ($counterBrend == 2) {
                                    echo '</div>';
                                    echo '<div class="half right">';
                                }
                                if ($counterBrend == 4) {
                                    echo '</div>';
                                }
                                $counterBrend++;
                            }
                            ?>

                        </div>
                    </div>
                </div><!-- tab1 -->
            <?php } ?>
        </div>
    </div>
</div>