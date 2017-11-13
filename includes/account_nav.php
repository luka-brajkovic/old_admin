<?php
$url_up = $REQUEST;
?>
<div class="holderAccountNav row">
    <div class="quarter">
        <div class="inner">
            <a <?= ($url_up == "/nalog") ? "class='current'" : ""; ?> href="/nalog">Vaš nalog</a>
        </div>
    </div>
    <div class="quarter">
        <div class="inner">
            <a <?= ($url_up == "/nalog/promena-lozinke") ? "class='current'" : ""; ?> href="/nalog/promena-lozinke">Promena lozinke</a>
        </div>
    </div>
    <div class="quarter">    
        <div class="inner">
            <a <?= ($url_up == "/nalog/promena-adrese") ? "class='current'" : ""; ?> href="/nalog/promena-adrese">Promena adrese</a>
        </div>
    </div>
    <div class="quarter">
        <div class="inner">
            <a <?= ($url_up == "/nalog/porudzbine") ? "class='current'" : ""; ?> href="/nalog/porudzbine">Vaše porudžbine</a>
        </div>
    </div>
</div>    


