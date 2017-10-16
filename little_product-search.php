<div class="quarter masterHolderProduct" itemscope itemtype="http://schema.org/Product">
    <meta itemprop="brand" content="<?= $item->b_title; ?>">
    <div class="littleProduct clear">
        <div>
            <a href="/<?= (isset($subSubCatView) && $item->master_master_url != "") ? $item->master_master_url . "/" . $item->master_cat_url : $item->master_cat_url . "/" . $item->sub_cat_url; ?>/<?= $item->url . "/" . $item->resource_id; ?>" title='<?= htmlspecialchars_decode($item->title); ?>'>
                <span class="imgHolder">
                    <?php if (is_file("uploads/uploaded_pictures/_content_proizvodi/" . $dimUrlLitSecund . "/" . $item->product_image)) { ?>
                        <img itemprop="image" class="transition" src="/uploads/uploaded_pictures/_content_proizvodi/<?= $dimUrlLitSecund . "/" . $item->product_image; ?>" alt='<?= htmlspecialchars_decode($item->title); ?> slika' title='<?= htmlspecialchars_decode($item->title); ?>' />
                    <?php } elseif (is_file("uploads/uploaded_pictures/_content_proizvodi/" . $dimDataLitList . "/" . $item->product_image)) { ?>
                        <img itemprop="image" class="transition" src="/uploads/uploaded_pictures/_content_proizvodi/<?= $dimDataLitList . "/" . $item->product_image; ?>" alt='<?= htmlspecialchars_decode($item->title); ?> slika' title='<?= htmlspecialchars_decode($item->title); ?>' />
                    <?php } else { ?>
                        <img class="transition" src="/images/no-image.jpg" alt='<?= htmlspecialchars_decode($item->title); ?> slika' title='<?= htmlspecialchars_decode($item->title); ?>' />
                    <?php } ?>                </span> 
                <?php
                if ($item->old_price > $item->master_price && $item->master_price > 0) {
                    $procenat = 100 - (100 * $item->master_price / $item->old_price);
                    ?>
                    <p>
                        <span><?= ceil($procenat); ?>%</span>
                        <span>popust</span>
                    </p>
                    <?php
                } elseif ($item->old_price > $item->price) {
                    $procenat = 100 - (100 * $item->price / $item->old_price);
                    ?>
                    <p>
                        <span><?= ceil($procenat); ?>%</span>
                        <span>popust</span>
                    </p>
                    <?php
                }
                $counntGratis = 0;
                if ($item->gratis_id) {
                    $gratisId = mysql_query("SELECT * FROM _content_proizvodi WHERE resource_id = $item->gratis_id LIMIT 1");
                    $gratisId = mysql_fetch_object($gratisId);

                    if (is_file("uploads/uploaded_pictures/_content_proizvodi/" . $dimUrlLit . "/" . $gratisId->product_image)) {
                        $counntGratis++;
                        ?>
                        <p class="itemGift" style="background-image: url(/images/gift.png), url(/uploads/uploaded_pictures/_content_proizvodi/<?= $dimUrlLit."/".$gratisId->product_image; ?>)">
                        </p>
                        <?php
                    } elseif (is_file("uploads/uploaded_pictures/_content_proizvodi/" . $dimUrlLitSecund . "/" . $gratisId->product_image)) {
                        $counntGratis++;
                        ?>
                        <p class="itemGift" style="background-image: url(/images/gift.png), url(/uploads/uploaded_pictures/_content_proizvodi/<?= $dimUrlLitSecund."/".$gratisId->product_image; ?>)">
                        </p>
                        <?php                        
                    }
                }
                if ($item->gratis_id_2) {
                    $gratisId = mysql_query("SELECT * FROM _content_proizvodi WHERE resource_id = $item->gratis_id_2 LIMIT 1");
                    $gratisId = mysql_fetch_object($gratisId);

                    if (is_file("uploads/uploaded_pictures/_content_proizvodi/" . $dimUrlLit . "/" . $gratisId->product_image)) {
                        ?>
                        <p class="itemGift" style="<?= ($counntGratis==1)?"left: 59px;":""; ?> background-image: url(/images/gift.png), url(/uploads/uploaded_pictures/_content_proizvodi/<?= $dimUrlLit."/".$gratisId->product_image; ?>)">
                        </p>
                        <?php
                    } elseif (is_file("uploads/uploaded_pictures/_content_proizvodi/" . $dimUrlLitSecund . "/" . $gratisId->product_image)) {
                        $counntGratis++;
                        ?>
                        <p class="itemGift" style="background-image: url(/images/gift.png), url(/uploads/uploaded_pictures/_content_proizvodi/<?= $dimUrlLitSecund."/".$gratisId->product_image; ?>)">
                        </p>
                        <?php                        
                    }
                }
                $counntGratis = 0;
                ?>
            </a>
        </div>
        <div class="littleProductDesc">
            <div class="clear">
                <span class="arrow"></span>
                <div class="drziOpis clear">
                    <h4>
                        <a href="/<?= (isset($subSubCatView) && $item->master_master_url != "") ? $item->master_master_url . "/" . $item->master_cat_url : $item->master_cat_url . "/" . $item->sub_cat_url; ?>/<?= $item->url . "/" . $item->resource_id; ?>" title='<?= htmlspecialchars_decode($item->title); ?>'>
                            <span itemprop="name" class="transition"><?= $item->b_title . " " . htmlspecialchars_decode($item->title); ?></span>
                        </a>
                    </h4>
                    <div class="priceSum" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                        <?php if ($item->old_price > 0 && $item->old_price != "") { ?> <span class='outer'><span class="inner"><?= number_format($item->old_price, 2, ",", "."); ?> rsd</span></span><?php } ?>
                        <strong class="margin-vertical<?= (($item->old_price != '' && $item->old_price > 0) || $item->akcija == "Da") ? " popustPrice" : ""; ?>"><b>Cena:</b><span class="microPrice"><?= ($item->master_price > 0) ? number_format($item->master_price, 2, ",", ".") : number_format($item->price, 2, ",", "."); ?></span> rsd</strong>
                        <meta itemprop="price" content="<?= ($item->master_price > 0) ? number_format($item->master_price, 2, ".", "") : number_format($item->price, 2, ".", ""); ?>">
                        <meta itemprop="priceCurrency" content="RSD">
                        <meta itemprop="availability" content="http://schema.org/InStock">
                        <meta itemprop="url" content="<?= $configSiteDomain . $item->master_cat_url . "/" . $item->sub_cat_url . "/" . $item->url . "/" . $item->resource_id; ?>">
                    </div>
                </div>
                <?php if ($configSiteShop == 1) { ?>
                    <a class="addCart" href="javascript:addToCart('<?= $item->resource_id; ?>','1','<?= ($item->master_price > 0) ? $item->master_price : $item->price; ?>')" title="Dodaj u korpu"><i class="fa fa-cart-plus"></i> Dodaj u korpu</a><?php
                    if ($isLoged) {
                        if (in_array($item->resource_id, $nizZelja) && $userData->id != '') { ?>
                            <a href="javascript:" class="addWish left added" data-rid="<?= $item->resource_id; ?>" title="Proizvod je u vašoj listi želja"><i class="fa fa-heart transition"></i></a>                            
                        <?php }else{ ?>
                            <a href="javascript:" class="addWish left" data-rid="<?= $item->resource_id; ?>" title="Dodaj u listu želja"><i class="fa fa-heart transition" ></i></a>
                        <?php }
                    }else{ ?>
                            <a href="javascript:" class="addWish left" data-rid="<?= $item->resource_id; ?>" title="Dodaj u listu želja"><i class="fa fa-heart transition" ></i></a>
                    <?php } ?>
                    <a href="javascript:" class="addCompare right <?php if (isset($numList) && $numList == 0) { ?>add-to-wish-list <?php } ?>" data-rid="<?= $item->resource_id; ?>" title="Uporedi proizvod"><i class="fa fa-arrows-h transition"></i></a>
                <?php } else { ?>
                    <a class="addCart noShop" href="/<?= $item->master_cat_url . "/" . $item->sub_cat_url . "/" . $item->url . "/" . $item->resource_id; ?>" title="Pogledajte proizvod">Pogledajte</a>
                <?php } ?>
            </div>
        </div>
    </div>
</div>