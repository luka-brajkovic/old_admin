<?php
require("../library/config.php");
$f->checkLogedAdmin("module");

$menuResourceId = $f->getValue("rid");
$menuId = $f->getValue("menu_id");
$module_name = "menu_items";

if ($menuId == "") {
    $menuViewColl = new Collection("menus");
    $menusView = $menuViewColl->getCollection("WHERE resource_id = '$menuResourceId' AND lang_id IN (SELECT id FROM languages WHERE is_default='1')");
    $menuView = $menusView[0];
    $menuId = $menuView->id;
} else {
    $menuView = new View("menus", $menuId);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />		
        <title><?= ADMIN_TITLE; ?></title>
        <?php include("../head.php"); ?>
        <script type="text/javascript" src="/admin/resources/js/jquery.ui.nestedsortable.js"></script>
        <style>
            .placeholder {
                background-color: #cfcfcf;
            }

            .ui-nestedSortable-error {
                background:#fbe3e4;
                color:#8a1f11;
            }

            ol {
                margin: 0;
                padding: 0;
                padding-left: 30px;
            }

            ol.sortable, ol.sortable ol {
                margin: 0 0 0 25px;
                padding: 0;
                list-style-type: none;
            }

            ol.sortable {
                margin: 4em 0;
            }

            .sortable li {
                margin: 7px 0 0 0;
                padding: 0;
            }

            .sortable li div  {
                border: 1px solid black;
                padding: 3px;
                margin: 0;
                cursor: move;
            }

        </style>
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
                        <h1>Link from menu: <?= $menuView->title; ?></h1>

                        <?php if ($db->numRows("SELECT * FROM menu_items WHERE menu_id = '$menuId' AND parent_id = '0'") > 0) { ?>
                            <section id="demo">
                                <ol class="sortable ui-sortable">
                                    <?php
                                    $linksCollection = new Collection("menu_items");
                                    $links = $linksCollection->getCollection("WHERE menu_id = '$menuId' AND parent_id = '0'", "ORDER BY ordering");
                                    foreach ($links as $link) {
                                        ?>
                                        <li id="list_<?= $link->id; ?>">
                                            <div> 
                                                <span style="width: auto; float: left; font-size: 12px; padding-top: 4px;"><?= $link->title; ?> | <?= $link->url; ?></span>
                                                <a style="float: right;" class="button_table tooltip" title="Delete" onclick="return confirm('Are you sure?');" href="work.php?action=delete_menu_link&link_id=<?= $link->id; ?>">
                                                    <span class="ui-icon ui-icon-trash"></span>
                                                </a>
                                                <a style="float: right;" class="button_table tooltip" title="Edit" href="edit_item.php?item_id=<?= $link->id; ?>">
                                                    <span class="ui-icon ui-icon-pencil"></span>
                                                </a>                                                         
                                                <br clear="all" />
                                            </div>
                                            <?php
                                            if ($db->numRows("SELECT * FROM menu_items WHERE parent_id = '$link->id'") > 0) {
                                                $f->printMenuLinksListing($menuId, $link->id);
                                            }
                                            ?>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                </ol>
                            </section>


                            <script>
                                $(document).ready(function () {

                                    $('ol.sortable').nestedSortable({
                                        disableNesting: 'no-nest',
                                        forcePlaceholderSize: true,
                                        handle: 'div',
                                        helper: 'clone',
                                        items: 'li',
                                        maxLevels: 10,
                                        opacity: .6,
                                        placeholder: 'placeholder',
                                        revert: 250,
                                        tabSize: 25,
                                        tolerance: 'pointer',
                                        toleranceElement: '> div',
                                        update: function (event, ui) {
                                            serialized = $('ol.sortable').nestedSortable('serialize');
                                            $.ajax({
                                                type: "POST",
                                                url: "work.php",
                                                data: serialized + "&menu_id=<?= $menuId; ?>&action=order_links",
                                                success: function (msg) {

                                                }
                                            });
                                        }
                                    });

                                });

                            </script>
                        <?php } else { ?>
                            <p>There are no links for this menu.</p>
                        <?php } ?>
                        <hr />

                        <form method="POST" id="add_custom_link" name="add_custom_link" action="work.php">
                            <fieldset>
                                <legend>Add new custom link for menu: <?= $menuView->title; ?></legend>
                                <p>
                                    <label for="title">Title *</label>
                                    <input type="text" class="lf" name="title" id="title" required="required"/>
                                </p>

                                <p>
                                    <label for="title">URL</label>
                                    <input type="text" class="lf" name="url" id="url" />
                                </p>

                                <p>
                                    <label for="open_type">Open in</label>
                                    <select name="open_type" id="open_type">
                                        <option value="1">Same window</option>
                                        <option value="2">New tab</option>
                                    </select>
                                </p>

                                <input type="hidden" name="action" value="add_link_custom" />
                                <input type="hidden" name="menu_id" value="<?= $menuId; ?>" />
                                <input type="hidden" name="type" value="1" />
                                <input type="hidden" name="resource_id" value="0" />
                                <input type="hidden" name="parent_id" value="0" />
                                <input type="submit" class="button" value="Save" /> 
                            </fieldset>
                        </form>

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