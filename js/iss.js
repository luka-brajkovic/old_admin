$(document).ready(function () {
    $(window).scroll(function () {
        if ($(this).scrollTop() > 200) {
            $('.scrollup').fadeIn();
        } else {
            $('.scrollup').fadeOut();
        }
    });
    $('.scrollup').click(function () {
        $("html, body").animate({scrollTop: 0}, 800);
        return false;
    });
    $('.goTop').click(function () {
        $("html, body").animate({scrollTop: 0}, 800);
        return false;
    });

    $(".view_type a").click(function () {

        var klasa = $(this).attr("id");

        $.ajax({
            type: "POST",
            async: true,
            url: "/work.php",
            data: "klasa=" + klasa + "&action=change_show_type",
            success: function (msg) {
                location.reload();
            }
        });
    });

    $('.checkClick').click(function () {
        $(this).toggleClass('active');
    });
    var visina = $(".bannerRight").height();
    $(".content").css("min-height", visina + "px");

    $("#clickChangeAddress").click(function () {
        var state = $("#changeAddress").css("display");
        if (state === "none") {
            $("#changeAddress").slideDown("fast");
        }
    });

    $("#hangeAddressHide").click(function () {
        var state = $("#changeAddress").css("display");
        if (state === "block") {
            $("#changeAddress").slideUp("fast");
        }
    });
});
$(".active.have_sub").parent().find("ul").slideDown("slow");

$(document).on('change', '.korpa-proizvod-kolicina', function () {
    var data = $(this).data();
    var val = $(this).val();
    var itemID = data.itemId;

    $.ajax({
        type: "POST",
        async: true,
        url: "/work.php",
        data: "itemID=" + itemID + "&q=" + val + "&action=update-q-cart",
        success: function (msg) {
            location.reload();
        }
    });
});

$(document).on("click", "#add_to_cart_notifier #popUpInner p a:first-child, a#close ", function () {
    $("#add_to_cart_notifier").fadeOut("fast");
});

$(window).load(function () {
    var maxHeightDesc = Math.max.apply(null, $(".drziOpis h4").map(function ()
    {
        return $(this).height();
    }).get());
    $(".drziOpis h4").css("height", maxHeightDesc + "px");

});
$(window).load(function () {
    var maxHeightDesc = Math.max.apply(null, $(".ispod").map(function ()
    {
        return $(this).height();
    }).get());
    $(".ispod").css("height", maxHeightDesc + "px");
});
function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}
$(document).on("submit", "#forgotPassForm", function () {
    $(this).parent().find("em").remove();
    $(this).find("input[type='text']").css("border", "1px solid #CCC");
    var email = $(this).find("input[type='text']").val();

    if (validateEmail(email)) {
        $.ajax({
            type: "POST",
            async: true,
            url: "/work.php",
            data: "email=" + email + "&action=check_if_mail_exist",
            success: function (msg) {
                msg = $.trim(msg);
                if (msg == "1") {
                    window.location.href = "http://www.skycomputer.rs/strana/aktivacioni-link-poslat";
                } else if (msg == "2") {
                    $("#forgotPassForm").after("<em style='color:red; padding:0 20px; display:block; padding-bottom:10px;'>Poštovani Vaš nalog je neaktivan. Poslat Vam je e-mail sa aktivacionim linkom prilikom registracije. Pokušajte da ga pronađete u inboxu, spam ili trash folderu Vaše email adrese!</em>");
                    $("#forgotPassForm").find("input[type='text']").css("border", "1px solid red");
                } else if (msg == "3") {
                    $("#forgotPassForm").parent().find("a").after("<em style='color:red; padding:0 20px; display:block; padding-bottom:10px;'>Poštovani, Nalog sa ovim e-mailom ne postoji. Molimo Vas pokušajte ponovo!</em>");
                    $("#forgotPassForm").find("input[type='text']").css("border", "1px solid red");
                } else if (msg == "4") {
                    $("#forgotPassForm").parent().find("a").after("<em style='color:red; padding:0 20px; display:block; padding-bottom:10px;'>Poštovani, danas Vam je već poslat mail za promenu šifre. Pokušajte da ga pronađete u inboxu, spam ili trash folderu Vaše email adrese!</em>");
                    $("#forgotPassForm").find("input[type='text']").css("border", "1px solid red");
                }
            }
        });
        return false;
    } else {
        $(this).parent().find("a").after("<em style='color:red; display:block; padding-bottom:10px;'>Niste uneli validan email. Pokusajte ponovo!</em>");
        $(this).find("input[type='text']").css("border", "1px solid red");
        return false;
    }
});
$(document).on("click", "#forgotPass", function () {
    $("#popup").fadeIn("slow");
});
$(document).on("click", "#closer", function () {
    $("#popup").fadeOut("slow");
});

$(document).on('change', '.korpa-proizvod-kolicina', function () {
    var data = $(this).data();
    var val = $(this).val();
    var itemID = data.itemId;

    $.ajax({
        type: "POST",
        async: true,
        url: "/work.php",
        data: "itemID=" + itemID + "&q=" + val + "&action=update-q-cart",
        success: function (msg) {
            location.reload();
        }
    });
});

$(document).on('click', '#korpa-dostava', function () {

    var dostava = $('.dostava:checked').val();
    var ime = $('#ime').val();
    var prezime = $('#prezime').val();
    var telefon = $('#telefon').val();
    var adresa = $('#adresa').val();
    var grad = $('#grad').val();
    var zip = $('#zip').val();

    $.ajax({
        type: "POST",
        async: true,
        url: "/work.php",
        data: "dostava=" + dostava + "&ime=" + ime + "&prezime=" + prezime + "&telefon=" + telefon + "&adresa=" + adresa + "&grad=" + grad + "&zip=" + zip + "&action=update-user-info-cart",
        success: function (msg) {
            location.href = "/korpa-dostava";
        }
    });
});

$(document).on('click', '.nacin-placanja-radio', function () {

    var val = $(this).val();
    if (val == 1) {
        var text = "Plaćanje pouzećem kuriru prilikom preuzimanja pošiljke. Plaćanje se vrši isključivo gotovinom.";
    } else {
        var text = "Uplatnicom na šalteru banke ili pošte ili e-bankingom. Rok za uplatu porudžbenice je tri radna dana odnosno 72 sata, nakon čega će ista biti stornirana.";
    }

    $('#nacin-placanja').text(text);
});

$(document).on('click', '#korpa-poruci', function () {

    var napomena = $('#napomena').val();
    var nacin_placanja = $('.nacin-placanja-radio:checked').val();

    $.ajax({
        type: "POST",
        async: true,
        url: "/work.php",
        data: "nacin_placanja=" + nacin_placanja + "&napomena=" + napomena + "&action=korpa-zavrsi",
        success: function (msg) {
            alert("Uspešno ste izvršili kupovinu!");
            location.href = "/regen-session.php";
        }
    });
});




$(".active.have_sub").parent().find("ul").slideDown("slow");

$(".have_sub").click(function () {
    var status = $(this).parent().find("ul.sub").css("display");
    if (status === "none") {
        $("ul.sub").css("display", "none");
        $("ul.sub").prev().css("background-color", "");
        $("ul.list li a").css("color", "#3f3c3d");
        $("ul.list li a").css("background-color", "#f9f9f9");
        $(this).css("color", "");
        $(this).css("background-color", "#307AB7");
        $(this).css("color", "#FFF");
        $(this).parent().find("ul").slideDown("slow");
    } else {
        $(this).css("color", "");
        $(this).css("background-color", "");
        $(this).parent().find("ul").slideUp("slow");
    }
}); 