<?php

require("../library/config.php");

//Here we have all values from POST and GET
$values = $f->getRequestValues();
$action = $values["action"];

switch ($action) {

    case "save_settings":

        foreach ($values as $key => $field) {

            $langCollection = new Collection("languages");
            $languages = $langCollection->getCollection();
            foreach ($languages as $key => $language) {

                $lang_code = $language->code;
                $settings = new View("settings", $language->id, "lang_id");
                $settings->site_title = $values["site_title_$lang_code"];
                $settings->site_footer = $values["site_footer_$lang_code"];
                $settings->site_description = $values["site_description_$lang_code"];
                $settings->site_email = $values["site_email_$lang_code"];
                $settings->site_firm = $values["site_firm_$lang_code"];
                $settings->site_account = $values["site_account_$lang_code"];
                $settings->site_phone = $values["site_phone_$lang_code"];
                $settings->site_phone_2 = $values["site_phone_2_$lang_code"];
                $settings->site_facebook = $values["site_facebook_$lang_code"];
                $settings->site_facebook_app_id = $values["site_facebook_app_id_$lang_code"];
                $settings->site_twitter = $values["site_twitter_$lang_code"];
                $settings->site_twitter_username = $values["site_twitter_username_$lang_code"];
                $settings->site_google_plus = $values["site_google_plus_$lang_code"];
                $settings->site_api_key = $values["site_api_key_$lang_code"];
                $settings->site_address = $values["site_address_$lang_code"];
                $settings->site_linkedin = $values["site_linkedin_$lang_code"];
                $settings->site_instagram = $values["site_instagram_$lang_code"];
                $settings->site_pinterest = $values["site_pinterest_$lang_code"];
                $settings->site_youtube = $values["site_youtube_$lang_code"];
                $settings->site_vimeo = $values["site_vimeo_$lang_code"];
                $settings->site_koordinate = $values["site_koordinate_$lang_code"];
                $settings->site_embed = $values["site_embed_$lang_code"];
                $settings->site_zip = $values["site_zip_$lang_code"];
                $settings->site_city = $values["site_city_$lang_code"];
                $settings->site_country = $values["site_country_$lang_code"];
                $settings->site_analytic = $values["site_analytic_$lang_code"];
                $settings->site_verification = $values["site_verification_$lang_code"];
                $settings->site_outgoing_server = $values["site_outgoing_server_$lang_code"];
                $settings->site_smtp_port = $values["site_smtp_port_$lang_code"];
                $settings->site_username = $values["site_username_$lang_code"];
                $settings->site_password = $values["site_password_$lang_code"];
                $settings->site_working_time_1 = $values["site_working_time_1_$lang_code"];
                $settings->site_working_time_2 = $values["site_working_time_2_$lang_code"];
                $settings->site_working_time_3 = $values["site_working_time_3_$lang_code"];
                $settings->online_shop = $values["online_shop_$lang_code"];
                $settings->Save();
            }
        }

        $f->redirect("index.php?infomsg=success_save_settings");

        break;
}
?>