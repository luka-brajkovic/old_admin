function obrisi_sliku(slika) {


    $.ajax({
        type: "POST",
        url: "work.php",
        data: "action=obrisi_sliku&slika=" + slika,
        success: function (msg) {
            $('#galerija').html(msg);

        }
    });
}