<div class="filterTopHolder margin-vertical clear">
    <div class="catTitile">
        <h1><?= $brandObj->title; ?> proizvodi</h1>
    </div>
    <?php
    if ($numberOfRows > 1) {
        ?>
        <div class="filterTop">
            <div class="row">
                <?php
                if ($numberOfRows > 12) {
                    ?>
                    <div class="quarter">
                        <div class="selectHolder">
                            <select id="po_strani" name='po_strani'>
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
                        <select name='sortiranje' id="sortiranje">
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
                    if ($brojStrana > 1) {
                        for ($i = 1; $i <= $brojStrana; $i++) {
                            ?>
                            <a class="pagination <?php if ($i == $page) echo 'active'; ?>" data-page="<?= $i; ?>" href="javascript:void();" ><?= $i; ?></a>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>