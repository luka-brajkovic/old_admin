<!DOCTYPE html>
<html lang="sr" <?= ($htmlTagAddOG != "") ? $htmlTagAddOG : ""; ?>>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge"><![endif]-->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title><?= str_replace("&nbsp;", " ", (empty($titleSEO)) ? htmlspecialchars_decode($csTitle) : htmlspecialchars_decode($titleSEO)); ?></title>
        <meta name="description" content='<?= trim(str_replace("&nbsp;", " ", (empty($descSEO)) ? htmlspecialchars_decode($csDesc) : htmlspecialchars_decode($descSEO))); ?>'><?php if (strpos($csDomain, "wds.in.rs") !== FALSE) { ?>

        <meta name="robots" content="noindex, nofollow"><?php } else {
    ?>

        <meta name="robots" content="index, follow, noodp, noydir"><?php }
?>

        <meta name="Author" content="<?= $csName; ?>, <?= $csEmail; ?>"><?php if ($csFacebookAppID != "") { ?>
        <meta property="fb:app_id" content="<?= $csFacebookAppID; ?>"><?php }
?>

        <meta property="og:title" content='<?= (empty($titleSEO)) ? htmlspecialchars_decode($csTitle) : htmlspecialchars_decode($titleSEO); ?>'>
        <meta property="og:description" content='<?= trim(str_replace("&nbsp;", " ", (empty($descSEO)) ? htmlspecialchars_decode($csDesc) : htmlspecialchars_decode($descSEO))); ?>'>
        <meta property="og:url" content="<?= rtrim($csDomain, "/") . $REQUEST; ?>"><?php if ($ogType != "") { ?>

        <meta property="og:type" content="<?= $ogType; ?>"><?php } else {
            ?>

        <meta property="og:type" content="business.business">
        <meta property="business:contact_data:street_address" content="<?= $csAddress; ?>"> 
        <meta property="business:contact_data:locality" content="<?= $csCity; ?>">
        <meta property="business:contact_data:postal_code" content="<?= $csZip; ?>">
        <meta property="business:contact_data:country_name" content="Srbija"><?php list ($lat, $long) = explode(", ", $csCoordinates); ?>

        <meta property="place:location:latitude" content="<?= $lat; ?>">
        <meta property="place:location:longitude" content="<?= $long; ?>">
        <meta property="business:contact_data:email" content="<?= $csEmail; ?>">
        <meta property="business:contact_data:phone_number" content="<?= $csPhone; ?>">
        <meta property="business:contact_data:website" content="<?= $csDomain; ?>"><?php
    }
?>

        <meta property="og:site_name" content="<?= $csName; ?>">
        <meta property="og:locale" content="sr_RS"><?php
        if (strpos($csDomain, "https") !== false) {
            ?>

            <meta property="og:image" content="<?= (empty($imgSEO)) ? $csDomain . "images/share.jpg" : rtrim($csDomain, "/") . $imgSEO; ?>">
            <meta property="og:image:url" content="<?= (empty($imgSEO)) ? $csDomain . "images/share.jpg" : rtrim($csDomain, "/") . $imgSEO; ?>">
            <meta property="og:image:secure_url" content="<?= (empty($imgSEO)) ? $csDomain . "images/share.jpg" : rtrim($csDomain, "/") . $imgSEO; ?>"><?php } else {
            ?>

            <meta property="og:image" content="<?= (empty($imgSEO)) ? $csDomain . "images/share.jpg" : rtrim($csDomain, "/") . $imgSEO; ?>">
            <meta property="og:image:url" content="<?= (empty($imgSEO)) ? $csDomain . "images/share.jpg" : rtrim($csDomain, "/") . $imgSEO; ?>"><?php
        }
        if ($csTwitterUsername != "") {
            ?>

            <meta name="twitter:site" content="<?= $csTwitterUsername; ?>">
            <meta name="twitter:card" content="summary_large_image">
            <meta name="twitter:title" content="<?= ($titleSEO != "") ? $titleSEO : $csTitle; ?>">
            <meta name="twitter:description" content="<?= ($descSEO != "") ? $descSEO : $csDesc; ?>">
            <meta name="twitter:image" content="<?= (empty($imgSEO)) ? $csDomain . "images/share.jpg" : rtrim($csDomain, "/") . $imgSEO; ?>"><?php
    }
    if ($csVerification != "") {
        ?>

            <meta name="google-site-verification" content="<?= $csVerification; ?>"><?php
        }
        if ($csGooglePlus != "") {
            ?>

            <link rel="publisher" href="<?= $csGooglePlus; ?>"><?php
    }
    if (isset($canonical)) {
        ?>

            <link rel="canonical" href="<?= $canonical; ?>"><?php }
        ?>

        <link rel="sitemap" type="application/xml" title="Sitemap" href="/sitemap.xml">

        <style type="text/css"><?php
    include_once "css/style.php";
    include_once "css/font-awesome.min.css";
        ?>.backHomeLink{text-decoration:underline}</style><?php
    if (is_file("images/logo.png")) {
        $logoEx = "png";
    } elseif (is_file("images/logo.svg")) {
        $logoEx = "svg";
    } elseif (is_file("images/logo.jpg")) {
        $logoEx = "jpg";
    }
    if ($urlAKTIVE == "/") {
        $sameAs = "";
        if ($csFacebook != "") {
            $sameAs.='"' . $csFacebook . '",';
        }
        if ($csGooglePlus != "") {
            $sameAs.='"' . $csGooglePlus . '",';
        }
        if ($csTwitter != "") {
            $sameAs.='"' . $csTwitter . '",';
        }
        if ($csLinkedIn != "") {
            $sameAs.='"' . $csLinkedIn . '",';
        }
        $sameAs = rtrim($sameAs, ",");
            ?>

            <script type="application/ld+json">
                {
                "@context": "http://schema.org",
                "@type": "WebSite",
                "url": "<?= $csDomain; ?>",
                "potentialAction": {
                "@type": "SearchAction",
                "target": "<?= $csDomain; ?>pretraga?pretraga_po={search_term_string}",
                "query-input": "required name=search_term_string"
                }
                }
            </script>
            <script type="application/ld+json">
                {
                "@context" : "http://schema.org",
                "@type" : "WebSite",
                "name" : "<?= $csName; ?>",
                "url" : "<?= rtrim($csDomain, "/"); ?>"
                }
            </script>
            <script type="application/ld+json">
                {
                "@context": "http://schema.org",
                "@type": "Organization",
                "name": "<?= $csName; ?>",
                "url": "<?= rtrim($csDomain, "/"); ?>",
                "logo": "<?= $csDomain; ?>images/logo.<?= $logoEx; ?>",
                "sameAs" : [<?= $sameAs; ?> ],
                "description": "<?= $csDesc; ?>",
                "contactPoint" : [
                { "@type" : "ContactPoint",
                "telephone" : "<?= str_replace(array(" ", "-", "/"), "", $csPhone); ?>",
                "contactType" : "sales",
                "email" : "<?= $csEmail; ?>"
                } ],
                "address" : [
                { "@type" : "PostalAddress",
                "streetAddress" : "<?= $csAddress; ?>",
                "postalCode" : "<?= $csZip; ?>",
                "addressLocality" : "<?= $csCity; ?>"
                } ]
                }
            </script>
            <script type="application/ld+json">
                {
                "@context": "http://schema.org",
                "@type": "Store",
                "@id": "<?= $csDomain; ?>",
                "name": "<?= $csName; ?>",
                "image": "<?= $csDomain . "images/share.jpg"; ?>",
                "priceRange": "<?= str_replace(".00", "", $allProductPrce->min_price) . "RSD - " . str_replace(".00", "", $allProductPrce->max_price) . "RSD"; ?>",
                "address": {
                "@type": "PostalAddress",
                "streetAddress": "<?= $csAddress; ?>",
                "addressLocality": "<?= $csCity; ?>",
                "addressRegion": "PŽ",
                "postalCode": "<?= $csZip; ?>",
                "addressCountry": "RS"
                },
                "geo": {
                "@type": "GeoCoordinates",
                "latitude": <?= $lat; ?>,
                "longitude": <?= $long; ?>
                },
                "url": "<?= $csDomain; ?>",
                "telephone": "<?= str_replace(array(" ", "-", "/"), "", $csPhone); ?>",
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
        ?>
