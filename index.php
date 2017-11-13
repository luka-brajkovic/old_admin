<?php
include_once ("library/config.php");

$naslovna = true;

$urlAKTIVE = "/";

$htmlTagAddOG = 'prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# business: http://ogp.me/ns/business#"';
?>

<?php include_once ("head.php"); ?>

</head>
<body>
    <?php
    if ($_POST["okinuto"] != "") {
        $email = $_POST["email"];
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE) {
            $salje = $db->getValue("text", "_content_html_blocks", "resource_id", "8");
        } else {
            $salje = $db->getValue("text", "_content_html_blocks", "resource_id", "9");
            $bodyUser = $db->getValue("text", "_content_html_blocks", "resource_id", "10");
            $f->sendEmail($csEmail, "$csName", $email, "Uspešna prijava na newsletter", $bodyUser, $currentLanguage);

            $postojiEmail = $db->getValue("resource_id", "_content_newsletter", title, $email);

            if ($postojiEmail == "") {
                $paketNewsletter = new View("resources");
                $paketNewsletter->table_name = "_content_newsletter";
                $paketNewsletter->Save();

                $newEmail = new View("_content_newsletter");
                $newEmail->resource_id = $paketNewsletter->id;
                $newEmail->title = $email;
                $newEmail->url = $f->generateUrlFromText($email);
                $newEmail->system_date = date("Y-m-d H:i:s");
                $newEmail->lang = 1;
                $newEmail->status = 1;
                $newEmail->Save();
            } else {
                $maili = new Collection("_content_newsletter");
                $mailArr = $maili->getCollection("WHERE  title = '$email'");
                $newEmail = $mailArr[0];

                $newEmail->status = 1;
                $newEmail->Save();
            }
        }
        ?> 

        <div id="popup">
            <div id='popupInner'>
                <?= $salje; ?>
                <a class="more" onclick="closePopup();" href="javascript:">Zatvori</a>
            </div>
        </div>
        <?php
    }
    include_once ("header.php");
    include_once ("includes/index-content.php");
    include_once ("footer.php");
    ?>
</body>
</html>