function validateEmail(a){var o=/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;return o.test(a)}$(document).ready(function(){$(window).scroll(function(){$(this).scrollTop()>200?$(".scrollup").fadeIn():$(".scrollup").fadeOut()}),$(".scrollup").click(function(){return $("html, body").animate({scrollTop:0},800),!1}),$(".view_type a").click(function(){var a=$(this).attr("id");$.ajax({type:"POST",async:!0,url:"/work.php",data:"klasa="+a+"&action=change_show_type",success:function(){location.reload()}})}),$(".checkClick").click(function(){$(this).toggleClass("active")});var o=$(".bannerRight").height();$(".content").css("min-height",o+"px"),$("#clickChangeAddress").click(function(){var a=$("#changeAddress").css("display");"none"===a&&$("#changeAddress").slideDown("fast")}),$("#hangeAddressHide").click(function(){var a=$("#changeAddress").css("display");"block"===a&&$("#changeAddress").slideUp("fast")})}),$(".active.have_sub").parent().find("ul").slideDown("slow"),$(document).on("change",".korpa-proizvod-kolicina",function(){var a=$(this).data(),o=$(this).val(),i=a.itemId;$.ajax({type:"POST",async:!0,url:"/work.php",data:"itemID="+i+"&q="+o+"&action=update-q-cart",success:function(){location.reload()}})}),$(document).on("click","#add_to_cart_notifier #popUpInner p a:first-child, a#close ",function(){$("#add_to_cart_notifier").fadeOut("fast")}),$(window).load(function(){var a=Math.max.apply(null,$(".ispod").map(function(){return $(this).height()}).get());$(".ispod").css("height",a+"px")}),$(document).on("submit","#forgotPassForm",function(){$(this).parent().find("em").remove(),$(this).find("input[type='text']").css("border","1px solid #CCC");var a=$(this).find("input[type='text']").val();return validateEmail(a)?($.ajax({type:"POST",async:!0,url:"/work.php",data:"email="+a+"&action=check_if_mail_exist",success:function(a){a=$.trim(a),"1"==a?window.location.href="<?= $configSiteDomain; ?>strana/aktivacioni-link-poslat":"2"==a?($("#forgotPassForm").after("<em style='color:red; padding:0 20px; display:block; padding-bottom:10px;'>Poštovani Vaš nalog je neaktivan. Poslat Vam je e-mail sa aktivacionim linkom prilikom registracije. Pokušajte da ga pronađete u inboxu, spam ili trash folderu Vaše email adrese!</em>"),$("#forgotPassForm").find("input[type='text']").css("border","1px solid red")):"3"==a?($("#forgotPassForm").parent().find("a").after("<em style='color:red; padding:0 20px; display:block; padding-bottom:10px;'>Poštovani, Nalog sa ovim e-mailom ne postoji. Molimo Vas pokušajte ponovo!</em>"),$("#forgotPassForm").find("input[type='text']").css("border","1px solid red")):"4"==a&&($("#forgotPassForm").parent().find("a").after("<em style='color:red; padding:0 20px; display:block; padding-bottom:10px;'>Poštovani, danas Vam je već poslat mail za promenu šifre. Pokušajte da ga pronađete u inboxu, spam ili trash folderu Vaše email adrese!</em>"),$("#forgotPassForm").find("input[type='text']").css("border","1px solid red"))}}),!1):($(this).parent().find("a").after("<em style='color:red; display:block; padding-bottom:10px;'>Niste uneli validan email. Pokusajte ponovo!</em>"),$(this).find("input[type='text']").css("border","1px solid red"),!1)}),$(document).on("click","#forgotPass",function(){$("#popup").fadeIn("slow")}),$(document).on("click","#closer",function(){$("#popup").fadeOut("slow")}),$(document).on("change",".korpa-proizvod-kolicina",function(){var a=$(this).data(),o=$(this).val(),i=a.itemId;$.ajax({type:"POST",async:!0,url:"/work.php",data:"itemID="+i+"&q="+o+"&action=update-q-cart",success:function(){location.reload()}})}),$(document).on("click","#korpa-dostava",function(){var a=$(".dostava:checked").val(),o=$("#ime").val(),i=$("#prezime").val(),n=$("#telefon").val(),t=$("#adresa").val(),e=$("#grad").val(),s=$("#zip").val();$.ajax({type:"POST",async:!0,url:"/work.php",data:"dostava="+a+"&ime="+o+"&prezime="+i+"&telefon="+n+"&adresa="+t+"&grad="+e+"&zip="+s+"&action=update-user-info-cart",success:function(){location.href="/korpa-dostava"}})}),$(document).on("click",".nacin-placanja-radio",function(){var a=$(this).val();if(1==a)var o="Plaćanje pouzećem kuriru prilikom preuzimanja pošiljke. Plaćanje se vrši isključivo gotovinom.";else var o="Uplatnicom na šalteru banke ili pošte ili e-bankingom. Rok za uplatu porudžbenice je tri radna dana odnosno 72 sata, nakon čega će ista biti stornirana.";$("#nacin-placanja").text(o)}),$(document).on("click","#korpa-poruci",function(){var a=$("#napomena").val(),o=$(".nacin-placanja-radio:checked").val();$.ajax({type:"POST",async:!0,url:"/work.php",data:"nacin_placanja="+o+"&napomena="+a+"&action=korpa-zavrsi",success:function(){alert("Uspešno ste izvršili kupovinu!"),location.href="/regen-session.php"}})}),$(".active.have_sub").parent().find("ul").slideDown("slow");function closePopup(){$("#popup").fadeOut("slow")}function closeCart(){$("#add_to_cart_notifier").fadeOut("slow")}