<?php
require("library/config.php");
$f->checkLogedAdmin();
$module_name = "index";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />		
        <title><?= ADMIN_TITLE; ?></title>
        <?php include("head.php"); ?>
    </head>
    <body>
        <!-- Container -->
        <div id="container">

            <?php include("header.php"); ?>

            <!-- Background wrapper -->
            <div id="bgwrap">
                <!-- Main Content -->
                <div id="content">
                    <div id="main">
                        <h1>Welcome, <span><?= $f->adminName(); ?></span> !</h1>
                        <p>Manage content on web site</p>
                        <div class="">
                            <!-- Big buttons -->
                            <ul class="dash">
                                <?php
                                $contentTypes = new Collection("content_types");
                                $contentTypeCollection = $contentTypes->getCollection("WHERE `id` != '0' AND id != '81' ORDER BY ordering ASC");

                                foreach ($contentTypeCollection as $ct) {
                                    ?>
                                    <li>
                                        <a href="module_content/index.php?cid=<?= $ct->id; ?>" title="Mange <?= $ct->title; ?> on website" class="tooltip">
                                            <img src="/issedit/resources/assets/icons/7_48x48.png" alt="" />
                                            <span><?= $ct->title; ?></span>
                                        </a>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                            <!-- End of Big buttons -->
                        </div>
                        <hr />
                    </div>
                </div>
                <!-- End of Main Content -->

                <?php include("sidebar.php"); ?>

            </div>
            <!-- End of bgwrap -->

        </div>
        <!-- End of Container -->

        <?php include("footer.php"); ?>

    </body>
</html>