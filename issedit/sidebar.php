<!-- Sidebar -->

<div id="sidebar">
    <?php
    switch ($module_name) {
        case "content":
        case "index":
            /*
              $contentTypeCollection = new Collection("content_types");
              $contentTypes = $contentTypeCollection->getCollection();

              echo "<h2>Content</h2>";
              ?>
              <div id="accordion">
              <h3><a href="/issedit/module_contenttypes/index.php" title="See all content types and manage them" class="tooltip">Content types</a></h3>
              <div>
              <ul>
              <?php
              foreach($contentTypes as $ct) {
              $f->printContentsForSidebar($ct);
              }
              ?>
              </ul>
              </div>
              </div>

              <!-- <h2>Content &amp; Categories</h2> -->
              <div id="accordion">
              <div>
              <ul>
              <?php /*
              foreach($contentTypes as $ct) {
              if($ct->category_type != 0) {
              ?>
              <li <?php if($module_name == "content" && $cid == $ct->id) echo "class='active'"; ?>>
              <a href="/issedit/module_content/index.php?cid=<?= $ct->id; ?>" class="tooltip" title="Manage <?= $ct->title; ?>"><?= $ct->title; ?></a>
              <?php $f->printCategoriesNavigation(0, $ct->id, 1, $parent_id); ?>
              </li>
              <?php
              }
              }
              ?>
              </ul>
              </div>
              </div>
              <?php */
            break;

        case "categories":
            /*
              $language = new View("languages", 1, "is_default");

              $content_typesCollection = new Collection("content_types");
              $content_types = $content_typesCollection->getCollection();

              echo "<h2>Categories</h2>";
              ?>
              <div id="accordion">
              <h3><a href="/issedit/module_categories/index.php" title="See all categories types and manage them" class="tooltip">Categories</a></h3>
              <div>
              <ul>
              <?php
              $categoriesCollection = new Collection("categories");
              foreach($content_types as $ct) {
              $categories = $categoriesCollection->getCollection("WHERE parent_id = 0 AND content_type_id =" . $ct->id . " AND lang = " . $language->id);
              if($categoriesCollection->resultCount>0){
              echo "<li>";
              ?>
              <li <?php if($module_name == "content" && $cid == $ct->id) echo "class='active'"; ?>>
              <a href="/issedit/module_content/index.php?cid=<?= $ct->id; ?>" class="tooltip" title="Manage <?= $ct->title; ?>"><?= $ct->title; ?></a>
              <?php
              echo "<ul>";
              foreach($categories as $category)
              $f->printCategoryRec($category, "");
              echo "</ul>";
              }
              echo "</li>";
              }
              ?>
              </ul>
              </div>
              </div>
              <?php */
            break;


        case "settings":
        case "dimensions":
        case "fields":
        case "langfile":
        case "languages":
        case "contenttypes":
        case "administrators":
            ?>
            <h2>Settings menu</h2>

            <div id="accordion">
                <div>
                    <h3><a href="#" title="See actions for module Settings" class="tooltip">Settings & Languages</a></h3>
                    <div>
                        <ul>
                            <li <?php if ($module_name == "settings") echo 'class="active"'; ?>><a href="/issedit/module_settings/index.php" class="tooltip" title="Manage Settings for your website">Settings</a></li>
                            <li <?php if ($module_name == "languages") echo 'class="active"'; ?>><a href="/issedit/module_languages/index.php" class="tooltip" title="Manage Languages on your website">Languages</a></li>
                            <li <?php if ($module_name == "langfile") echo 'class="active"'; ?>><a href="/issedit/module_langfile/index.php" class="tooltip" title="Manage Languages files on your website">Languages files</a></li>                                
                            <li <?php if ($module_name == "fields" && $table_name == "categories") echo 'class="active"'; ?>><a href="/issedit/module_fields/index.php?table_name=categories" class="tooltip" title="Manage category global custom fields">Category global fields</a></li>

                            <li <?php if ($module_name == "module_contenttypes" && $table_name == "module_contenttypes") echo 'class="active"'; ?>><a href="/issedit/module_contenttypes/index.php">Content types</a></li> 


                            <li <?php if ($module_name == "dimensions" && $table_name == "categories") echo 'class="active"'; ?>><a href="/issedit/module_dimensions/index.php?table_name=categories" class="tooltip" title="Manage categories dimension for uploading images">Categories dimension</a></li>
                            <?php /* ?>
                            <li <?php if ($module_name == "fields" && $table_name == "users") echo 'class="active"'; ?>><a href="/issedit/module_fields/index.php?table_name=users" class="tooltip" title="Manage users global custom fields">Users global fields</a></li>
                            <li <?php if ($module_name == "dimensions" && $table_name == "users") echo 'class="active"'; ?>><a href="/issedit/module_dimensions/index.php?table_name=users" class="tooltip" title="Manage users dimension for uploading images">Users dimension</a></li>
                            
                            <li <?php if ($table_name == "albums") echo 'class="active"'; ?>><a href="/issedit/module_dimensions/index.php?table_name=albums" class="tooltip" title="Manage albums dimension for uploading images">Albums dimension</a></li>
                             <?php */ ?>
                                <li <?php if ($module_name == "administrators") echo 'class="active"'; ?>><a href="/issedit/module_administrators/index.php" class="tooltip" title="Manage administrators and moderators">Administrators / Moderators</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <?php
            break;

        case "comments":
            $comments->printActionsSidebarComments($currentLanguage, $content_type);
            break;

        case "menus":
            ?>
            <h2>Menus at front</h2>

            <div id="accordion">
                <div>
                    <h3><a href="#" title="See actions for module Menus" class="tooltip">Menus</a></h3>
                    <div>

                        <ul>

                            <li style="border-bottom: 0px;"><a href="/issedit/module_menus/index.php" class="tooltip" title="See all menus">All Menus</a>
                                <ul>
                                    <?php
                                    $menusCollection = new Collection("menus");
                                    $menusSidebar = $menusCollection->getCollection("WHERE lang_id IN (SELECT id FROM languages WHERE is_default='1')");

                                    foreach ($menusSidebar as $menuSidebar) {
                                        ?>
                                        <li <?php if ($menuSidebar->resource_id == $menuResourceId) echo 'class="active"'; ?>>
                                            <a href="/issedit/module_menus/menu.php?rid=<?= $menuSidebar->resource_id; ?>" class="tooltip" title="View links for menu: <?= $menuSidebar->title; ?>"><?= $menuSidebar->title; ?></a>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <?php
            break;

        case "menu_items";
            ?>
            <h2>Links in menu</h2>

            <div id="accordion">
                <div>
                    <h3><a href="#" title="See actions for module Menus" class="tooltip">Links in menu</a></h3>
                    <div>
                        <ul>
                            <?php
                            $menuLinksCollection = new Collection("menus");
                            $menuItems = $menuLinksCollection->getCollection("WHERE resource_id = '$menuResourceId'");

                            foreach ($menuItems as $menuSingle) {
                                ?>
                                <li style="border-bottom: 0px;" <?php if ($menuSingle->id == $menuId) echo "class='active'"; ?>><a href="/issedit/module_menus/menu.php?rid=<?= $menuResourceId; ?>&menu_id=<?= $menuSingle->id; ?>" class="tooltip" title="View links for menu: <?= $menuSingle->title; ?>"><?= $menuSingle->title; ?></a></li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php
            break;

        case "html_blocks":
            ?>
            <h2>HTML Blocks menu</h2>

            <div id="accordion">
                <div>
                    <h3><a href="#" title="HTML Blocks menu" class="tooltip">HTML Blocks</a></h3>
                    <div>
                        <ul>
                            <li><a href="/issedit/module_html_blocks/index.php" class="tooltip" title="Manage HTML Blocks on your site">All HTML Blocks</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <?php
            break;

        case "newsletter":
            ?>
            <h2>Newsletter Menu</h2>

            <div id="accordion">
                <div>
                    <h3><a href="#" title="Newsletter Menu" class="tooltip">Newsletter</a></h3>
                    <div>
                        <ul>
                            <li><a href="/issedit/module_newsletter/index.php" class="tooltip" title="Manage Newsletter temapltes on your site">Newsletter Templates</a></li>
                            <li><a href="/issedit/module_newsletter/header.php" class="tooltip" title="Manage Newsletter header on your site">Newsletter Header</a></li>
                            <li><a href="/issedit/module_newsletter/footer.php" class="tooltip" title="Manage Newsletter footer on your site">Newsletter Footer</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <?php
            break;

        case "albums":
            //$albums->printActionsSidebar($currentLanguage, $album_id);
            ?> 
            <h2>Albums</h2>

            <div id="accordion">
                <div>
                    <h3><a href="#" title="Albums menu" class="tooltip">Albums</a></h3>
                    <div>
                        <ul>
                            <li <?php if ($selected == "gallery") echo 'class = "active"'; ?>><a href="/issedit/module_albums/add_gallery.php?selected=gallery" class="tooltip" title="Add gallery from zip file">Add gallery from ZIP file</a></li>
                            <li <?php if ($selected != "gallery") echo 'class = "active"'; ?>><a href="/issedit/module_albums/index.php" class="tooltip" title="See all albums on your website">All albums</a>
                                <?php
                                foreach ($albums as $album) {
                                    echo "<ul>";
                                    ?>
                                <li <?php if ($album->id == $album_id) echo 'class="active"'; ?>><a href="album.php?album_id=<?= $album->id; ?>"><?= $album->title; ?></a></li>
                                <?php
                                echo "</ul>";
                            }
                            ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <?php
            break;

        case "polls":
            $polls->printActionsSidebar($currentLanguage, $poll_id);
            break;

        case "users":
            ?>
            <h2>Users menu</h2>

            <div id="accordion">
                <div>
                    <h3><a href="#" title="Users menu" class="tooltip">Users</a></h3>
                    <div>
                        <ul>
                            <li <?php if ($sort == "") echo 'class="active"'; ?>><a href="/issedit/module_users/index.php" class="tooltip" title="Manage Users on your site">All Users</a>
                                <ul>
                                    <li <?php if ($sort == "approved") echo 'class="active"'; ?>><a href="/issedit/module_users/index.php?sort=approved" class="tooltip" title="Manage Users on your site">Approved users(<?= $num = $db->numRows("SELECT * FROM users WHERE status = 1") ?>)</a></li>
                                    <li <?php if ($sort == "unapproved") echo 'class="active"'; ?>><a href="/issedit/module_users/index.php?sort=unapproved" class="tooltip" title="Manage Users on your site">Not Approved Users(<?= $num = $db->numRows("SELECT * FROM users WHERE status = 0") ?>)</a></li>
                                    <li <?php if ($sort == "banned") echo 'class="active"'; ?>><a href="/issedit/module_users/index.php?sort=banned" class="tooltip" title="Manage Users on your site">Banned users(<?= $num = $db->numRows("SELECT * FROM users WHERE status = 2") ?>)</a></li>
                                    <li <?php if ($sort == "fb") echo 'class="active"'; ?>><a href="/issedit/module_users/index.php?sort=fb" class="tooltip" title="Manage Users on your site">Facebook users(<?= $num = $db->numRows("SELECT * FROM users WHERE fbuser = 1") ?>)</a></li>
                                    <li <?php if ($sort == "last3days") echo 'class="active"'; ?>><a href="/issedit/module_users/index.php?sort=last3days" class="tooltip" title="Manage Users on your site">Users in last 3 days(<?= $num = $db->numRows("SELECT * FROM users WHERE DATEDIFF(current_date, date_added ) <=3") ?>)</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <?php
            break;
        case "shopping_carts":
            ?>
            <h2>Shopping menu</h2>
            <div id="accordion">
                <div>
                    <h3><a href="#" title="Shopping menu" class="tooltip">Shopping</a></h3>
                    <div>
                        <ul>
                            <li class="active"><a href="/issedit/module_shopping/index.php" class="tooltip" title="Manage shoping carts on your site">Shopping carts(<?= $num = $db->numRows("SELECT * FROM shopping_carts") ?>)</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <?php
            break;
    }
    ?>

    <?php //$f->printStatistic($currentLanguage); ?>
    <br /><br />
</div>
<!-- End of Sidebar --> 