<?php

$bodyUser = '<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="utf-8">
    <title>' . $configSiteTitle . '</title>
    <meta name="Author" content="' . $configSiteFirm . ', ' . $configSiteEmail . '">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style type="text/css">
        @charset "utf-8";
        body{margin: 10px 0; font-family: Arial, Helvetica;padding: 0 10px; background: #F6F6F6; font-size: 14px;}
        table {border-collapse: collapse;}
        td {font-family: Arial, Helvetica; color: #666;}
        td img {max-width: 100%;}

        @media only screen and (max-width: 659px) {
            body,table,td,p,a,li,blockquote {
                -webkit-text-size-adjust:none !important;
            }
            table {width: 100% !important;}
            .responsive-image img {
                height: auto !important;
                max-width: 100% !important;
            }
        }
    </style>
</head>
<body>
    <table cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td>
                <table cellpadding="0" cellspacing="0" align="center" width="660" style="border:1px solid #E7E7E7;background-color: #FDFDFD;">
                    <tr>
                        <td align="center" class="responsive-image">
                            <a href="' . $configSiteDomain . '" title="Logo ' . $configSiteTitle . '" style="margin: 20px 0px 0px 0px;display: block">';
if (is_file("../images/logo.png")) {
    $ext = "png";
} elseif (is_file("../images/logo.jpg")) {
    $ext = "jpg";
}
$bodyUser .= '<img src="' . $configSiteDomain . 'images/logo.' . $ext . '" alt="Logo" titl="Logo" height="100px" />';
$bodyUser .= '</a>
                        </td>
                    </tr>
                    <tr><td><div style="font-size: 0; line-height: 0; width: 660px; height: 1px; background-color: #D6D6D6;margin: 20px auto;">&nbsp;</div></td></tr>
                    <tr>
                        <td style="padding: 0px 15px 20px 15px;font-size: 14px;">
                            ' . $newsletterDataSend . '<br /><br />
                            <div>Spoštovanjem tim sajta ' . $configSiteFirm . '</div>
                        </td>
                    </tr>
                    <tr><td style="font-size: 0; line-height: 0;" height="40">&nbsp;</td></tr>
                    <tr><td><div style="font-size: 0; line-height: 0; width: 660px; height: 1px; background-color: #D6D6D6;margin: 0 auto;">&nbsp;</div></td></tr>
                    <tr><td style="font-size: 0; line-height: 0;" height="10">&nbsp;</td></tr>
                    <tr>
                        <td style="padding: 10px 15px 10px 15px;font-size: 12px;">
                            <div style="float:left">
                                <div style="color: #201559;font-weight: 400;margin-bottom:10px;">' . $configSiteFirm . '</div>
                                <div><a href="mailto:' . $configSiteEmail . '" title="Email ' . $configSiteTitle . '" style="text-decoration: underline dotted;color: #000;">' . $configSiteEmail . '</a><br /><a href="tel:' . $configSitePhone . '" title="Telefon ' . $configSiteTitle . '" style="text-decoration: underline dotted;color: #000;">' . str_replace(' ', '', $configSitePhone) . '</a><br /><a href="' . $configSiteDomain . '" title="' . $configSiteTitle . '" style="text-decoration: underline dotted;color: #000;">' . $configSiteDomain . '</a></div>
                            </div>    
                            <a href="' . $configSiteDomain . '" title="' . $configSiteFirm . '">
                                <img src="' . $configSiteDomain . 'images/logo.jpg" alt="Logo ' . $configSiteFirm . '" title="Logo ' . $configSiteFirm . '" style="float: right" height="60px">
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 15px 10px 15px; color: #FFFFFF;">
                            <div style="float: left;">';
if ($configSiteFacebook != "") {
    $bodyUser .= '<a href="' . $configSiteFacebook . '" title="Facebook strana" style="text-decoration: none;display: inlne-block;margin-right: 15px;">
                                    <img src="' . $configSiteDomain . 'images/facebook_icon.png" width="20" height="20" alt="Facebook strana" title="Facebook strana ' . $configSiteFirm . '">
                                </a>';
}
if ($configSiteGooglePlus != "") {
    $bodyUser .= '<a href="' . $configSiteGooglePlus . '" title="Google Plus strana" style="text-decoration: none;display: inlne-block;margin-right: 15px;">
                                    <img src="' . $configSiteDomain . 'images/google_plus_icon.png" width="20" height="20" alt="Google Plus strana" title="Google Plus strana ' . $configSiteFirm . '">
                                </a>';
}
if ($configSiteTwitter != "") {
    $bodyUser .= '<a href="' . $configSiteTwitter . '" title="Twitter strana" style="text-decoration: none;display: inlne-block;margin-right: 15px;">
                                    <img src="' . $configSiteDomain . 'images/twitter_icon.png" width="20" height="20" alt="Twitter strana" title="Twitter strana ' . $configSiteFirm . '">
                                </a>';
}
$bodyUser .= '</div>
                        </td>
                    </tr>
                </table>
                <table cellpadding="0" cellspacing="0" align="center" width="660">
                    <tr>
                        <td style="padding: 10px 15px 10px 15px;font-size: 11px;clear:both;">
                            <div>
                                * Ukoliko želite da se odjavite sa naše newsletter liste kliknite <a href="' . $configSiteDomain . 'odjava-sa-newsletter/promeniumd5" style="text-decoration: underline dotted;color: #000;">ovde</a>.
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>';
