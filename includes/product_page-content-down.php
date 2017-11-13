<div class="productBottom">    
    <ul class="tab">
        <?php
        if ($proizvod->marketing_description != "") {
            ?>
            <li>
                <a id="opisTab" href="javascript:" class="tablinks <?php if($uso == '' && ($proizvod->technical_description == "" || $proizvod->warranty == "")){echo "active";}; ?>" onclick="openTab(event, 'opis')">Opis proizvoda</a>
            </li>
        <?php } else { ?>
            <i id="opisTab"></i>
        <?php }
        if ($proizvod->technical_description != "" || $proizvod->warranty != "") {
            ?>
            <li>
                <a id="specifikacijaTab" href="javascript:" class="tablinks <?= ($uso == '' && $proizvod->marketing_description == "")?"active":""; ?>" onclick="openTab(event, 'specifikacija')">Specifikacija proizvoda</a>
            </li>
            <?php
        } else { ?>
            <i id="specifikacijaTab"></i>
        <?php } ?>
        <li>
            <a id="pitanjeTab" href="javascript:" class="tablinks <?= ($uso != '' || ($proizvod->technical_description=="" && $proizvod->marketing_description==""))?"active":""; ?>" onclick="openTab(event, 'pitanje')">Pitanja i odgovori</a>
        </li>
    </ul>

    <?php
    if ($proizvod->marketing_description != "") {
        ?>
        <div id='opis' class="tabcontent <?php if($uso == '' && ($proizvod->technical_description == "" || $proizvod->warranty == "")){echo "actiTab";}; ?>">
            <div class="productPageSpec margin-vertical">

                <div class="tableProductHolder margin-vertical" itemprop="description">
                    <?= $proizvod->marketing_description; ?>
                </div>
            </div>
                <br>
                <p>* <?= $csName; ?> nastoji da opis proizvoda bude što preciznija, ali ne možemo da garantujemo da su svi opisi kompletni i bez grešaka.<br>
                   ** Sve cene su sa uračunatim PDV-om.
                </p>
        </div>
    <?php }
    ?>
    <div class="productPageSpec margin-vertical">
        <?php
        if ($proizvod->technical_description != "" || $proizvod->warranty != "") {
            ?>
            <div id='specifikacija' class="tabcontent <?= ($uso != '')?"":"actiTab"; ?>">
                <div class="tableProductHolder margin-vertical" itemprop="description">
                    <table>
                        <tbody>
                            <?= str_replace("value", "", htmlspecialchars_decode($proizvod->technical_description)); ?>
                            <?php
                            if ($proizvod->warranty) {
                                ?>
                                <tr class="titRed">
                                    <td>Garancija</td>
                                    <td>Garancija</td>
                                    <td><?= $proizvod->warranty; ?></td></tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <br>
                <p>* <?= $csName; ?> nastoji da opis proizvoda bude što preciznija, ali ne možemo da garantujemo da su svi opisi kompletni i bez grešaka.<br>
                   ** Sve cene su sa uračunatim PDV-om.
                </p>
            </div>
            <?php
        }
        if ($userData->resource_id) {
            if ($userData->title != "" && $nameCom == "") {
                $nameMess = $userData->title;
                $userDataMail = mysqli_query($conn,"SELECT `e-mail` as email FROM _content_users WHERE resource_id = $userData->resource_id LIMIT 1");
                $userDataMail = mysqli_fetch_object($userDataMail);
            } else {
                $nameMess = $nameCom;
            }
            if ($userDataMail->email != "" && $emailCom == "") {
                $emailMess = $userDataMail->email;
            } else {
                $emailMess = $emailCom;
            }
        } else {
            $emailMess = $emailCom;
            $nameMess = $nameCom;
        }

        $newToken = $f->generateFormToken('form1');
        ?>

        <div id='pitanje' class="tabcontent <?= ($uso != '' || ($proizvod->technical_description=="" && $proizvod->marketing_description==""))?"actiTab":""; ?>">
            <div class="comentForm">
                <form action="<?php echo htmlspecialchars($REQUEST); ?>#pitanjeTab" method="post" class="clear">
                    <input type="hidden" name="cat_master" value="<?= $niz[1]->url; ?>">
                    <input type="hidden" name="sub_cat" value="<?= $niz[2]->url; ?>">
                    <input type="hidden" name="url" value="<?= $proizvod->url; ?>">
                    <input type="hidden" name="rid" value="<?= $proizvod->resource_id; ?>">
                    <input type="hidden" name="token" value="<?= $newToken; ?>">
                    <input type="hidden" name="capchaHideOne" value="<?= $capcha1; ?>">
                    <input type="hidden" name="capchaHideTwo" value="<?= $capcha2; ?>">
                    <p<?= (in_array("name", $greske)) ? ' class="errorRed"' : ""; ?>>
                        <input type="text" name="name" value="<?= $nameMess; ?>" placeholder="Vaše ime *">
                    </p>
                    <p<?= (in_array("email", $greske)) ? ' class="errorRed"' : ""; ?>>
                        <input type="text" name="email" value="<?= $emailMess; ?>" placeholder="Vaš e-mail *">
                        <span>* Vaš email neće biti vidljiv na sajtu!</span>
                    </p>
                    <p<?= (in_array("message", $greske)) ? ' class="errorRed"' : ""; ?>>
                        <textarea name="message" placeholder="Vaš komentar *"><?= $messageCom; ?></textarea>
                    </p>
                    <p class="capcha<?= (in_array("capcha", $greske)) ? ' errorRed' : ""; ?>">
                        <span><?= $capcha1 . " <i class='fa fa-plus plusImage'></i> " . $capcha2 . " = "; ?></span>
                        <input type="text" name="capcha" value="">
                    </p>
                    <input type="submit" name="komentar" value="Postavi pitanje">
                </form>
            </div>
        <?php
        $quest = mysqli_query($conn,"SELECT * FROM _content_comments WHERE proizvod = '$proizvod->resource_id' AND odgovor != '' ORDER BY id DESC");
        $numbQuest = mysqli_num_rows($quest);
        if ($numbQuest != 0) {
            ?>
            <div class="comentForm komentarisi">
                <div class="commentList">
                    <?php
                    while ($red = mysqli_fetch_object($quest)) {
                        $timeAdd = date('Y-m-d H:i', strtotime('+2 hour', strtotime($red->system_date)));
                        list ($date, $time) = explode(" ", $timeAdd);
                        ?>
                        <div class="singleComent">
                            <div class="askHolder clear">
                                <p class="left"><strong><?= $red->title; ?></strong><br><?= $f->makeFancyDate($red->system_date); ?></p>
                                <p class="askQuest"><?= $red->pitanje; ?></p>
                            </div>
        <?php if ($red->odgovor != "") { ?>
                                <div class="subComment">
                                    <div class="ansQuest"><?= $red->odgovor; ?></div>
                                </div>
                        <?php } ?>
                        </div>
                    <?php }
                    ?>
                </div>
            </div>
            <?php
        }
        ?>
        </div>
        <?php
        $ispodProd = mysqli_query($conn,"SELECT cp.*, b.title as b_title, c.url as master_cat_url, c1.url as sub_cat_url FROM _content_products cp "
                . " LEFT JOIN _content_brand b ON b.resource_id = cp.brand "
                . " LEFT JOIN categories_content cc ON cp.resource_id = cc.content_resource_id "
                . " LEFT JOIN categories c1 ON c1.resource_id = cc.category_resource_id "
                . " LEFT JOIN categories c ON c.resource_id = c1.parent_id "
                . " WHERE cp.brand != $proizvod->brand AND (cp.status = 1 OR cp.master_status = 'Active') AND cc.category_resource_id = $catResourceID AND cp.price <= $proizvod->price ORDER BY cp.price DESC LIMIT 2") or die(mysqli_error($conn));
        $countJeftinijih = mysqli_num_rows($ispodProd);
        $iznadProd = mysqli_query($conn,"SELECT cp.*, b.title as b_title, c.url as master_cat_url, c1.url as sub_cat_url FROM _content_products cp "
                . " LEFT JOIN _content_brand b ON b.resource_id = cp.brand "
                . " LEFT JOIN categories_content cc ON cp.resource_id = cc.content_resource_id "
                . " LEFT JOIN categories c1 ON c1.resource_id = cc.category_resource_id "
                . " LEFT JOIN categories c ON c.resource_id = c1.parent_id "
                . " WHERE cp.brand != $proizvod->brand AND (cp.status = 1 OR cp.master_status = 'Active') AND cc.category_resource_id = $catResourceID AND cp.price >= $proizvod->price ORDER BY price ASC LIMIT 2") or die(mysqli_error($conn));
        $countSkupljih = mysqli_num_rows($iznadProd);

        if ($countSkupljih < 2) {
            $limit = 4 - $countSkupljih;
            $ispodProd = mysqli_query($conn,"SELECT cp.*, b.title as b_title, c.url as master_cat_url, c1.url as sub_cat_url FROM _content_products cp "
                    . " LEFT JOIN _content_brand b ON b.resource_id = cp.brand "
                    . " LEFT JOIN categories_content cc ON cp.resource_id = cc.content_resource_id "
                    . " LEFT JOIN categories c1 ON c1.resource_id = cc.category_resource_id "
                    . " LEFT JOIN categories c ON c.resource_id = c1.parent_id "
                    . " WHERE cp.brand != $proizvod->brand AND (cp.status = 1 OR cp.master_status = 'Active') AND cc.category_resource_id = $catResourceID AND cp.price <= $proizvod->price ORDER BY price DESC LIMIT $limit") or die(mysqli_error($conn));
        }
        if ($countJeftinijih < 2) {
            $limit = 4 - $countJeftinijih;
            $iznadProd = mysqli_query($conn,"SELECT cp.*, b.title as b_title, c.url as master_cat_url, c1.url as sub_cat_url FROM _content_products cp "
                    . " LEFT JOIN _content_brand b ON b.resource_id = cp.brand "
                    . " LEFT JOIN categories_content cc ON cp.resource_id = cc.content_resource_id "
                    . " LEFT JOIN categories c1 ON c1.resource_id = cc.category_resource_id "
                    . " LEFT JOIN categories c ON c.resource_id = c1.parent_id "
                    . " WHERE cp.brand != $proizvod->brand AND (cp.status = 1 OR cp.master_status = 'Active') AND cc.category_resource_id = $catResourceID AND cp.price >= $proizvod->price ORDER BY price ASC LIMIT $limit") or die(mysqli_error($conn));
        }

        if ($countJeftinijih > 0 || $countSkupljih > 0) {
            ?>

            <div id="similar" class="productHolder clear" >
                <h6>Korisnici koji su gledali ovaj artikal su pogledali još:</h6>
                <div class="catProductAll">
                    <div  class="productListIndex row">
                        <?php
                        while ($item = mysqli_fetch_object($ispodProd)) {
                            include ("little_product-search.php");
                        }
                        while ($item = mysqli_fetch_object($iznadProd)) {
                            include ("little_product-search.php");
                        }
                    }
                    ?>
                </div>        
            </div>
        </div>        
    </div>
</div>
</div>
</div>