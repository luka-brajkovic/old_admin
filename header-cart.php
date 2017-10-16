<div class="chartable right">
    <div class="clear headerLogin">
        <ul class="right">
            <?php
            if ($configSiteShop == 1) {
                if ($isLoged) {
                    ?>
                    <li class="right account">
                        <p class='long_p'>Pozdrav <strong><?= $userData->ime; ?></strong> <a class="transition" href="/nalog" title="Vaš nalog">Vaš nalog</a> | <a href="/nalog/odjava">Odjavite se</a></p>
                    <li/>
                <?php } else {
                    ?> 
                    <li class="">  
                        <a class="quarterMarginLB" href="/prijava" title="Prijavi se">Prijavi se</a>
                    </li>
                    <li>
                        <a class="transition" href="/registracija" title="Registruj se">Registruj se</a>
                    </li>
                    <?php
                }
            }
            ?>
        </ul>
    </div>
    <div class="headerCart clear">
        <div class="right">
            <?php if ($configSiteShop == 1) { ?>
                <div class="wishHeader">
                    <a href="/lista-zelja">
                        <i class="fa fa-heart-o left"></i>
                        <div class="rignt">
                            <?php
                            if ($isLoged && $userData->resource_id != "") {
                                $itemsInW = new Collection("list_zelja");
                                $itemsInWArr = $itemsInW->getCollection("WHERE user_rid = $userData->resource_id");
                                ?>
                                <span class="transition"><?= count($itemsInWArr); ?></span>
                                <?php
                            } else {
                                ?>
                                <span class="transition">0</span>
                            <?php } ?>
                            <em>Lista želja</em>
                        </div>
                    </a>
                </div>
            <?php } ?>
        </div>
        <div class="right">
            <div class="cartHeader quarterMarginRS">
                <?php if ($configSiteShop == 1) { ?>
                    <a href="/korpa" title="Idi u korpu">
                        <i class="fa fa-cart-arrow-down left"></i>
                        <div class="right">
                            <?php
                            $sessionID = session_id();
                            $korpa = new View("korpa", $sessionID, 'session_id');
                            if (!empty($korpa->id)) {
                                $queryKorpa = $db->execQuery("SELECT COUNT(id) as korpa_suma from proizvodi_korpe WHERE korpa_rid = $korpa->id");
                                $dataKorpa = mysql_fetch_row($queryKorpa);
                                if (count($dataKorpa) > 0) {
                                    $sumaKorpa = $dataKorpa[0];
                                } else {
                                    $sumaKorpa = 0;
                                }
                            } else {
                                $sumaKorpa = 0;
                            }
                            ?>
                            <span id="cart_count" class="transition"><?= $sumaKorpa; ?></span><br>
                            <em>Korpa</em>
                        </div>
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>
    <li class="right">
        <a class="headerPhone" href="tel:<?= str_replace(array(" ", "-", "/"), "", $configSitePhone); ?>" title="Kontaktirajte nas"><?= str_replace("+381", "0", $configSitePhone); ?></a>
    </li>
</div>
