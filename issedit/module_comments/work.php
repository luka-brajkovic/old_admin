<?php

require("../library/config.php");

$action = $f->getValue("action");

switch ($action) {


    case "replay_comment":

        $author_name = $f->getValue("author_name");
        $author_email = $f->getValue("author_email");
        $comment_text = $f->getValue("comment_text");
        $approved = $f->getValue("approved");
        $parent_id = $f->getValue("parent_id");
        $content_type = $f->getValue("content_type");
        $date = date("Y-m-d H:i:s");

        if ($approved == 2) {
            $approved = 0;
        }

        $db->execQuery("INSERT INTO comments (`type`, `parent_id`, `author_email`, `author_name`, `comment_text`, `approved`, `comment_date`) 
										  VALUES ('1', '$parent_id', '$author_email', '$author_name', '$comment_text', '$approved', '$date')");

        $f->redirect("index.php?parent_id=$parent_id&content_type=$content_type");

        break;

    case "delete_comment":

        $parent_id = $f->getValue("parent_id");
        $content_type = $f->getValue("content_type");
        $sort_status = $f->getValue("sort_status");
        $sort_other = $f->getValue("sort_other");
        $comment_id = $f->getValue("comment_id");

        $db->execQuery("DELETE FROM comments WHERE comment_id='$comment_id'");
        $f->redirect("index.php?sort_status=$sort_status&sort_other=$sort_other&parent_id=$parent_id&content_type=$content_type");

        break;

    case "approve_comment":

        $parent_id = $f->getValue("parent_id");
        $content_type = $f->getValue("content_type");
        $sort_status = $f->getValue("sort_status");
        $sort_other = $f->getValue("sort_other");
        $comment_id = $f->getValue("comment_id");
        $page = $f->getValue("page");

        $db->execQuery("UPDATE comments SET `approved`='1' WHERE comment_id='$comment_id'");
        $f->redirect("index.php?sort_status=$sort_status&sort_other=$sort_other&parent_id=$parent_id&content_type=$content_type&page=$page");

        break;

    case "disapprove_comment":

        $parent_id = $f->getValue("parent_id");
        $content_type = $f->getValue("content_type");
        $sort_status = $f->getValue("sort_status");
        $sort_other = $f->getValue("sort_other");
        $comment_id = $f->getValue("comment_id");
        $page = $f->getValue("page");

        $db->execQuery("UPDATE comments SET `approved`='0' WHERE comment_id='$comment_id'");
        $f->redirect("index.php?sort_status=$sort_status&sort_other=$sort_other&parent_id=$parent_id&content_type=$content_type&page=$page");

        break;

    case "edit_comment":

        $comment_text = $f->getValue("comment_text");
        $approved = $f->getValue("approved");
        $parent_id = $f->getValue("parent_id");
        $content_type = $f->getValue("content_type");
        $sort_other = $f->getValue("sort_other");
        $sort_status = $f->getValue("sort_status");
        $page = $f->getValue("page");

        if ($approved == 2) {
            $approved = 0;
        }

        $db->execQuery("UPDATE comments SET `comment_text`='$comment_text', `approved`='$approved' WHERE comment_id='$comment_id'");
        $f->redirect("index.php?sort_status=$sort_status&sort_other=$sort_other&parent_id=$parent_id&content_type=$content_type&page=$page");

        break;
}
?>