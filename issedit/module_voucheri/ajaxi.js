function search() {
    var phrase = $("#input_search").val();


    var status = $("#status").val();

    /* OTVARAS GIF OVDE*/
    console.log(phrase);
    if (phrase.length >= 3) {
        $.ajax({
            type: "POST",
            url: "work.php",
            data: "action=search_phrase&phrase=" + phrase,
            success: function (msg) {
                $('#ajax_search').html(msg);
                /* ZATVARAS GIF OVDE*/
            }
        });
    } else {

        $.ajax({
            type: "POST",
            url: "work.php",
            data: "action=search_everything&status=" + status,
            success: function (msg) {
                $('#ajax_search').html(msg);
                /* ZATVARAS GIF OVDE*/
            }
        });
    }
}
