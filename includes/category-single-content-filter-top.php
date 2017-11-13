<div class="filterTopHolder margin-vertical clear">
    <div class="catTitile">
        <?php if ($cat_last_url != '') {
            ?>
            <h1><?= $catMasterData->title ." - ". $catLastData->title; ?></h1>  
            <?php
        } else {
            ?>
            <h1><?= $catMasterData->title ." - ". $catMiddleData->title; ?></h1>  
            <?php
        }
        ?>
    </div>
    <?php
    if($numRows > 1){
    ?>
    <div class="filterTop">
        <div class="row">
            <?php
            if($numRows > 12){
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
            <?php          
            /* ?>
              <div class="view_type right">
              <a class="view <?php
              if (!isset($_SESSION['show_type']) || $_SESSION["show_type"] == "grid") {
              echo "active";
              }
              ?>" title="Tabela" id="grid">
              <img src="/images/col-view.png" alt="grid view">
              </a>
              <a class="view <?php
              if (isset($_SESSION['show_type']) && $_SESSION["show_type"] == "row") {
              echo "active";
              }
              ?>" title="Lista" id="row">
              <img src="/images/row-view.png" alt="row view">
              </a>
              </div>
              <?php */ ?>
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