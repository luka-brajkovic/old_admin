<div class="productPageAll margin-vertical quarterMarginLM">
    <h1 itemprop="name"><?= $proizvod->b_title . " " . htmlspecialchars_decode($proizvod->title); ?></h1>
    <?php if ($proizvod->master_status != 'Active' && $proizvod->status != 1) { ?>
    <h3 class="noPrdSingle">PROIZVOD VIŠE NIJE DOSTUPAN!</h3>
    <?php } else { ?>
        <table class="margin-vertical">
            <tbody>

                <tr class="oldPrice">
                    <td>
                        <?php if ($proizvod->old_price != '' && $proizvod->old_price != '0' && ($proizvod->old_price > $proizvod->price || $proizvod->old_price > $proizvod->master_price)) { ?> 
                            Stara cena:
                        <?php } ?>
                    </td>
                    <td>
                        <?php if ($proizvod->old_price != '' && $proizvod->old_price != '0' && ($proizvod->old_price > $proizvod->price || $proizvod->old_price > $proizvod->master_price)) { ?> 
                            <?= number_format($proizvod->old_price, 0, ",", "."); ?> rsd
                        <?php } ?>
                        <span class="right">
                            <strong class="bodovi">Broj bodova: <?= ($proizvod->master_price > 0) ? number_format($proizvod->master_price / 1000, 0, ",", ".") : number_format($proizvod->price / 1000, 0, ",", "."); ?></strong>
                        </span>
                    </td>
                </tr>
                <tr class="newPrice" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                    <td>Aktuelna cena:</td>
                    <td class="posRel">
                        <span><?= ($proizvod->master_price > 0) ? number_format($proizvod->master_price, 0, ",", ".") : number_format($proizvod->price, 0, ",", "."); ?></span> rsd
                        <meta itemprop="price" content="<?= ($proizvod->master_price > 0) ? number_format($proizvod->master_price, 0, ".", "") : number_format($proizvod->price, 0, ".", ""); ?>">
                        <meta itemprop="priceCurrency" content="RSD">
                        <meta itemprop="url" content="<?= $csDomain . $proizvod->master_cat_url . "/" . $proizvod->sub_cat_url . "/" . $url . "/" . $rid; ?>">
                        <?php if ($proizvod->master_status == 'Active' && $proizvod->status != 1) { ?>
                            <meta itemprop="availability" content="http://schema.org/LimitedAvailability">
                        <?php } elseif ($proizvod->status != 1) { ?>
                            <meta itemprop="availability" content="http://schema.org/OutOfStock">
                        <?php } else { ?>
                            <meta itemprop="availability" content="http://schema.org/InStock">
                        <?php } ?>
                        <span class="right">
                            <a href="/aktuelnosti/bodovi" title="Više o sistemu bodovanja">Više o sistemu bodovanja [...]</a>
                        </span>
                        <?php
                        if ($proizvod->old_price > $proizvod->master_price && $proizvod->master_price > 0) {
                            $procenat = 100 - (100 * $proizvod->master_price / $proizvod->old_price);
                            ?>
                            <p class="singleProcent right">
                                <span><?= ceil($procenat); ?>%</span>
                                <span>popust</span>
                            </p>
                            <?php
                        } elseif ($proizvod->old_price > $proizvod->price) {
                            $procenat = 100 - (100 * $proizvod->price / $proizvod->old_price);
                            ?>
                            <p class="singleProcent right">
                                <span><?= ceil($procenat); ?>%</span>
                                <span>popust</span>
                            </p>
                            <?php
                        }
                        ?>
                    </td>
                </tr>
                <?php
                if ($proizvod->gratis_id != "") {
                    $gratis = mysqli_query($conn,"SELECT cp.product_image, cp.resource_id, cp.title, cp.url, c.url as sub_url, c1.url as cat_url, cb.title as b_title FROM _content_products cp "
                            . " LEFT JOIN categories_content cc ON cc.content_resource_id = cp.resource_id "
                            . " LEFT JOIN categories c ON c.resource_id = cc.category_resource_id "
                            . " LEFT JOIN categories c1 ON c.parent_id = c1.resource_id "
                            . " LEFT JOIN _content_brand cb ON cb.resource_id = cp.brand "
                            . " WHERE cp.resource_id = $proizvod->gratis_id LIMIT 1");
                    $gratis = mysqli_fetch_object($gratis);
                    ?>
                    <tr class="forDinar">
                        <?php if ($proizvod->gratis_id_2 != "") { ?>
                            <td class="red">Uz kupovinu proizvoda dobijate i ova dva za samo 1 dinar:</td>
                        <?php } else { ?>
                            <td class="red">Uz kupovinu proizvoda dobijate i ovaj za 1 dinar:</td>
                        <?php } ?>
                        <td>
                            <a href="/<?= $gratis->sub_url . "/" . $gratis->cat_url . "/" . $gratis->url . "/" . $gratis->resource_id; ?>" title="<?= $gratis->b_title . " " . $gratis->title; ?>">
                                <?php
                                if (is_file("uploads/uploaded_pictures/_content_products/" . $dimUrlLit . "/" . $gratis->product_image)) {
                                ?>
                                    <img src="/uploads/uploaded_pictures/_content_products/<?= $dimUrlLit . "/" . $gratis->product_image; ?>" alt="<?= $gratis->b_title . " " . $gratis->title; ?>">
                                <?php } elseif (is_file("uploads/uploaded_pictures/_content_products/" . $dimUrlLitSecund . "/" . $gratis->product_image)) { ?>
                                    <img src="/uploads/uploaded_pictures/_content_products/<?= $dimUrlLitSecund . "/" . $gratis->product_image; ?>" alt="<?= $gratis->b_title . " " . $gratis->title; ?>">
                                <?php } ?>
                                <?= $gratis->b_title . " " . $gratis->title; ?>
                            </a>
                        </td>
                    </tr>
                <?php
                }
                if ($proizvod->gratis_id_2 != "") {
                    $gratis = mysqli_query($conn,"SELECT cp.product_image, cp.resource_id, cp.title, cp.url, c.url as sub_url, c1.url as cat_url, cb.title as b_title FROM _content_products cp "
                            . " LEFT JOIN categories_content cc ON cc.content_resource_id = cp.resource_id "
                            . " LEFT JOIN categories c ON c.resource_id = cc.category_resource_id "
                            . " LEFT JOIN categories c1 ON c.parent_id = c1.resource_id "
                            . " LEFT JOIN _content_brand cb ON cb.resource_id = cp.brand "
                            . " WHERE cp.resource_id = $proizvod->gratis_id_2 LIMIT 1");
                    $gratis = mysqli_fetch_object($gratis);
                    ?>
                    <tr class="forDinar">
                        <td class="red">&nbsp;</td>
                        <td>
                            <a href="/<?= $gratis->sub_url . "/" . $gratis->cat_url . "/" . $gratis->url . "/" . $gratis->resource_id; ?>" title="<?= $gratis->b_title . " " . $gratis->title; ?>">
                                <img src="/uploads/uploaded_pictures/_content_products/<?= $dimUrlLit . "/" . $gratis->product_image; ?>" alt="<?= $gratis->b_title . " " . $gratis->title; ?>">
        <?= $gratis->b_title . " " . $gratis->title; ?>
                            </a>
                        </td>
                    </tr>
    <?php } ?>
            </tbody>
        </table>
    <?php } ?>
<?php if ($csShop == 1) { ?>
        <table class="margin-vertical">
            <tbody>
                <?php
                if ($proizvod->status == 1 || $proizvod->master_status == 'Active') {
                    ?>
                    <tr>
                        <td>Količina:</td>
                        <td>
                            <input type="number" value="1" min="1" max="20" id="proizvod-kolicina" data-item-id="<?= $proizvod->resource_id; ?>" data-price="<?= $proizvod->price; ?>" /> komad/a
                        </td>
                    </tr>
                    <?php /* <tr>
                      <td>Ukupna cena:</td>
                      <td class="totalPrice"><?= ($proizvod->master_price > 0) ? number_format($proizvod->master_price, 2, ",", ".") : number_format($proizvod->price, 2, ",", "."); ?> rsd</td>
                      </tr> */ ?>
                    <tr>
                        <td></td>
                        <td class="addToCart clear">
                            <a class="left" href="javascript:addToCart('<?= $proizvod->resource_id; ?>','1','<?= ($proizvod->master_price > 0) ? $proizvod->master_price : $proizvod->price; ?>')" title="Dodaj u korpu"><i class="fa fa-cart-plus"></i> Dodaj u korpu</a>
                            <a href="javascript:" class="addCompare left <?php if ($numList == 0) { ?>add-to-wish-list <?php } ?>" data-rid="<?= $proizvod->resource_id; ?>" title="Uporedi">Uporedi</a>
                            <a href="#pitanjeTab" onclick="openTab(event, 'pitanje')">Postavi pitanje za proizvod</a>
                        </td>
                    </tr>
                    <?php
                }
                if ($isLoged):
                    ?>
                    <?php
                    $korisnik = $_SESSION['loged_user'];
                    $numList = mysqli_query($conn,"SELECT * FROM list_zelja WHERE user_rid = '$korisnik' AND product_rid = '$proizvod->resource_id'");
                    $numList = mysqli_num_rows($numList);
                    ?>
                    <tr>
                        <td></td>
                        <td class="addToWishList"><?php if (in_array($proizvod->resource_id, $nizZelja) && $userData->id != '') { ?>
                                <a href="javascript:" class="addWish add-to-wish-list addedWish" data-rid="<?= $proizvod->resource_id; ?>" title="Dodaj u listu želja"><i class="fa fa-heart"></i> Proizvod je u Vašoj listu želja</a>                              
                            <?php } else { ?>
                                <a href="javascript:" class="addWish add-to-wish-list" data-rid="<?= $proizvod->resource_id; ?>" title="Dodaj u listu želja"><i class="fa fa-heart"></i> Dodaj u listu želja</a>                                
                            <?php } ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    <?php } ?>
    <?php
    $dostava = mysqli_query($conn,"SELECT text FROM _content_html_blocks WHERE resource_id = '16' LIMIT 1");
    $dostava = mysqli_fetch_object($dostava);
    echo str_replace("../..", "", $dostava->text);
    ?>
</div>
<script type="text/javascript">function addToCart(){var o = $("#proizvod-kolicina").data(), a = o.itemId, c = o.price, i = $("#proizvod-kolicina").val(); console.log(a), console.log(c), console.log(i), $.ajax({type:"POST", async:!0, url:"/work.php", data:"itemID=" + a + "&price=" + c + "&q=" + i + "&action=add-to-cart", success:function(){location.reload()}})}</script>