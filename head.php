<!DOCTYPE html>
<html lang="sr-RS" <?= ($htmlTagAddOG!="")?$htmlTagAddOG:""; ?>>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?= str_replace("&nbsp;", " ", (empty($titleSEO)) ? htmlspecialchars_decode($configSiteTitle) : htmlspecialchars_decode($titleSEO)); ?></title>
        <meta name="keywords" content="<?= (empty($keysSEO)) ? $configSiteKeywords : $keysSEO; ?>">
        <meta name="description" content='<?= trim(str_replace("&nbsp;", " ", (empty($descSEO)) ? htmlspecialchars_decode($configSiteDescription) : htmlspecialchars_decode($descSEO))); ?>'><?php
        if (strpos($configSiteDomain, "webdizajnsrbija.rs") !== FALSE || strpos($configSiteDomain, "wds.in.rs") !== FALSE) { ?>
        
        <meta name="robots" content="noindex, nofollow"><?php
        } else { ?>
        
        <meta name="robots" content="index, follow, noodp, noydir"><?php
        } ?>
        
        <meta name="Author" content="<?= $configSiteFirm; ?>, <?= $configSiteEmail; ?>">
        <meta property="og:description" content='<?= trim(str_replace("&nbsp;", " ", (empty($descSEO)) ? htmlspecialchars_decode($configSiteDescription) : htmlspecialchars_decode($descSEO))); ?>'>
        <meta property="og:title" content='<?= (empty($titleSEO)) ? htmlspecialchars_decode($configSiteTitle) : htmlspecialchars_decode($titleSEO); ?>'>
        <meta property="og:url" content="http://<?= $HOST . $REQUEST; ?>">
        <meta property="og:image" content="<?= (empty($imgSEO)) ? "http://" . $HOST . "/images/share.jpg" : "http://" . $HOST . $imgSEO; ?>"><?php
        if($ogType!=""){ ?>
        
        <meta property="og:type" content="<?= $ogType; ?>"><?php
        }else{ ?>
        
        <meta property="og:type" content="business.business">
        <meta property="business:contact_data:street_address" content="<?= $configSiteAddress; ?>"> 
        <meta property="business:contact_data:locality" content="<?= $configSiteCity; ?>">
        <meta property="business:contact_data:postal_code" content="<?= $configSiteZip; ?>">
        <meta property="business:contact_data:country_name" content="Srbija"><?php
        list ($lat, $long) = explode(", ", $configSiteKoordinate); ?>
        
        <meta property="place:location:latitude" content="<?= $lat; ?>">
        <meta property="place:location:longitude" content="<?= $long; ?>">
        <meta property="business:contact_data:email" content="<?= $configSiteEmail; ?>">
        <meta property="business:contact_data:phone_number" content="<?= $configSitePhone; ?>">
        <meta property="business:contact_data:website" content="<?= $configSiteDomain; ?>"><?php 
        } ?>        
        <meta property="og:site_name" content="<?= $configSiteFirm; ?>">
        <meta property="og:locale" content="sr_RS" /><?php
        if($configSiteVerification!=""){ ?>
        
        <meta name="google-site-verification" content="<?= $configSiteVerification; ?>" /><?php
        }
        if ($configSiteGooglePlus!="") { ?>
        
        <link rel="publisher" href="<?= $configSiteGooglePlus; ?>"><?php 
        }
        if (isset($canonical)) { ?>
        
        <link rel="canonical" href="<?= $canonical; ?>"><?php
        } ?>
            
        <link rel="apple-touch-icon" sizes="57x57" href="/images/favicon/apple-touch-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="/images/favicon/apple-touch-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/images/favicon/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/images/favicon/apple-touch-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/images/favicon/apple-touch-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/images/favicon/apple-touch-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="/images/favicon/apple-touch-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/images/favicon/apple-touch-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/images/favicon/apple-touch-icon-180x180.png">
        <link rel="icon" type="image/png" href="/images/favicon/favicon-32x32.png" sizes="32x32">
        <link rel="icon" type="image/png" href="/images/favicon/favicon-194x194.png" sizes="194x194">
        <link rel="icon" type="image/png" href="/images/favicon/favicon-96x96.png" sizes="96x96">
        <link rel="icon" type="image/png" href="/images/favicon/android-chrome-192x192.png" sizes="192x192">
        <link rel="icon" type="image/png" href="/images/favicon/favicon-16x16.png" sizes="16x16">
        <link rel="manifest" href="/images/favicon/manifest.json">
        <link rel="shortcut icon" href="/images/favicon/favicon.ico">
        <meta name="msapplication-TileColor" content="#ffc40d">
        <meta name="msapplication-TileImage" content="/images/favicon/mstile-144x144.png">
        <meta name="msapplication-config" content="/images/favicon/browserconfig.xml">
        <meta name="theme-color" content="#ffffff">
        <style type="text/css"><?php 
include_once "css/style.php";
include_once "css/font-awesome.min.css";
?></style><?php
        if ($urlAKTIVE == "/") {
            if (is_file("images/logo.png")) {
                $logoEx = "png";
            } elseif (is_file("images/logo.svg")) {
                $logoEx = "svg";
            } elseif (is_file("images/logo.jpg")) {
                $logoEx = "jpg";
            }
            $sameAs = "";
            if ($configSiteFacebook != "") {
                $sameAs.='"' . $configSiteFacebook . '",';
            }
            if ($configSiteGooglePlus != "") {
                $sameAs.='"' . $configSiteGooglePlus . '",';
            }
            if ($configSiteTwitter != "") {
                $sameAs.='"' . $configSiteTwitter . '",';
            }
            if ($configSiteLinkedIn != "") {
                $sameAs.='"' . $configSiteLinkedIn . '",';
            }
            $sameAs = rtrim($sameAs, ",");
            ?>
            <script type="application/ld+json">
                {
                "@context": "http://schema.org",
                "@type": "WebSite",
                "url": "<?= $configSiteDomain; ?>",
                "potentialAction": {
                "@type": "SearchAction",
                "target": "<?= $configSiteDomain; ?>pretraga?pretraga_po={search_term_string}",
                "query-input": "required name=search_term_string"
                }
                }
            </script>
            <script type="application/ld+json">
                {
                "@context" : "http://schema.org",
                "@type" : "WebSite",
                "name" : "<?= $configSiteFirm; ?>",
                "url" : "<?= rtrim($configSiteDomain, "/"); ?>"
                }
            </script>
            <script type="application/ld+json">
                {
                "@context": "http://schema.org",
                "@type": "Organization",
                "name": "<?= $configSiteFirm; ?>",
                "url": "<?= rtrim($configSiteDomain, "/"); ?>",
                "logo": "<?= $configSiteDomain; ?>images/logo.<?= $logoEx; ?>",
                "sameAs" : [
                <?= $sameAs; ?>
                ],
                "description": "<?= $configSiteDescription; ?>",
                "contactPoint" : [
                { "@type" : "ContactPoint",
                "telephone" : "<?= str_replace(array(" ", "-", "/"), "", $configSitePhone); ?>",
                "contactType" : "sales",
                "email" : "<?= $configSiteEmail; ?>"
                } ],
                "address" : [
                { "@type" : "PostalAddress",
                "streetAddress" : "<?= $configSiteAddress; ?>",
                "postalCode" : "<?= $configSiteZip; ?>",
                "addressLocality" : "<?= $configSiteCity; ?>"
                } ]
                }
            </script>
            <script type="application/ld+json">
                {
                "@context": "http://schema.org",
                "@type": "Store",
                "@id": "<?= $configSiteDomain; ?>",
                "name": "<?= $configSiteFirm; ?>",
                "address": {
                "@type": "PostalAddress",
                "streetAddress": "<?= $configSiteAddress; ?>",
                "addressLocality": "<?= $configSiteCity; ?>",
                "addressRegion": "PŽ",
                "postalCode": "<?= $configSiteZip; ?>",
                "addressCountry": "RS"
                },
                "geo": {
                "@type": "GeoCoordinates",
                "latitude": <?= $lat; ?>,
                "longitude": <?= $long; ?>
                },
                "url": "<?= $configSiteDomain; ?>",
                "telephone": "<?= str_replace(array(" ", "-", "/"), "", $configSitePhone); ?>",
                "openingHoursSpecification": [
                {
                "@type": "OpeningHoursSpecification",
                "dayOfWeek": [
                "Monday",
                "Tuesday",
                "Wednesday",
                "Thursday",
                "Friday"
                ],
                "opens": "09:00",
                "closes": "17:00"
                },
                {
                "@type": "OpeningHoursSpecification",
                "dayOfWeek": "Saturday",
                "opens": "09:00",
                "closes": "13:00"
                }
                ]
                }
            </script>
            <?php
        }
        if ($configSiteAnalyric != "" && strpos($configSiteDomain, "webdizajnsrbija.rs") === FALSE && strpos($configSiteDomain, "wds.in.rs") === FALSE) {
            include ("google-analytics.php");
        }
        ?>