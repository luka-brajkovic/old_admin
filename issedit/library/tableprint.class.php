<?php

class Tableprint extends Functions {

    /**
     * Prints content types list table
     *
     * @param string $table Table name
     */
    function printContentTypesTable($table) {

        $ct = new Collection($table);
        $ctList = $ct->getCollection();

        if ($ct->resultCount > 0) {
            ?>
            <table cellspacing="0" cellpadding="0" border="0" class="fullwidth">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Tablename</th>
                        <th>URL</th>
                        <th>Comments</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($ctList as $key => $ctSingle) {
                        ?>
                        <tr>
                            <td><?= $ctSingle->id; ?></td>
                            <td><?= $ctSingle->title; ?></td>
                            <td><?= $ctSingle->table_name; ?></td>
                            <td><?= $ctSingle->url; ?></td>
                            <td>
                                <?php
                                if ($ctSingle->comments == 0) {
                                    echo "No comments";
                                } else {
                                    echo "Show comments";
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if ($ctSingle->category_type == 0) {
                                    echo "No category";
                                } else if ($ctSingle->category_type == 1) {
                                    echo "Single category";
                                } else if ($ctSingle->category_type == 2) {
                                    echo "Multicategory";
                                }
                                ?>
                            </td>
                            <td>
                                <a title="View fields for Content type: <?= $ctSingle->title; ?>" class="button_table tooltip" href="fields.php?id=<?= $ctSingle->id; ?>">
                                    <span class="ui-icon ui-icon-search"></span>
                                </a>
                                <a title="Edit Content type: <?= $ctSingle->title; ?>" class="button_table tooltip" href="edit.php?id=<?= $ctSingle->id; ?>">
                                    <span class="ui-icon ui-icon-pencil"></span>
                                </a>
                                <a title="Delete Content type: <?= $ctSingle->title; ?>" class="button_table tooltip" href="work.php?action=delete_ct&id=<?= $ctSingle->id; ?>" onclick="return confirm('Are you sure you want delete this content type? Deleting this contnet type all data will be deleted and lost.');">
                                    <span class="ui-icon ui-icon-trash"></span>
                                </a>
                                <a title="Dimensions for Content type: <?= $ctSingle->title; ?>" class="button_table tooltip" href="dimensions.php?id=<?= $ctSingle->id; ?>">
                                    <span class="ui-icon ui-icon-arrow-4-diag"></span>
                                </a>

                                <?php if ($ctSingle->category_type != 0) { ?>
                                    <a title="Category fields for Content type: <?= $ctSingle->title; ?>" class="button_table tooltip" href="category_fields.php?cid=<?= $ctSingle->id; ?>">
                                        <span class="ui-icon ui-icon-tag"></span>
                                    </a>
                                <?php } ?>

                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php
        } else {
            ?>
            <p>No listed content types.</p>
            <?php
        }
    }

    function printContentTypeFieldsTable($cid) {

        $ctFields = new Collection("content_type_fields");
        $ctFieldsList = $ctFields->getCollection("WHERE content_type_id = '$cid' order by ordering");

        if ($ctFields->resultCount > 0) {
            ?>
            <table cellspacing="0" cellpadding="0" border="0" class="fullwidth ct_fields_table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Column name</th>
                        <th>Type</th>
                        <th>Default value</th>
                        <th>Show in list</th>
                        <th>Searchable</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($ctFieldsList as $key => $fieldSingle) {
                        ?>
                        <tr id="item_<?= $fieldSingle->id; ?>">
                            <td><?= $fieldSingle->id; ?></td>
                            <td><?= $fieldSingle->title; ?></td>
                            <td><?= $fieldSingle->column_name; ?></td>
                            <td><?= $fieldSingle->field_type; ?></td>
                            <td><?= $fieldSingle->default_value; ?></td>
                            <td>
                                <?php
                                if ($fieldSingle->show_in_list == 0) {
                                    echo "No";
                                } else {
                                    echo "Yes";
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if ($fieldSingle->searchable == 0) {
                                    echo "No";
                                } else {
                                    echo "Yes";
                                }
                                ?>
                            </td>
                            <td>
                                <a title="Edit field: <?= $fieldSingle->title; ?>" class="button_table tooltip" href="edit_field.php?id=<?= $fieldSingle->id; ?>">
                                    <span class="ui-icon ui-icon-pencil"></span>
                                </a>
                                <a title="Delete field: <?= $fieldSingle->title; ?>" class="button_table tooltip" href="work.php?action=delete_ct_field&id=<?= $fieldSingle->id; ?>" onclick="return confirm('Are you sure you want delete this field? Deleting this field all data will be deleted and lost.');">
                                    <span class="ui-icon ui-icon-trash"></span>
                                </a>

                                <a title="Order field: <?= $fieldSingle->title; ?>" class="button_table tooltip handle" href="javascript:void(0);">
                                    <span class="ui-icon ui-icon-arrow-4"></span>
                                </a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php
        } else {
            ?>
            <p>No listed content types.</p>
            <?php
        }
    }

    function printContentTypeCategoryFieldsTable($cid) {

        $ctFields = new Collection("categories_fields");
        $ctFieldsList = $ctFields->getCollection("WHERE content_type_id = '$cid'");

        if ($ctFields->resultCount > 0) {
            ?>
            <table cellspacing="0" cellpadding="0" border="0" class="fullwidth">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Column name</th>
                        <th>Type</th>
                        <th>Default value</th>
                        <th>Show in list</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($ctFieldsList as $key => $fieldSingle) {
                        ?>
                        <tr>
                            <td><?= $fieldSingle->id; ?></td>
                            <td><?= $fieldSingle->title; ?></td>
                            <td><?= $fieldSingle->column_name; ?></td>
                            <td><?= $fieldSingle->field_type; ?></td>
                            <td><?= $fieldSingle->default_value; ?></td>
                            <td>
                                <?php
                                if ($fieldSingle->show_in_list == 0) {
                                    echo "No";
                                } else {
                                    echo "Yes";
                                }
                                ?>
                            </td>
                            <td>
                                <a title="Edit field: <?= $fieldSingle->title; ?>" class="button_table tooltip" href="edit_cat_field.php?id=<?= $fieldSingle->id; ?>">
                                    <span class="ui-icon ui-icon-pencil"></span>
                                </a>
                                <a title="Delete field: <?= $fieldSingle->title; ?>" class="button_table tooltip" href="work.php?action=delete_ct_cat_field&id=<?= $fieldSingle->id; ?>" onclick="return confirm('Are you sure you want delete this field? Deleting this field all data will be deleted and lost.');">
                                    <span class="ui-icon ui-icon-trash"></span>
                                </a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php
        } else {
            ?>
            <p>No listed content types.</p>
            <?php
        }
    }

    function printCategoryFieldsTable() {
        $ctFields = new Collection("categories_fields");
        $ctFieldsList = $ctFields->getCollection();

        if ($ctFields->resultCount > 0) {
            ?>
            <table cellspacing="0" cellpadding="0" border="0" class="fullwidth">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Column name</th>
                        <th>Type</th>
                        <th>Default value</th>
                        <th>Show in list</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($ctFieldsList as $key => $fieldSingle) {
                        ?>
                        <tr>
                            <td><?= $fieldSingle->id; ?></td>
                            <td><?= $fieldSingle->title; ?></td>
                            <td><?= $fieldSingle->column_name; ?></td>
                            <td><?= $fieldSingle->field_type; ?></td>
                            <td><?= $fieldSingle->default_value; ?></td>
                            <td>
                                <?php
                                if ($fieldSingle->show_in_list == 0) {
                                    echo "No";
                                } else {
                                    echo "Yes";
                                }
                                ?>
                            </td>
                            <td>
                                <a title="Edit field: <?= $fieldSingle->title; ?>" class="button_table tooltip" href="edit_cat_field.php?id=<?= $fieldSingle->id; ?>">
                                    <span class="ui-icon ui-icon-pencil"></span>
                                </a>
                                <a title="Delete field: <?= $fieldSingle->title; ?>" class="button_table tooltip" href="work.php?action=delete_ct_cat_field&id=<?= $fieldSingle->id; ?>" onclick="return confirm('Are you sure you want delete this field? Deleting this field all data will be deleted and lost.');">
                                    <span class="ui-icon ui-icon-trash"></span>
                                </a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php
        } else {
            ?>
            <p>No listed category fields.</p>
            <?php
        }
    }

    function printFieldsTable($table_name) {
        $ctFields = new Collection("fields");
        $ctFieldsList = $ctFields->getCollection("WHERE table_name = '$table_name'");

        if ($ctFields->resultCount > 0) {
            ?>
            <table cellspacing="0" cellpadding="0" border="0" class="fullwidth">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Column name</th>
                        <th>Type</th>
                        <th>Default value</th>
                        <th>Show in list</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($ctFieldsList as $key => $fieldSingle) {
                        ?>
                        <tr>
                            <td><?= $fieldSingle->id; ?></td>
                            <td><?= $fieldSingle->title; ?></td>
                            <td><?= $fieldSingle->column_name; ?></td>
                            <td><?= $fieldSingle->field_type; ?></td>
                            <td><?= $fieldSingle->default_value; ?></td>
                            <td>
                                <?php
                                if ($fieldSingle->show_in_list == 0) {
                                    echo "No";
                                } else {
                                    echo "Yes";
                                }
                                ?>
                            </td>
                            <td>
                                <a title="Edit field: <?= $fieldSingle->title; ?>" class="button_table tooltip" href="edit_field.php?id=<?= $fieldSingle->id; ?>">
                                    <span class="ui-icon ui-icon-pencil"></span>
                                </a>
                                <a title="Delete field: <?= $fieldSingle->title; ?>" class="button_table tooltip" href="work.php?action=delete_field&id=<?= $fieldSingle->id; ?>" onclick="return confirm('Are you sure you want delete this field? Deleting this field all data will be deleted and lost.');">
                                    <span class="ui-icon ui-icon-trash"></span>
                                </a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php
        } else {
            ?>
            <p>No listed fields.</p>
            <?php
        }
    }

    function printLangTable() {

        $languages = new Collection("languages");
        $allLaguages = $languages->getCollection();
        if ($languages->resultCount > 0) {
            ?>
            <table cellspacing="0" cellpadding="0" border="0" class="fullwidth categories" id="<?= $parent_id; ?>" type="<?= $type; ?>">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Code</th>
                        <th>Is Default</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td></td></tr>
                    <?php
                    foreach ($allLaguages as $key => $language) {
                        if ($key % 2)
                            $class = "";
                        else
                            $class = "odd";
                        ?>
                        <tr class="<?= $class; ?>">
                            <td><?= $language->id; ?></td>
                            <td><?= $language->title; ?></td>
                            <td><?= $language->code; ?></td>
                            <td><?php
                                if ($language->is_default == 1)
                                    echo "Yes";
                                else
                                    echo "No";
                                ?></td>
                            <td>
                                <? if ($data['is_default'] != 1) { ?>
                                <a title="Delete language: <?= $language->title; ?>" class="button_table tooltip" href="work.php?action=delete&id=<?= $language->id; ?>" onclick="return confirm('Are you sure?');">
                                    <span class="ui-icon ui-icon-trash"></span>
                                </a>
                            <?php } ?>

                            <a title="Edit language: <?= $language->title; ?>" class="button_table tooltip" href="edit.php?id=<?= $language->id; ?>">
                                <span class="ui-icon ui-icon-pencil"></span>
                            </a>

                        </td>
                    </tr>
                    <?php
                }
                ?>
                <tr><td></td></tr>
            </tbody>
        </table>
        <?php
        } else {
            ?>
            <p>No listed languages.</p>
            <?php
        }
    }

    function printContentTypeDimensionsTable($cid) {
        $dimensions = new Collection("content_type_dimensions");
        $dimensionsList = $dimensions->getCollection("WHERE content_type_id = '$cid'");

        if ($dimensions->resultCount > 0) {
            ?>
            <table cellspacing="0" cellpadding="0" border="0" class="fullwidth">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>URL</th>
                        <th>Width X Height</th>
                        <th>Crop Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($dimensionsList as $key => $dimension) {
                        ?>
                        <tr>
                            <td><?= $dimension->id; ?></td>
                            <td><?= $dimension->title; ?></td>
                            <td><?= $dimension->url; ?></td>
                            <td><?= $dimension->width; ?>X<?= $dimension->height; ?></td>
                            <td>
                                <?php
                                if ($dimension->crop_resize == 1) {
                                    echo "Crop";
                                } else {
                                    echo "Resize";
                                }
                                ?>
                            </td>
                            <td>
                                <a title="Edit dimension: <?= $dimension->title; ?>" class="button_table tooltip" href="edit_dimension.php?id=<?= $dimension->id; ?>">
                                    <span class="ui-icon ui-icon-pencil"></span>
                                </a>
                                <a title="Delete dimension: <?= $dimension->title; ?>" class="button_table tooltip" href="work.php?action=delete_ct_dimension&id=<?= $dimension->id; ?>" onclick="return confirm('Are you sure you want delete this dimension? Deleting this dimension all images for this dimension will be deleted and lost.');">
                                    <span class="ui-icon ui-icon-trash"></span>
                                </a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php
        } else {
            ?>
            <p>No listed dimensions.</p>
            <?php
        }
    }

    function printDimensionsTable($table_name) {
        $dimensions = new Collection("dimensions");
        $dimensionsList = $dimensions->getCollection("WHERE table_name = '$table_name'");

        if ($dimensions->resultCount > 0) {
            ?>
            <table cellspacing="0" cellpadding="0" border="0" class="fullwidth">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>URL</th>
                        <th>Width X Height</th>
                        <th>Crop Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($dimensionsList as $key => $dimension) {
                        ?>
                        <tr>
                            <td><?= $dimension->id; ?></td>
                            <td><?= $dimension->title; ?></td>
                            <td><?= $dimension->url; ?></td>
                            <td><?= $dimension->width; ?>X<?= $dimension->height; ?></td>
                            <td>
                                <?php
                                if ($dimension->crop_resize == 1) {
                                    echo "Crop";
                                } else {
                                    echo "Resize";
                                }
                                ?>
                            </td>
                            <td>
                                <a title="Edit dimension: <?= $dimension->title; ?>" class="button_table tooltip" href="edit_dimension.php?id=<?= $dimension->id; ?>">
                                    <span class="ui-icon ui-icon-pencil"></span>
                                </a>
                                <?php if ($dimension->table_name != "albums") { ?>
                                    <a title="Delete dimension: <?= $dimension->title; ?>" class="button_table tooltip" href="work.php?action=delete_dimension&id=<?= $dimension->id; ?>" onclick="return confirm('Are you sure you want delete this dimension? Deleting this dimension all images for this dimension will be deleted and lost.');">
                                        <span class="ui-icon ui-icon-trash"></span>
                                    </a>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php
        } else {
            ?>
            <p>No listed dimensions.</p>
            <?php
        }
    }

    function printCategoriesTable($cid, $parent_id) {

        $defLanguage = new View("languages", "1", "is_default");

        $categoryCollection = new Collection("categories");
        $categories = $categoryCollection->getCollection("WHERE content_type_id = '$cid' AND parent_id = '$parent_id' AND lang='$defLanguage->id'", "ORDER BY ordering");

        //Get categories custom fields for content type
        $categoriesFields = new Collection("categories_fields");
        $categoriesFieldsCollection = $categoriesFields->getCollection("WHERE content_type_id = '$cid' AND show_in_list = '1'");

        //Get categories global fields
        $categoriesGlobalFields = new Collection("fields");
        $categoriesGlobalFieldsCollection = $categoriesGlobalFields->getCollection("WHERE table_name = 'categories' AND show_in_list = '1'");

        $groupsCol = new Collection("_content_grupe");

        if ($categoryCollection->resultCount > 0) {
            ?>
            <table cellspacing="0" cellpadding="0" border="0" class="fullwidth category_table">
                <thead>
                    <tr>
                        <th>rID</th>
                        <th>Title</th>
                        <th>URL</th>

                        <?php
                        foreach ($categoriesFieldsCollection as $ctField) {
                            echo '<th>' . $ctField->title . '</th>';
                        }
                        ?>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($categories as $key => $category) {
                        $contentCount = Database::numRows("SELECT * FROM categories_content WHERE category_resource_id = '$category->resource_id'");
                        ?>
                        <tr id="item_<?= $category->resource_id; ?>" <?php if ($category->status == 2) echo 'style="background:#ffe5eb"'; ?>>
                            <td><?= $category->resource_id; ?></td>
                            <td><?= $category->title; ?></td>
                            <td><?= $category->url; ?></td>

                            <?php
                            foreach ($categoriesFieldsCollection as $ctField) {
                                $column_name = $ctField->column_name;
                                echo '<td>' . $category->$column_name . '</td>';
                            }
                            ?>
                            <td>
                                <a title="Edit category: <?= $category->title; ?>" class="button_table tooltip" href="edit_category.php?id=<?= $category->resource_id; ?>">
                                    <span class="ui-icon ui-icon-pencil"></span>
                                </a>
                                <a title="Copy category: <?= $category->title; ?>" class="button_table tooltip" href="work.php?action=copy_category&resource_id=<?= $category->resource_id; ?>">
                                    <span class="ui-icon ui-icon-pencil"></span>
                                </a>
                                <?php
                                $childs = $categoryCollection->getCollection("WHERE parent_id = $category->resource_id");
                                if ($categoryCollection->resultCount == 0 && $contentCount == 0) {
                                    ?>
                                    <a title="Delete category: <?= $category->title; ?>" class="button_table tooltip" href="work.php?action=delete_category&id=<?= $category->id; ?>" onclick="return confirm('Are you sure you want delete this category?');">
                                        <span class="ui-icon ui-icon-trash"></span>
                                    </a>
                                    <?php
                                }
                                ?>
                                <a title="Order category: <?= $category->title; ?>" class="button_table tooltip handle" href="javascript:void(0);">
                                    <span class="ui-icon ui-icon-arrow-4"></span>
                                </a>

                                <?php if ($contentCount > 0) { ?>
                                    <a title="View and order content for category: <?= $category->title; ?>" class="button_table tooltip handle" href="/issedit/module_content/index.php?cid=<?= $category->content_type_id; ?>&category_id=<?= $category->resource_id; ?>">
                                        <span class="ui-icon ui-icon-info"></span>
                                    </a>
                                <?php } else { ?>
                                    <a title="Branch category: <?= $category->title; ?>" class="button_table tooltip" href="index.php?parent_id=<?= $category->resource_id; ?>&cid=<?= $category->content_type_id; ?>">
                                        <span class="ui-icon ui-icon-search"></span>
                                    </a>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php
        } else {
            ?>
            <p>No listed categories. Manage content <a href="../module_content/index.php?cid=<?= $cid; ?>" >here</a>.</p>
            <?php
        }
    }

    function printNewsletterTable() {
        $uslugeCol = new Collection("_content_usluge");
        $uslugeArr = $uslugeCol->getCollection("WHERE status = 1");
        $newsTemplate = new View("newsletter_template", 1);
        $newsRids = $newsTemplate->body;
        ?>
        <table cellspacing="0" cellpadding="0" border="0" class="fullwidth c_table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Cena</th>
                    <th>Rezervisano</th>
                    <th>Kupljeno</th>
                    <th>Obeleži</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($uslugeArr as $usluga) {
                    ?>
                    <tr id="item_<?= $usluga->resource_id; ?>" onclick="checkThis('<?= $usluga->resource_id; ?>');" style="background:<?= (strpos($newsRids, $usluga->resource_id) !== false) ? '#57c5c7' : '#d6d8d8'; ?>">
                        <td><?= $usluga->resource_id; ?></td>
                        <td><?= $usluga->title; ?></td>
                        <td><?= $usluga->nova_cena; ?> din</td>
                        <td><?= $usluga->stvarno_rezervisano; ?></td>
                        <td><?= $usluga->stvarno_kupljeno; ?></td>
                        <td><input type="checkbox" name="newsrids[]" <?= (strpos($newsRids, $usluga->resource_id) !== false) ? "checked='checked'" : ""; ?> value="<?= $usluga->resource_id; ?>" /></td>
                        </td>	
                        <?php
                    }
                    ?>
            </tbody>
        </table>
        <?php
    }

    function printContentTable($cid, $wholeQuery, $tableCustomFields, $start_from, $img) {

        $contentType = new View("content_types", $cid);
        $defLanguage = new View("languages", 1, "is_default");

        if (!empty($img)) {
            $dimensions = new Collection("content_type_dimensions");
            $dimsArr = $dimensions->getCollection("WHERE content_type_id = $cid AND url NOT LIKE 'g%' ORDER BY LENGTH(url),url");
            $dimData = $dimsArr[0];
            $dimUrlLit = $dimData->url;
            $dimDataBig = $dimsArr[count($dimsArr) - 1];
            $dimUrlBig = $dimDataBig->url;

            $dimDatasecund = $dimsArr[2];
            $dimUrlLitSecund = $dimDatasecund->url;
        }



        $contentCollection = new Collection($contentType->table_name);
        /*
          echo $wholeQuery . "<br>";
         */
        $items = mysql_query($wholeQuery);
        $rbr = $start_from;
        if (mysql_num_rows($items) > 0) {
            ?>
            <script type="text/javascript">

                /* FJA EDITABLE TITLE */

                $(document).on("dblclick", ".editable_title strong", function () {
                    var cid = $(this).parent().data("cid");
                    var rid = $(this).parent().data("rid");
                    var currentValue = $(this).html();
                    var forValue = "";
                    $(this).parent().html("<textarea>" + currentValue + "</textarea><a href='javascript:void(0);'>OK</a>");
                });
                $(document).on("click", "td.editable_title a", function () {
                    $(this).html("Saving...");
                    var parentObj = $(this).parent();
                    var cid = $(this).parent().data("cid");
                    var rid = $(this).parent().data("rid");
                    var currentValue = $(this).parent().find("textarea").val();

                    $.ajax({
                        method: "post",
                        url: "work.php",
                        data: {action: "save_title", cid: cid, rid: rid, vrednost: currentValue}
                    }).done(function (msg) {
                        var result = $.trim(msg);
                        console.log = result;
                        parentObj.html("<strong>" + result + "</strong>");
                    });
                });



                /* FJA ZA AKTIVAN NEAKTIVAN  */

                $(document).on("dblclick", ".editable_select strong", function () {
                    var cid = $(this).parent().data("cid");
                    var rid = $(this).parent().data("rid");
                    var currentValue = $(this).html();
                    var forValue = "";
                    if (currentValue == "aktivan") {
                        forValue = 1;
                        $(this).parent().html("<select ><option selected value='1'>Active</option><option  value='2'>Inactive</option></select><a href='javascript:void(0);'>OK</a>");
                    } else {
                        forValue = 2;
                        $(this).parent().html("<select ><option value='1'>Active</option><option selected value='2'>Inactive</option></select><a href='javascript:void(0);'>OK</a>");
                    }
                });

                $(document).on("click", "td.editable_select a", function () {
                    $(this).html("Saving...");
                    var parentObj = $(this).parent();
                    var grandFObj = $(this).parent().parent();
                    var cid = $(this).parent().data("cid");
                    var rid = $(this).parent().data("rid");
                    var currentValue = $(this).parent().find("select").val();

                    $.ajax({
                        method: "post",
                        url: "work.php",
                        data: {action: "save_status", cid: cid, rid: rid, vrednost: currentValue}
                    }).done(function (msg) {
                        var result = $.trim(msg);
                        result = result.replace(" ", "");
                        console.log = result;
                        if (result === "aktivan") {
                            grandFObj.css("background", "");
                            parentObj.html("<strong style='color:green'>" + result + "</strong>");
                        } else {
                            grandFObj.css("background", "#ffe5eb");
                            parentObj.html("<strong style='color:red'>" + result + "</strong>");
                        }

                    });
                });


                /* FJA ZA ORDERING CUSTOM  */

                $(document).on("dblclick", ".editable_ordering strong", function () {
                    var cid = $(this).parent().data("cid");
                    var rid = $(this).parent().data("rid");
                    var currentValue = $(this).html();
                    var forValue = "";
                    $(this).parent().html("<input value='" + currentValue + "' /><a href='javascript:void(0);'>OK</a>");
                });

                $(document).on("click", "td.editable_ordering a", function () {
                    $(this).html("Saving...");
                    var parentObj = $(this).parent();
                    var grandFObj = $(this).parent().parent();
                    var cid = $(this).parent().data("cid");

                    var keys = [];

                    var rid = '';
                    var ordering = '';
                    var valueToPut = '';

                    var object = $(".editable_ordering");
                    object.each(function () {
                        rid = $(this).data("rid");
                        ordering = "";
                        if ($(this).find("input").length > 0) {
                            ordering = $(this).find("input").val();
                        } else {
                            ordering = $(this).find("strong").html();
                        }
                        valueToPut = rid + "__" + ordering;
                        keys.push(valueToPut);
                    });
                    $.ajax({
                        method: "post",
                        url: "work.php",
                        data: {action: "save_ordering", cid: cid, ordering: keys}
                    }).done(function (msg) {

                        window.location.href = window.location.href;
                    });

                });



            </script>    
            <table cellspacing="0" cellpadding="0" border="0" class="fullwidth c_table">
                <thead>
                    <tr>
                        <th><input type='checkbox' id='check_all' name='check_all' /></th>
                        <th>RBR</th>
                        <?php
                        if (!empty($img)) {
                            echo "<th>Image</th>";
                        }
                        ?>
                        <th>ID</th>
                        <th>Title</th>
                        <?php if ($cid == 72) { ?>
                            <th>Brand</th>
                        <?php } ?>
                        <?php if ($contentType->category_type != 0) { ?>
                            <th>Category</th>
                        <?php } ?>
                        <?php
                        foreach ($tableCustomFields as $itemFld => $tbl_name) {

                            echo "<th>$itemFld</th>";
                        }
                        if ($cid == 72 || $cid == 80 || $cid == 68 || $cid == 73) {
                            ?>
                            <th>Views</th>
                        <?php } ?>
                        <th>Status</th>
                        <th>Ordering</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    while ($item = mysql_fetch_object($items)) {
                        ?>
                        <tr id="item_<?= $item->resource_id; ?>" <?php if ($item->status == 2) echo 'style="background:#ffe5eb"'; ?>>
                            <td><input type='checkbox' value="<?= $item->resource_id; ?>" name='check_content[]' id='check_<?= $item->resource_id; ?>' /></td>
                            <td><?= ++$rbr . ". "; ?></td>
                            <?php
                            if (!empty($img)) {
                                $stavka = $img;
                                if (is_file("../../uploads/uploaded_pictures/" . $contentType->table_name . "/" . $dimUrlLit . "/" . $item->$stavka)) {
                                    ?>
                                    <td>
                                        <a style="width:100%; float:none; margin-bottom: 0; display: block; margin:0 auto;" class="fancybox" rel='<?= $item->resource_id; ?>' href='../../uploads/uploaded_pictures/<?= $contentType->table_name; ?>/<?= $dimUrlBig; ?>/<?= $item->$img; ?>'>
                                            <img style="height:40px; width:auto;" src='../../uploads/uploaded_pictures/<?= $contentType->table_name; ?>/<?= $dimUrlLit; ?>/<?= $item->$stavka; ?>' />
                                        </a>
                                    </td>
                                    <?php
                                } else if ("../../uploads/uploaded_pictures/" . $contentType->table_name . "/" . $dimUrlLitSecund . "/" . $item->$stavka) {
                                    ?>
                                    <td>
                                        <a style="width:100%; float:none; margin-bottom: 0; display: block; margin:0 auto;" class="fancybox" rel='<?= $item->resource_id; ?>' href='../../uploads/uploaded_pictures/<?= $contentType->table_name; ?>/<?= $dimUrlBig; ?>/<?= $item->$img; ?>'>
                                            <img style="height:40px; width:auto;" src='../../uploads/uploaded_pictures/<?= $contentType->table_name; ?>/<?= $dimUrlLitSecund; ?>/<?= $item->$stavka; ?>' />
                                        </a>
                                    </td>
                                    <?php
                                } else {
                                    echo "<td style='height:40px;'>no image</td>";
                                }
                            }
                            ?>
                            <td><?= $item->resource_id; ?></td>
                            <td class='editable_title' data-rid='<?= $item->resource_id; ?>' data-cid='<?= $cid; ?>' >
                                <?= $item->title; ?>
                            </td>
                            <?php
                            if ($cid == 72) {
                                $brandSingle = mysql_query("SELECT title FROM _content_brend WHERE resource_id = $item->brand LIMIT 1");
                                $brandSingle = mysql_fetch_object($brandSingle);
                                ?>
                                <td><?= $brandSingle->title; ?></td>
                            <?php } ?>
                            <?php if ($contentType->category_type != 0) { ?>
                                <td><?= $item->cat_title; ?></td>
                            <?php } ?>
                            <?php
                            $counter = 0;

                            foreach ($tableCustomFields as $itemFld => $tbl_name) {
                                $counter++;
                                $fType = $tbl_name[0];
                                $fColumnName = $tbl_name[1];
                                $idElement = $cid . "_" . $item->resource_id . "_" . $fColumnName;
                                switch ($fType) {
                                    case "select_table":
                                        $prefix = "cust" . $counter;
                                        echo "<td>" . $item->$prefix . "</td>";
                                        break;
                                    case "text":
                                    case "textarea":
                                    case "select":
                                        echo "<td class='editable' id='$idElement'>" . $item->$fColumnName . "</td>";
                                        break;
                                }
                            }

                            if ($cid == 72 || $cid == 80 || $cid == 68 || $cid == 73) {
                                ?>
                                <td><?= $item->num_views; ?></td>
                            <?php } ?>
                            <td class='editable_select' data-rid='<?= $item->resource_id; ?>' data-cid='<?= $cid; ?>' >
                                <?php
                                switch ($item->status) {
                                    case "1":
                                        echo "<strong id='status_$item->resource_id' style='color:green'>active</strong>";
                                        break;
                                    default:
                                        echo "<strong id='status_$item->resource_id' style='color:red'>inactive</strong>";
                                        break;
                                }
                                ?>    
                            </td>
                            <td class='editable_ordering' data-rid='<?= $item->resource_id; ?>' data-cid='<?= $cid; ?>'>
                                <?= "<strong id='status_$item->resource_id' style='color:blue'>" . $item->ordering . "</strong>"; ?>
                            </td>
                            <td>
                                <a title="Edit <?= $contentType->title; ?>: <?= $item->title; ?>" class="button_table tooltip" href="edit.php?rid=<?= $item->resource_id; ?>&cid=<?= $cid; ?>">
                                    <span class="ui-icon ui-icon-pencil"></span>
                                </a>
                                <a title="Copy <?= $contentType->title; ?>: <?= $item->title; ?>" class="button_table tooltip" href="work.php?action=copy_content&rid=<?= $item->resource_id; ?>&cid=<?= $cid; ?>">
                                    <span class="ui-icon ui-icon-search"></span>
                                </a>
                                <a title="Delete <?= $contentType->title; ?>: <?= $item->title; ?>" class="button_table tooltip" href="work.php?action=delete_content&rid=<?= $item->resource_id; ?>" onclick="return confirm('Are you sure you want delete this content? Deleting this contnet type all data will be deleted and lost.');">
                                    <span class="ui-icon ui-icon-trash"></span>
                                </a>
                                <a title="Order <?= $contentType->title; ?>: <?= $item->title; ?>" class="button_table tooltip handle" href="javascript:void(0);">
                                    <span class="ui-icon ui-icon-arrow-4"></span>
                                </a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php
        } else {
            echo "<strong style='font-size:20px; padding:20px; display:block; background:#454545; color:#FFF;'>There is nothing under these search criteria!</strong>";
        }
    }

    function printContentCarts($cid, $where = "", $order = "", $limit, $category_id) {

        $contentType = new View("content_types", $cid);
        $defLanguage = new View("languages", 1, "is_default");

        if ($where == "") {
            $where = "WHERE lang ='$defLanguage->id'";
        } else {
            $where .= " AND lang = '$defLanguage->id'";
        }

        $contentCollection = new Collection($contentType->table_name);
        $contentCollection->_limit = $limit;
        $content = $contentCollection->getCollection($where, "ORDER BY system_date DESC ");

        if ($category_id != 0) {
            $category = new View("categories", $category_id);
            $cat_contCollection = new Collection("categories_content");
            $cat_cont = $cat_contCollection->getCollection("WHERE category_resource_id = " . $category->resource_id);
            $uslov = "(";
            foreach ($cat_cont as $cc)
                $uslov .= $cc->content_resource_id . ",";
            $uslov .= "0)";
            $content = $contentCollection->getCollection("WHERE resource_id IN " . $uslov . " AND lang =" . $defLanguage->id, $order);
        }
        $showInListFieldsCollection = new Collection("content_type_fields");
        $showInListFields = $showInListFieldsCollection->getCollection("WHERE content_type_id = '$cid' AND show_in_list = '1'");

        if ($contentCollection->resultCount > 0) {
            ?>
            <table cellspacing="0" cellpadding="0" border="0" class="fullwidth c_table">
                <thead>
                    <tr>
                        <th>ID</th>

                        <th>Status</th>
                        <?php
                        foreach ($showInListFields as $field) {
                            echo "<th>" . $field->title . "</th>";
                        }
                        ?>

                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($content as $item) {
                        ?>
                        <tr id="item_<?= $item->resource_id; ?>" <?php if ($item->status == 2) echo 'style="background:#ffe5eb"'; ?>>
                            <td><?= $item->resource_id; ?></td>

                            <td>
                                <?php
                                switch ($item->status) {
                                    case 2:
                                        echo "Treba da se pogleda";
                                        break;

                                    case 1:
                                        echo "Jos kupuje";
                                        break;

                                    case 3:
                                        echo "Zavrsena i poslata";
                                        break;

                                    case 4:
                                        echo "Trash";
                                        break;
                                }
                                ?>
                            </td>
                            <?php
                            foreach ($showInListFields as $field) {
                                $columnName = $field->column_name;

                                switch ($field->field_type) {

                                    case "text":
                                    case "textarea":
                                    case "wysiwyg":
                                    case "wysiwyg":
                                    case "select":
                                    case "radio":
                                    case "checkbox":
                                    case "datepicker":
                                    case "colorpicker":
                                        echo "<td>" . $item->$columnName . "</td>";
                                        break;
                                    case "image":
                                        $dimensionUrl = Database::getValue("url", "content_type_dimensions", "content_type_id", $cid);
                                        $image_name = $item->$columnName;
                                        echo "<td>";
                                        if (is_file("../../uploads/uploaded_pictures/$contentType->table_name/$dimensionUrl/$image_name")) {
                                            echo "<a onclick='return hs.expand(this)' class='highslide' href='/uploads/uploaded_pictures/$contentType->table_name/$dimensionUrl/$image_name?$rand'>View image</a>";
                                        } else {
                                            echo "Not set";
                                        }
                                        echo "</td>";
                                        break;
                                    case "file":
                                        $file_name = $item->$columnName;
                                        echo "<td>";
                                        if (is_file("../../uploads/uploaded_files/$contentType->table_name/$file_name")) {
                                            echo "<a target='_blank' href='/uploads/uploaded_files/$contentType->table_name/$file_name?$rand'>View file</a>";
                                        } else {
                                            echo "Not set";
                                        }
                                        echo "</td>";
                                        break;
                                    case "select_table":
                                        list($tableSelect, $keyField, $labelField) = explode(",", $field->default_value);
                                        echo "<td>";
                                        echo Database::getValue($labelField, $tableSelect, $keyField, $item->$columnName);
                                        echo "</td>";
                                        break;
                                }
                            }
                            ?>

                            <td><?= Functions::makeFancyDate($item->system_date); ?></td>
                            <td>
                                <a title="Edit <?= $contentType->title; ?>: <?= $item->title; ?>" class="button_table tooltip" href="edit.php?rid=<?= $item->resource_id; ?>&cid=<?= $cid; ?>">
                                    <span class="ui-icon ui-icon-pencil"></span>
                                </a>
                                <a title="Copy <?= $contentType->title; ?>: <?= $item->title; ?>" class="button_table tooltip" href="work.php?action=copy_content&rid=<?= $item->resource_id; ?>&cid=<?= $cid; ?>">
                                    <span class="ui-icon ui-icon-search"></span>
                                </a>
                                <a title="Delete <?= $contentType->title; ?>: <?= $item->title; ?>" class="button_table tooltip" href="work.php?action=delete_content&rid=<?= $item->resource_id; ?>" onclick="return confirm('Are you sure you want delete this content? Deleting this contnet type all data will be deleted and lost.');">
                                    <span class="ui-icon ui-icon-trash"></span>
                                </a>
                                <a title="Order <?= $contentType->title; ?>: <?= $item->title; ?>" class="button_table tooltip handle" href="javascript:void(0);">
                                    <span class="ui-icon ui-icon-arrow-4"></span>
                                </a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php
        } else {
            /* ?>
              <form method="POST" id="add_content" name="add_content" action="work.php">
              <div id="tabs">
              <ul>
              <?php
              $languagesCollection = new Collection("languages");
              $languages = $languagesCollection->getCollection();
              foreach ($languages as $key=>$language) {
              ?>
              <li><a href="#tab<?= $language->code; ?>"><?= $language->title; ?></a></li>
              <?php
              }
              ?>
              </ul>
              <?php
              foreach ($languages as $key=>$language) {
              $lang_code = $language->code;
              ?>
              <div id="tab<?= $language->code; ?>">
              <fieldset>
              <legend>Add new <?= $contentTypeContent->title; ?>  for language: <?= $language->title; ?></legend>
              <p>
              <label for="title">Title</label>
              <input type="text" class="lf" name="<?= $language->code; ?>[title]" id="title" />
              </p>

              <input type="button" class="button save_edit_content" value="Save and edit" /> <input type="submit" class="button" value="Save" />
              </fieldset>
              </div>
              <?php
              }
              ?>
              <input type="hidden" name="action" value="add_content" />
              <input type="hidden" name="content_type_id" value="<?= $cid; ?>" />
              <input type="hidden" name="edit" id="edit" value="0" />
              </div>
              </form>
              <?php */
        }
    }

    function printUsersTable($users) {
        $usersGlobalFields = new Collection("fields");
        $usersGlobalFieldsCollection = $usersGlobalFields->getCollection("WHERE table_name = 'users' AND show_in_list = '1'");
        ?>
        <table cellspacing="0" cellpadding="0" border="0" class="fullwidth users_table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fullname</th>
                    <th>Email</th>
                    <th>Date Added</th>
                    <th>Status</th>
                    <th>Facebook user</th>
                    <?php
                    foreach ($usersGlobalFieldsCollection as $key => $userField) {
                        echo '<th>' . $userField->title . '</th>';
                    }
                    ?>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr><td></td></tr>
                <?php
                $i = 1;
                foreach ($users as $key => $user) {
                    if ($i % 2)
                        $class = "";
                    else
                        $class = "odd";
                    ?>
                    <tr class="<?= $class; ?>" id="item_<?= $user->id; ?>" <?php if ($user->status == 0) echo 'style="background:#ffe5eb"'; ?>>
                        <td><?= $user->id; ?></td>
                        <td><?= $user->fullname; ?></td>
                        <td><?= $user->email; ?></td>
                        <td><?= $user->date_added; ?></td>
                        <td><?php
                            switch ($user->status) {
                                case 1:
                                    echo "Approved";
                                    break;
                                case 0:
                                    echo "Not approved";
                                    break;
                                default:
                                    echo "Banned";
                            };
                            ?>
                        </td>
                        <td><?php
                            switch ($user->fbuser) {
                                case 1:
                                    echo "Yes";
                                    break;
                                default:
                                    echo "No";
                            };
                            ?>
                        </td>
                        <?php
                        foreach ($usersGlobalFieldsCollection as $key => $userField) {
                            $column_name = $userField->column_name;
                            echo '<td>' . $user->$column_name . '</td>';
                        }
                        ?>
                        <td>
                            <a title="Delete user: <?= $user->fullname; ?>" class="button_table tooltip" href="work.php?action=delete_user&user_id=<?= $user->id; ?>" onclick="return confirm('Are you sure?');">
                                <span class="ui-icon ui-icon-trash"></span>
                            </a>

                            <a title="Edit user: <?= $user->fullname; ?>" class="button_table tooltip" href="edit_user.php?user_id=<?= $user->id; ?>">
                                <span class="ui-icon ui-icon-pencil"></span>
                            </a>
                        </td>
                    </tr>
                    <?php
                    $i++;
                }
                ?>
                <tr><td></td></tr>
            </tbody>
        </table>
        <?php
    }

    function printMenusTable() {
        $defLanguage = new View("languages", "1", "is_default");

        $menusCollection = new Collection("menus");
        $menus = $menusCollection->getCollection("WHERE lang_id='$defLanguage->id'", "ORDER BY id");

        if ($menusCollection->resultCount > 0) {
            ?>
            <table cellspacing="0" cellpadding="0" border="0" class="fullwidth category_table">
                <thead>
                    <tr>
                        <th>rID</th>
                        <th>Title</th>
                        <th>URL</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($menus as $key => $menu) {
                        ?>
                        <tr id="item_<?= $menu->resource_id; ?>">
                            <td><?= $menu->resource_id; ?></td>
                            <td><?= $menu->title; ?></td>
                            <td><?= $menu->url; ?></td>
                            <td>
                                <a title="Edit menu: <?= $menu->title; ?>" class="button_table tooltip" href="edit_menu.php?rid=<?= $menu->resource_id; ?>">
                                    <span class="ui-icon ui-icon-pencil"></span>
                                </a>
                                <a title="Delete menu: <?= $menu->title; ?>" class="button_table tooltip" href="work.php?action=delete_menu&rid=<?= $menu->resource_id; ?>" onclick="return confirm('Are you sure you want delete this menu? All menu links will be deleted too.');">
                                    <span class="ui-icon ui-icon-trash"></span>
                                </a>
                                <a title="View menu links for: <?= $menu->title; ?>" class="button_table tooltip" href="menu.php?rid=<?= $menu->resource_id; ?>">
                                    <span class="ui-icon ui-icon-search"></span>
                                </a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php
        } else {
            ?>
            <p>No listed menus.</p>
            <?php
        }
    }

    function printAlbumsListTable($albums) {
        ?>
        <table cellspacing="0" cellpadding="0" border="0" class="fullwidth">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Number of pictures</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 0;
                foreach ($albums as $key => $album) {
                    if ($i % 2)
                        $class = "";
                    else
                        $class = "odd";
                    ?>
                    <tr class="<?= $class; ?>" id="item_<?= $user->id; ?>">
                        <td><?= $album->id; ?></td>
                        <td>[ALBUM url="<?= $album->title; ?>"]</td>
                        <td><?= $album->num_of_pic; ?></td>
                        <td>
                            <a title="View pictures in album: <?= $album->title; ?>" class="button_table tooltip" href="album.php?album_id=<?= $album->id; ?>">
                                <span class="ui-icon ui-icon-search"></span>
                            </a>
                            <a title="Edit album: <?= $album->title; ?>" class="button_table tooltip" href="edit_album.php?album_id=<?= $album->id; ?>">
                                <span class="ui-icon ui-icon-pencil"></span>
                            </a>
                            <?php if ($album->num_of_pic == 0) { ?>
                                <a title="Delete album: <?= $album->title; ?>" class="button_table tooltip" href="work.php?action=delete_album&album_id=<?= $album->id; ?>" onclick="return confirm('Are you sure?');">
                                    <span class="ui-icon ui-icon-trash"></span>
                                </a>
                                <?php
                            }
                        }
                        ?>
            </tbody>
        </table>
        <?php
    }

    function printPicturesListTable($album_id) {

        $albumSingle = new View("albums", $album_id);

        $picturesCollection = new Collection("pictures");
        $pictures = $picturesCollection->getCollection("WHERE album_id = '$album_id'", "ORDER BY ordering");

        if ($albumSingle->num_of_pic > 0) {
            ?>
            <form method="POST" action="work.php">
                <table cellspacing="0" cellpadding="0" border="0" class="fullwidth pictures_table">
                    <thead>
                        <tr>
                            <td><input type="checkbox" class="checkall"></td>
                            <th>Picture</th>
                            <th>Picture name</th>
                            <th>Size</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        foreach ($pictures as $picture) {
                            if ($i++ % 2)
                                $class = "";
                            else
                                $class = "odd";
                            ?>
                            <tr id="item_<?= $picture->id; ?>">
                                <td>
                                    <input type="checkbox" name="pictures[]" value="<?= $picture->id; ?>" />
                                </td>
                                <td>
                                    <a href="/uploads/uploaded_pictures/albums/resize/<?= $picture->file_name ?>" class="highslide " onclick="return hs.expand(this)">
                                        <img src="/uploads/uploaded_pictures/albums/crop/<?= $picture->file_name ?>" />
                                    </a>
                                </td>
                                <td><?= $picture->picture_name; ?></td>
                                <td>
                                    <?php
                                    $size = filesize("../../uploads/uploaded_pictures/albums/resize/$picture->file_name");
                                    $size = Functions::sizeOfFile($size);
                                    echo $size;
                                    ?>
                                </td>
                                <td>

                                    <a title="Edit picture: <?= $picture->picture_name; ?>" class="button_table tooltip" href="edit_picture.php?picture_id=<?= $picture->id; ?>&album_id=<?= $album_id; ?>">
                                        <span class="ui-icon ui-icon-pencil"></span>
                                    </a>                                    

                                    <a title="Order picture: <?= $picture->picture_name; ?>" class="button_table tooltip handle" href="javascript:void(0);">
                                        <span class="ui-icon ui-icon-arrow-4"></span>
                                    </a>

                                    <a title="Delete picture: <?= $picture->picture_name; ?>" class="button_table tooltip" href="work.php?action=delete_picture&picture_id=<?= $picture->id; ?>&album_id=<?= $album_id; ?>" onclick="return confirm('Are you sure?');">
                                        <span class="ui-icon ui-icon-trash"></span>
                                    </a>



                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
                <input type="hidden" name="album_id" value="<?= $album_id; ?>" />
                <input type="hidden" name="action" value="delete_selected_pictures" />
                <p>
                    <input type="submit" class="button" value="Delete selected" onclick="return confirm('Are you sure?');" />
                </p>
            </form>
            <?php
        } else {
            ?>
            <p>No listed pictures.</p>
            <?php
        }
    }

    function printAdminListTable($adminId) {
        $adminsCollection = new Collection("administrators");
        $admins = $adminsCollection->getCollection();

        if ($adminsCollection->resultCount > 0) {
            ?>
            <table cellspacing="0" cellpadding="0" border="0" class="fullwidth">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Fullname</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    foreach ($admins as $admin) {
                        if ($admin->id != 1) {
                            if ($i++ % 2)
                                $class = "";
                            else
                                $class = "odd";
                            ?>
                            <tr>
                                <td><?= $admin->id; ?>	</td>
                                <td><?= $admin->username; ?></td>
                                <td>
                                    <?php
                                    switch ($admin->role) {
                                        case 1:
                                            echo "Administrator";
                                            break;
                                        case 2:
                                            echo "Moderator";
                                            break;
                                    }
                                    ?>
                                </td>
                                <td><?= $admin->fullname; ?></td>
                                <td><?= $admin->email; ?></td>
                                <td>

                                    <a title="Edit" class="button_table tooltip" href="edit_admin.php?admin_id=<?= $admin->id; ?>">
                                        <span class="ui-icon ui-icon-pencil"></span>
                                    </a> 
                                    <?php if ($adminId != $admin->id) { ?>
                                        <a title="Delete" class="button_table tooltip" href="work.php?action=delete_admin&admin_id=<?= $admin->id; ?>" onclick="return confirm('Are you sure?');">
                                            <span class="ui-icon ui-icon-trash"></span>
                                        </a>  
                                    <?php } ?>   
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
            <?php
        } else {
            ?>
            <p>No listed admins.</p>
            <?php
        }
    }

    function printShoppingCarts($carts) {
        ?>
        <table cellspacing="0" cellpadding="0" border="0" class="fullwidth rezultati">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tip kupca</th>
                    <th>Kupac</th>
                    <th>Adresa</th>
                    <th>Kupac email</th>
                    <th>Kupac telefon</th>
                    <th>Datum porudzbine</th>
                    <th>Status</th>
                    <th>Akcije</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 0;
                foreach ($carts as $cart) {
                    if ($i++ % 2)
                        $class = "";
                    else
                        $class = "odd";
                    ?>
                    <tr <?php if ($cart->status == 0) echo 'style="background:#ffe5eb"'; ?>>
                        <td><?= $cart->id; ?>	</td>
                        <?php
                        $userKorpa = new View("_content_korisnici", $cart->user_id, "id");
                        ?>
                        <td><?php
                            echo ($userKorpa->tip_kupca) ? $userKorpa->tip_kupca : "Fizičko lice";
                            ?>
                        </td>
                        <td><?= ($userKorpa->tip_kupca == "Pravno lice") ? $userKorpa->firma . "<br>" : ""; ?><?= $userKorpa->ime . " " . $userKorpa->prezime; ?></td>
                        <?php
                        $city = new View("_content_gradovi", $cart->grad, "resource_id");
                        ?>
                        <td><?= $cart->adresa . " " . $cart->naselje . ", " . $cart->zip . " " . $city->title; ?></td>

                        <td><?php
                            $sufix = "e-mail";
                            echo $userKorpa->$sufix;
                            ?>
                        </td>
                        <td><?php
                            echo $cart->telefon;
                            ?>
                        </td>
                        <td><?= Functions::makeFancyDate($cart->system_date); ?></td>
                        <td><?php
                            switch ($cart->status) {
                                case 1:
                                    echo "Tek pristigla korpa";
                                    break;
                                case 2:
                                    echo "Poslata roba";
                                    break;
                                case 3:
                                    echo "Plaćeno";
                                    break;
                                default:
                                    echo "Trash";
                            };
                            ?>
                        </td>
                        <td>
                            <a title="View items in cart: <?= $cart->title; ?>" class="button_table tooltip" href="cart.php?cart_rid=<?= $cart->id; ?>">
                                <span class="ui-icon ui-icon-search"></span>
                            </a>
                            <a title="Delete <?= $cart->title; ?>" class="button_table tooltip" href="work.php?action=delete_cart&cart_id=<?= $cart->id; ?>" onclick="return confirm('Are you sure you want delete this cart? Deleting this cart all data will be deleted and lost.');">
                                <span class="ui-icon ui-icon-trash"></span>
                            </a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
        <?php
    }

    function printShoppingCartItems($cart_id) {

        $proizvodiKorpe = Database::execQuery("SELECT PK.gratis as gratis, PK.gratis2 as gratis_two, PK.id, PK.original_rid, CP.product_image as slika, PK.title, PK.cena, PK.kolicina FROM proizvodi_korpe PK "
                        . "JOIN _content_proizvodi CP ON CP.resource_id = PK.original_rid "
                        . "WHERE PK.korpa_rid = $cart_id");


        $dimensions = new Collection("content_type_dimensions");
        $dimsArr = $dimensions->getCollection("WHERE content_type_id = 72 AND url NOT LIKE 'g%' ORDER BY LENGTH(url),url");
        $dimData = $dimsArr[0];
        $dimUrlLit = $dimData->url;

        $dimData = $dimsArr[1];
        $dimUrlLitSecund = $dimData->url;
        
        $dimData = $dimsArr[2];
        $dimUrlLitThird = $dimData->url;
        ?>





        <script type="text/javascript">
            $(document).ready(function () {
                $(".rezultati tbody tr td p strong").dblclick(function () {
                    var qty = $(this).html();
                    var id = $(this).data("id");
                    var input_html = "<strong><input data-id='" + id + "' type='text' value='" + qty + "'/> <button>Sacuvaj</button></strong>"
                    $(this).parent().html(input_html);
                });
            });
            $(document).on("click", ".rezultati tbody tr td p strong button", function () {
                var proizvod_id = $(this).parent().find("input[type='text']").data("id");
                var nova_kolicina = $(this).parent().find("input[type='text']").val();

                var href = document.location.href;
                $.ajax({
                    method: "post",
                    url: "work.php",
                    data: {'proizvod_id': proizvod_id, 'action': "change_kolicina", 'kolicina': nova_kolicina}
                })
                        .done(function (msg) {
                            window.location.href = href;
                        });
            });
        </script>    
        <table cellspacing="0" cellpadding="0" border="0" class="fullwidth rezultati">
            <thead>
                <tr>
                    <th>Slika</th>
                    <th>Naziv</th>
                    <th>Cena</th>
                    <th>Kolicina<br><i>(Klikni 2x ukoliko želiš da promeniš kolićinu)</i></th>
                    <th>Cena ukupno po proizvodu</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($dataKorpa = mysql_fetch_array($proizvodiKorpe)) {
                    ?>
                    <tr>
                        <td>
                            <?php if (is_file("../../uploads/uploaded_pictures/_content_proizvodi/" . $dimUrlLit. "/" . $dataKorpa['slika'])) { ?>
                                <img style="widht:auto; height: 50px;" src="/uploads/uploaded_pictures/_content_proizvodi/<?= $dimUrlLit . "/" . $dataKorpa['slika']; ?>" alt="<?= $dataKorpa['title']; ?>" title="<?= $dataKorpa['title']; ?>" />
                            <?php } else if (is_file("../../uploads/uploaded_pictures/_content_proizvodi/" . $dimUrlLitSecund . "/" . $dataKorpa['slika'])) { ?>
                                <img style="widht:auto; height: 50px;" src="/uploads/uploaded_pictures/_content_proizvodi/<?= $dimUrlLitSecund . "/" . $dataKorpa['slika']; ?>" alt="<?= $dataKorpa['title']; ?>" title="<?= $dataKorpa['title']; ?>" />
                            <?php } else if (is_file("../../uploads/uploaded_pictures/_content_proizvodi/" . $dimUrlLitThird . "/" . $dataKorpa['slika'])) { ?>
                                <img style="widht:auto; height: 50px;" src="/uploads/uploaded_pictures/_content_proizvodi/<?= $dimUrlLitThird . "/" . $dataKorpa['slika']; ?>" alt="<?= $dataKorpa['title']; ?>" title="<?= $dataKorpa['title']; ?>" />
                            <?php } ?> 

                        </td>
                        <td><?= $dataKorpa['title']; ?></td>
                        <td><?= number_format($dataKorpa['cena'], 2, ",", "."); ?> rsd</td>
                        <td>
                            <p><strong data-id='<?= $dataKorpa[id]; ?>'><?= $dataKorpa['kolicina']; ?></strong></p>

                        </td>
                        <td>
                            <strong><?= number_format($dataKorpa['cena'] * $dataKorpa['kolicina'], 2, ",", "."); ?> rsd</strong>
                        </td>
                    </tr>
                    <?php
                    if ($dataKorpa['gratis']) {
                        $gratis = mysql_query("SELECT cp.product_image, cp.resource_id, cp.title, cp.url, c.url as sub_url, c1.url as cat_url, cb.title as b_title FROM _content_proizvodi cp "
                                . " LEFT JOIN categories_content cc ON cc.content_resource_id = cp.resource_id "
                                . " LEFT JOIN categories c ON c.resource_id = cc.category_resource_id "
                                . " LEFT JOIN categories c1 ON c.parent_id = c1.resource_id "
                                . " LEFT JOIN _content_brend cb ON cb.resource_id = cp.brand "
                                . " WHERE cp.resource_id = " . $dataKorpa['gratis'] . " LIMIT 1");
                        $gratis = mysql_fetch_object($gratis);
                        ?>
                        <tr>
                            <td>
                                <a href="/<?= $gratis->sub_url . "/" . $gratis->cat_url . "/" . $gratis->url . "/" . $gratis->resource_id; ?>" title="<?= $gratis->b_title . " " . $gratis->title; ?>">
                                    <img style="widht:auto; height: 50px;" src="/uploads/uploaded_pictures/_content_proizvodi/<?php echo $dimUrlLitSecund . "/" . $gratis->product_image; ?>" alt="<?= $gratis->b_title . " " . $gratis->title; ?>">
                                </a>
                            </td>
                            <td><?php echo $gratis->title; ?></td>
                            <td>1,00 rsd</td>
                            <td><?= $dataKorpa['kolicina']; ?></td>
                            <td>
                                <strong><?= $dataKorpa['kolicina']; ?>,00 rsd</strong>
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                        <?php
                        $total += 1;
                    }
                    if ($dataKorpa['gratis_two']) {
                        $gratis = mysql_query("SELECT cp.product_image, cp.resource_id, cp.title, cp.url, c.url as sub_url, c1.url as cat_url, cb.title as b_title FROM _content_proizvodi cp "
                                . " LEFT JOIN categories_content cc ON cc.content_resource_id = cp.resource_id "
                                . " LEFT JOIN categories c ON c.resource_id = cc.category_resource_id "
                                . " LEFT JOIN categories c1 ON c.parent_id = c1.resource_id "
                                . " LEFT JOIN _content_brend cb ON cb.resource_id = cp.brand "
                                . " WHERE cp.resource_id = " . $dataKorpa['gratis_two'] . " LIMIT 1");
                        $gratis = mysql_fetch_object($gratis);
                        ?>
                        <tr>
                            <td>
                                <a href="/<?= $gratis->sub_url . "/" . $gratis->cat_url . "/" . $gratis->url . "/" . $gratis->resource_id; ?>" title="<?= $gratis->b_title . " " . $gratis->title; ?>">
                                    <img style="widht:auto; height: 50px;" src="/uploads/uploaded_pictures/_content_proizvodi/<?php echo $dimUrlLitSecund . "/" . $gratis->product_image; ?>" alt="<?= $gratis->b_title . " " . $gratis->title; ?>">
                                </a>
                            </td>
                            <td><?= $gratis->b_title . " " . $gratis->title; ?></td>
                            <td>1,00 rsd</td>
                            <td><?= $dataKorpa['kolicina']; ?></td>
                            <td>
                                <strong><?= $dataKorpa['kolicina']; ?>,00 rsd</strong>
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                        <?php
                        $total += 1;
                    }

                    $total += $dataKorpa['cena'] * $dataKorpa['kolicina'];
                }
                ?>
            </tbody>
            <tfoot>
                <tr>

                    <td>

                    </td>
                    <td>

                    </td>
                    <td>

                    </td>
                    <td>
                        <span style="font-size: 20px;">UKUPNO</span>
                    </td>
                    <td>
                        <span style="font-size: 20px; color:red;"><?= number_format($total, 2, ",", "."); ?> rsd</span>
                    </td>

                </tr>
            </tfoot>
        </table>
        <?php
    }

}

//	end of class
?>