<?php
require("../library/config.php");
$f->checkLogedAdmin("module");
$infomsg = $f->getValue("infomsg");

$module_name = "settings";

$langCollection = new Collection("languages");
$languages = $langCollection->getCollection();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />		
        <title><?= ADMIN_TITLE; ?></title>
        <?php include("../head.php"); ?>
    </head>
    <body>
        <!-- Container -->
        <div id="container">

            <?php include("../header.php"); ?>

            <!-- Background wrapper -->
            <div id="bgwrap">

                <!-- Main Content -->
                <div id="content">
                    <div id="main" style="margin-left:320px;">
                        <?php
                        if ($infomsg != "") {

                            switch ($infomsg) {
                                case "success_save_settings":
                                    ?>
                                    <div class="message success close">
                                        <h2>Success!</h2>
                                        <p>Settings successfuly updated.</p>
                                    </div>
                                    <?php
                                    break;
                            }
                        }
                        ?>

                        <h1>Website Settings</h1>

                        <div class="">
                            <form method="POST" action="work.php" id="edit_settings">
                                <div id="tabs">
                                    <ul>
                                        <?php
                                        foreach ($languages as $key => $language) {
                                            ?>
                                            <li><a href="#tab<?= $language->code; ?>"><?= $language->title; ?></a></li>
                                            <?php
                                        }
                                        ?>
                                    </ul>

                                    <?php
                                    foreach ($languages as $key => $language) {

                                        $settings = new View("settings", $language->id, "lang_id");
                                        $lang_code = $language->code;
                                        ?>
                                        <div id="tab<?= $language->code; ?>">

                                            <fieldset>
                                                <legend>Edit settings for language: <?= $language->title; ?></legend>

                                                <p>
                                                    <label for="site_title_<?= $lang_code; ?>">Site title</label>
                                                    <input type="text" name="site_title_<?= $lang_code; ?>" class="lf" id="site_title_<?= $lang_code; ?>" value="<?= $settings->site_title; ?>" />
                                                </p>

                                                <p>
                                                    <label for="site_footer_<?= $lang_code; ?>">Site footer</label>
                                                    <input type="text" name="site_footer_<?= $lang_code; ?>" class="lf" id="site_footer_<?= $lang_code; ?>" value="<?= $settings->site_footer; ?>" />
                                                </p>

                                                <p>
                                                    <label for="site_keywords_<?= $lang_code; ?>" style="">Site keywords</label> 
                                                    <textarea style="width: 600px; height: 80px;" class="lf" id="site_keywords_<?= $lang_code; ?>" name="site_keywords_<?= $lang_code; ?>"><?= $settings->site_keywords; ?></textarea>
                                                    <span style="font-size: 10px">izrada sajtova, web dizajn, seo, optimizacija, izrada sajta, responsive, beograd, srbija</span>
                                                </p>

                                                <p>
                                                    <label for="site_description_<?= $lang_code; ?>" style="">Site description</label> 
                                                    <textarea style="width: 600px; height: 80px;" class="lf" id="site_description_<?= $lang_code; ?>" name="site_description_<?= $lang_code; ?>"><?= $settings->site_description; ?></textarea>
                                                </p>

                                                <p>
                                                    <label for="site_firm_<?= $lang_code; ?>">Company name</label>
                                                    <input type="text" name="site_firm_<?= $lang_code; ?>" class="lf" id="site_firm_<?= $lang_code; ?>" value="<?= $settings->site_firm; ?>" />
                                                </p>

                                                <p>
                                                    <label for="site_account_<?= $lang_code; ?>">Company account</label>
                                                    <input type="text" name="site_account_<?= $lang_code; ?>" class="lf" id="site_account_<?= $lang_code; ?>" value="<?= $settings->site_account; ?>" />
                                                </p>
                                                <p>
                                                    <label for="site_email_<?= $lang_code; ?>">Site email</label>
                                                    <input type="text" name="site_email_<?= $lang_code; ?>" class="lf" id="site_email_<?= $lang_code; ?>" value="<?= $settings->site_email; ?>" />
                                                </p>
                                                <p>
                                                    <label for="site_phone_<?= $lang_code; ?>">Phone</label>
                                                    <input type="text" name="site_phone_<?= $lang_code; ?>" class="lf" id="site_phone_<?= $lang_code; ?>" value="<?= $settings->site_phone; ?>" />
                                                    <span style="font-size: 10px">+381 11 2345678</span>
                                                </p>
                                                <p>
                                                    <label for="site_address_<?= $lang_code; ?>">Street address</label>
                                                    <input type="text" name="site_address_<?= $lang_code; ?>" class="lf" id="site_address_<?= $lang_code; ?>" value="<?= $settings->site_address; ?>" />
                                                </p>
                                                <p>
                                                    <label for="site_city_<?= $lang_code; ?>">City</label>
                                                    <input type="text" name="site_city_<?= $lang_code; ?>" class="lf" id="site_city_<?= $lang_code; ?>" value="<?= $settings->site_city; ?>" />
                                                </p>
                                                <p>
                                                    <label for="site_country_<?= $lang_code; ?>">Country</label>
                                                    <input type="text" name="site_country_<?= $lang_code; ?>" class="lf" id="site_city_<?= $lang_code; ?>" value="<?= $settings->site_country; ?>" />
                                                </p>
                                                <p>
                                                    <label for="site_zip_<?= $lang_code; ?>">ZIP code</label>
                                                    <input type="text" name="site_zip_<?= $lang_code; ?>" class="lf" id="site_zip_<?= $lang_code; ?>" value="<?= $settings->site_zip; ?>" />
                                                </p>

                                                <p>
                                                    <label for="site_facebook_<?= $lang_code; ?>">Facebook</label>
                                                    <input type="text" name="site_facebook_<?= $lang_code; ?>" class="lf" id="site_facebook_<?= $lang_code; ?>" value="<?= $settings->site_facebook; ?>" />
                                                    <span style="font-size: 10px">https://www.facebook.com/webdizajnsrbija/</span>
                                                </p>
                                                <p>
                                                    <label for="site_twitter_<?= $lang_code; ?>">Twitter</label>
                                                    <input type="text" name="site_twitter_<?= $lang_code; ?>" class="lf" id="site_twitter_<?= $lang_code; ?>" value="<?= $settings->site_twitter; ?>" />
                                                    <span style="font-size: 10px">https://twitter.com/WebDizajnSrbija/</span>
                                                </p>
                                                <p>
                                                    <label for="site_google_plus_<?= $lang_code; ?>">Google Plus</label>
                                                    <input type="text" name="site_google_plus_<?= $lang_code; ?>" class="lf" id="site_google_plus_<?= $lang_code; ?>" value="<?= $settings->site_google_plus; ?>" />
                                                    <span style="font-size: 10px">https://plus.google.com/+WebdizajnsrbijaRsIzradaSajtova/</span>
                                                </p>
                                                <p>
                                                    <label for="site_linkedin_<?= $lang_code; ?>">LinkedIn</label>
                                                    <input type="text" name="site_linkedin_<?= $lang_code; ?>" class="lf" id="site_linkedin_<?= $lang_code; ?>" value="<?= $settings->site_linkedin; ?>" />
                                                    <span style="font-size: 10px">https://www.linkedin.com/in/webdizajnsrbija/</span>
                                                </p>
                                                <p>
                                                    <label for="site_youtube_<?= $lang_code; ?>">You tube</label>
                                                    <input type="text" name="site_youtube_<?= $lang_code; ?>" class="lf" id="site_linkedin_<?= $lang_code; ?>" value="<?= $settings->site_youtube; ?>" />
                                                    <span style="font-size: 10px">https://www.youtube.com/web-dizajn-srbija/</span>
                                                </p>
                                                <p>
                                                    <label for="site_vimeo_<?= $lang_code; ?>">Vimeo</label>
                                                    <input type="text" name="site_vimeo_<?= $lang_code; ?>" class="lf" id="site_linkedin_<?= $lang_code; ?>" value="<?= $settings->site_vimeo; ?>" />
                                                    <span style="font-size: 10px">https://www.vimeo.com/web-dizajn-srbija/</span>
                                                </p>
                                                <p>
                                                    <label for="site_instagram_<?= $lang_code; ?>">Instagram</label>
                                                    <input type="text" name="site_instagram_<?= $lang_code; ?>" class="lf" id="site_linkedin_<?= $lang_code; ?>" value="<?= $settings->site_instagram; ?>" />
                                                    <span style="font-size: 10px">https://www.instagram.com/web-dizajn-srbija/</span>
                                                </p>
                                                <p>
                                                    <label for="site_pinterest_<?= $lang_code; ?>">Pinterest</label>
                                                    <input type="text" name="site_pinterest_<?= $lang_code; ?>" class="lf" id="site_linkedin_<?= $lang_code; ?>" value="<?= $settings->site_pinterest; ?>" />
                                                    <span style="font-size: 10px">https://www.pinterest.com/web-dizajn-srbija/</span>
                                                </p>

                                                <p>
                                                    <label for="site_koordinate_<?= $lang_code; ?>">Google Coordinates</label>
                                                    <input type="text" name="site_koordinate_<?= $lang_code; ?>" class="lf" id="site_koordinate_<?= $lang_code; ?>" value="<?= $settings->site_koordinate; ?>" />
                                                    <span style="font-size: 10px">44.774521, 20.497345</span>
                                                </p>
                                                
                                                <p>
                                                    <label for="site_embed_<?= $lang_code; ?>">Google Maps Embed</label>
                                                    <input type="text" name="site_embed_<?= $lang_code; ?>" class="lf" id="site_embed_<?= $lang_code; ?>" value="<?= $settings->site_embed; ?>" />
                                                </p>

                                                <p>
                                                    <label for="site_analytic_<?= $lang_code; ?>">Google analytic code</label>
                                                    <input type="text" name="site_analytic_<?= $lang_code; ?>" class="lf" id="site_analytic_<?= $lang_code; ?>" value="<?= $settings->site_analytic; ?>" />
                                                    <span style="font-size: 10px">UA-XXXXXXXX-X</span>
                                                </p>
                                                <p>
                                                    <label for="site_verification_<?= $lang_code; ?>">Google site verification</label>
                                                    <input type="text" name="site_verification_<?= $lang_code; ?>" class="lf" id="site_analytic_<?= $lang_code; ?>" value="<?= $settings->site_verification; ?>" />
                                                </p>

                                                <p>
                                                    <label for="online_shop_<?= $lang_code; ?>">Activate Online shop</label>
                                                    <select id="online_shop_<?= $lang_code; ?>" name="online_shop_<?= $lang_code; ?>">
                                                        <option <?= ($settings->online_shop == 1) ? "selected" : ""; ?> value="1">YES</option>
                                                        <option <?= ($settings->online_shop == 2) ? "selected" : ""; ?> value="2">NO</option>
                                                    </select>	
                                                </p>
                                                <input type="submit" class="button" value="Save" id="save_button" name="save_button">

                                            </fieldset>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>  

                                <input type="hidden" name="action" value="save_settings" />

                            </form>
                        </div>

                    </div>
                </div>
                <!-- End of Main Content -->


                <?php include("../sidebar.php"); ?>


            </div>
            <!-- End of bgwrap -->

        </div>
        <!-- End of Container -->

        <?php include("../footer.php"); ?>

    </body>
</html>