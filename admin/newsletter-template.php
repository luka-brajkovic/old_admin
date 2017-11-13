<?php

$bodyUser = '<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="utf-8">
    <title>' . $csTitle . '</title>
    <meta name="Author" content="' . $csName . ', ' . $csEmail . '">
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
                            <a href="' . $csDomain . '" title="Logo ' . $csTitle . '" style="margin: 20px 0px 0px 0px;display: block">';
if (is_file("../images/logo.png")) {
    $ext = "png";
} elseif (is_file("../images/logo.jpg")) {
    $ext = "jpg";
}
$bodyUser .= '<img src="' . $csDomain . 'images/logo.' . $ext . '" alt="Logo" titl="Logo" height="100px" />';
$bodyUser .= '</a>
                        </td>
                    </tr>
                    <tr><td><div style="font-size: 0; line-height: 0; width: 660px; height: 1px; background-color: #D6D6D6;margin: 20px auto;">&nbsp;</div></td></tr>
                    <tr>
                        <td style="padding: 0px 15px 20px 15px;font-size: 14px;">
                            ' . $newsletterDataSend . '<br /><br />
                            <div>Spoštovanjem tim sajta ' . $csName . '</div>
                        </td>
                    </tr>
                    <tr><td style="font-size: 0; line-height: 0;" height="40">&nbsp;</td></tr>
                    <tr><td><div style="font-size: 0; line-height: 0; width: 660px; height: 1px; background-color: #D6D6D6;margin: 0 auto;">&nbsp;</div></td></tr>
                    <tr><td style="font-size: 0; line-height: 0;" height="10">&nbsp;</td></tr>
                    <tr>
                        <td style="padding: 10px 15px 10px 15px;font-size: 12px;">
                            <div style="float:left">
                                <div style="color: #E5A812;font-weight: 400;margin-bottom:10px;">' . $csName . '</div>
                                <div><a href="mailto:' . $csEmail . '" title="Email ' . $csTitle . '" style="text-decoration: underline dotted;color: #000;">' . $csEmail . '</a><br /><a href="tel:' . $csPhone . '" title="Telefon ' . $csTitle . '" style="text-decoration: underline dotted;color: #000;">' . str_replace(' ', '', $csPhone) . '</a><br /><a href="' . $csDomain . '" title="' . $csTitle . '" style="text-decoration: underline dotted;color: #000;">' . $csDomain . '</a></div>
                            </div>    
                            <a href="' . $csDomain . '" title="' . $csName . '">
                                <img src="' . $csDomain . 'images/logo.jpg" alt="Logo ' . $csName . '" title="Logo ' . $csName . '" style="float: right" height="60px">
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 15px 10px 15px; color: #FFFFFF;">
                            <div style="float: left;">';
if ($csFacebook != "") {
    $bodyUser .= '<a href="' . $csFacebook . '" title="Facebook strana" style="text-decoration: none;display: inlne-block;margin-right: 15px;">
                                    <img src="' . $csDomain . 'images/facebook_icon.png" width="20" height="20" alt="Facebook strana" title="Facebook strana ' . $csName . '">
                                </a>';
}
if ($csGooglePlus != "") {
    $bodyUser .= '<a href="' . $csGooglePlus . '" title="Google Plus strana" style="text-decoration: none;display: inlne-block;margin-right: 15px;">
                                    <img src="' . $csDomain . 'images/google_plus_icon.png" width="20" height="20" alt="Google Plus strana" title="Google Plus strana ' . $csName . '">
                                </a>';
}
if ($csTwitter != "") {
    $bodyUser .= '<a href="' . $csTwitter . '" title="Twitter strana" style="text-decoration: none;display: inlne-block;margin-right: 15px;">
                                    <img src="' . $csDomain . 'images/twitter_icon.png" width="20" height="20" alt="Twitter strana" title="Twitter strana ' . $csName . '">
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
                                * Ukoliko želite da se odjavite sa naše newsletter liste kliknite <a href="' . $csDomain . 'odjava-sa-newsletter/promeniumd5" style="text-decoration: underline dotted;color: #000;">ovde</a>.
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>';
