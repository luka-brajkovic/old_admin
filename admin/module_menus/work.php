<?php

require("../library/config.php");

$action = $f->getValue("action");

switch ($action) {

    //Adding new menu
    case "add_menu":
        $langCollection = new Collection("languages");
        $languages = $langCollection->getCollection();

        //Creating new resource
        $resurce = new View("resources");
        $resurce->table_name = "menus";
        $resurce->Save();

        foreach ($languages as $lang) {
            //Creating new manu for each language
            $langValues = $f->getValue($lang->code);
            $menu = new View("menus");
            $menu->extend($langValues);

            $menu->resource_id = $resurce->id;
            $menu->lang_id = $lang->id;
            $menu->url = $f->generateUrlFromText($menu->title);
            $menu->Save();
        }

        $f->redirect("index.php?infomsg=success_add_menu");

        break;

    //Editing menu
    case "edit_menu":

        $langCollection = new Collection("languages");
        $languages = $langCollection->getCollection();

        $resourceId = $f->getValue("resource_id");

        foreach ($languages as $lang) {
            $langValues = $f->getValue($lang->code);
            $menusColl = new Collection("menus");
            $menus = $menusColl->getCollection("WHERE resource_id = '$resourceId' AND lang_id = '$lang->id'");
            $menu = $menus[0];

            $menu->extend($langValues);
            $menu->url = $f->generateUrlFromText($menu->title);
            $menu->Save();
        }

        $f->redirect("index.php?infomsg=success_edit_menu");

        break;

    //removing menu
    case "delete_menu":

        $rid = $f->getValue("rid");
        $res = new View("resources", $rid);
        $res->Remove();
        $f->redirect("index.php?infomsg=success_delete_menu");

        break;

    case "add_link_custom":

        $menu_id = $f->getValue("menu_id");
        $menuResId = $db->getValue("resource_id", "menus", "id", $menu_id);
        $parent_id = $f->getValue("parent_id");
        $menuItem = new View("menu_items");
        $menuItem->extend($_POST);
        $query = $db->execQuery("SELECT * FROM menu_items WHERE menu_id = '$menu_id' AND parent_id = '$parent_id'");
        $data = mysqli_fetch_array($query);
        $ordering = $data['ordering'] + 1;
        $menuItem->ordering = $ordering;
        $menuItem->Save();

        $f->redirect("menu.php?rid=$menuResId&menu_id=$menu_id");

        break;

    case "order_links":

        $menuId = $f->getValue("menu_id");
        $links = $_POST['list'];

        $orderByParent = array();
        foreach ($links as $linkId => $parentId) {
            if ($parentId == "root") {
                $parentId = 0;
            }
            $menuLink = new View("menu_items", $linkId);
            if (array_key_exists($parentId, $orderByParent)) {
                $orderByParent[$parentId] = $orderByParent[$parentId] + 1;
            } else {
                $orderByParent[$parentId] = 0;
            }
            $ordering = $orderByParent[$parentId];
            $menuLink->ordering = $ordering;
            $menuLink->parent_id = $parentId;
            $menuLink->Save();
        }

        var_dump($orderByParent);

        break;

    case "delete_menu_link":

        $link_id = $f->getValue("link_id");
        $link = new View("menu_items", $link_id);
        $menu_id = $link->menu_id;
        $parent_id = $link->parent_id;

        //Kacim decu od ovog sto brisemo za njegovog roditelja, odnosno dedu :)
        $childrenCollection = new Collection("menu_items");
        $children = $childrenCollection->getCollection("WHERE parent_id = '$link_id'");
        foreach ($children as $child) {
            $child->parent_id = $parent_id;
            $child->Save();
        }

        $rid = $db->getValue("resource_id", "menus", "id", $menu_id);
        $link->Remove();

        $f->redirect("menu.php?rid=$rid&menu_id=$menu_id");

        break;

    case "add_item":

        $type = $f->getValue("type");
        $open_in = $f->getValue("open_in");
        $menu_id = $f->getValue("menu_id");
        $parent_id = $f->getValue("parent_id");
        if ($parent_id == "") {
            $parent_id = 0;
        }

        $num = $db->execQuery("SELECT * FROM menu_items WHERE menu_id='$menu_id' AND parent_id='$parent_id'");
        $order = $num++;

        $db->execQuery("INSERT INTO menu_items (`type`, `menu_id`, `parent_id`, `open_type`, `lang_id`, `ordering`) VALUES ('$type', '$menu_id', '$parent_id', '$open_in', '$currentLanguage', '$order')");
        $item_id = $db->insertId;

        $f->redirect("edit_item.php?item_id=$item_id");

        break;

    case "edit_item":

        $item_id = $f->getValue("item_id");
        $menuItem = new View("menu_items", $item_id);
        $menuItem->extend($_POST);
        $menuItem->Save();

        $f->redirect("menu.php?menu_id=$menuItem->menu_id");

        break;

    /*
      $item_id = $f->getValue("item_id");
      $type = $f->getValue("type");
      $parent_id = $f->getValue("parent_id");
      $menu_id = $f->getValue("menu_id");
      $open_in = $f->getValue("open_in");

      if($type == 1) {
      $title = $f->getValue("title");
      $url = $f->getValue("url");

      $db->execQuery("UPDATE menu_items SET `title`='$title', `url`='$url', `open_type`='$open_in' WHERE id='$item_id'");

      } else if($type == 2) {

      $content_id = $f->getValue("content_id");
      $db->execQuery("UPDATE menu_items SET `resource_id`='$content_id', `open_type`='$open_in' WHERE id='$item_id'");


      } else if($type == 3) {

      $category_id = $f->getValue("category_id");
      $db->execQuery("UPDATE menu_items SET `resource_id`='$category_id', `open_type`='$open_in' WHERE id='$item_id'");
      }

      $f->redirect("menu.php?menu_id=$menu_id&parent_id=$parent_id");

      break; */

    case "order_items":

        $item = $_POST["item"];
        $parent_id = $f->getValue("parent_id");

        for ($i = 0; $i < count($item); $i++) {
            $db->execQuery("UPDATE menu_items SET `ordering`='$i' WHERE id='$item[$i]' AND parent_id='$parent_id'");
        }

        break;

    case "delete_item":

        $item_id = $f->getValue("item_id");
        $query_item = $db->execQuery("SELECT * FROM menu_items WHERE id='$item_id'");
        $data_item = mysqli_fetch_array($query_item);

        $menu_id = $data_item['menu_id'];
        $parent_id = $data_item['parent_id'];

        $db->execQuery("DELETE FROM menu_items WHERE id='$item_id'");

        $f->redirect("menu.php?menu_id=$menu_id&parent_id=$parent_id");

        break;
}
?>