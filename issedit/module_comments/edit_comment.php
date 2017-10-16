<?php
require("../library/config.php");
$f->checkLogedAdmin("module");

$categories = new Categories();
$content = new Content();
$comments = new Comments();

$module_name = "comments";

$content_type = $f->getValue("content_type");
$sort_status = $f->getValue("sort_status");
$sort_other = $f->getValue("sort_other");
$parent_id = $f->getValue("parent_id");

$comment_id = $f->getValue("comment_id");
$query_comment = $db->execQuery("SELECT * FROM comments WHERE comment_id='$comment_id'");
$data_comment = mysql_fetch_array($query_comment);
$page = $f->getValue("page");

if ($data_comment['approved'] == 0) {
    $comment_approved = 2;
} else {
    $comment_approved = 1;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />		
        <title><?= ADMIN_TITLE; ?></title>
        <?php include("../head.php"); ?>
    </head>
    <body>
        <!-- Container -->
        <div id="container">

            <?php include("../header.php"); ?>

            <!-- Background wrapper -->
            <div id="bgwrap">

                <!-- Main Content -->
                <div id="content">
                    <div id="main">
                        <h1>Edit comment</h1>

                        <?php
                        $method = "POST";
                        $action = "work.php";
                        $name = "replay";
                        $style = "";
                        $type = "";

                        //Comments user array for option
                        $approved["new"] = array(); //ovo new je label za OPTGROUP

                        $novi = array("1" => "Approved");
                        $novi2 = array("2" => "Not Approved");

                        array_push($approved["new"], $novi);
                        array_push($approved["new"], $novi2);

                        $fields = array(
                            "comment_text" => array(
                                "type" => "textarea",
                                "value" => $data_comment['comment_text'],
                                "class" => "lf",
                                "style" => "width: 485px; height: 80px;",
                                "required" => "1",
                                "label" => "",
                                "error" => "Field can not be empty!",
                                "description" => "",
                                "additional" => "",
                                "js_valid" => "text,0"
                            ),
                            "approved" => array(
                                "type" => "select",
                                "option" => $approved,
                                "selected" => $comment_approved,
                                "class" => "lf",
                                "default_msg" => "Choose...",
                                "style" => "",
                                "required" => "1",
                                "label" => "", // ako je prazno onda u labelu upisuje naziv polja
                                "error" => "You must choose something!", //ako je prazno onda ide default poruka
                                "description" => "", //Opis polja sta treba da se unese ili tako nesto
                                "additional_option" => "",
                                "additional" => "", // sve ostalo sto moze da stane u <input> ili ostalo
                                "js_valid" => "select,0"
                            ),
                            "action" => array(
                                "type" => "hidden",
                                "value" => "edit_comment",
                                "additional" => ""
                            ),
                            "parent_id" => array(
                                "type" => "hidden",
                                "value" => $parent_id,
                                "additional" => ""
                            ),
                            "content_type" => array(
                                "type" => "hidden",
                                "value" => $content_type,
                                "additional" => ""
                            ),
                            "sort_other" => array(
                                "type" => "hidden",
                                "value" => $sort_other,
                                "additional" => ""
                            ),
                            "sort_status" => array(
                                "type" => "hidden",
                                "value" => $sort_status,
                                "additional" => ""
                            ),
                            "page" => array(
                                "type" => "hidden",
                                "value" => $page,
                                "additional" => ""
                            ),
                            "comment_id" => array(
                                "type" => "hidden",
                                "value" => $comment_id,
                                "additional" => ""
                            ),
                            "save_button" => array(
                                "type" => "submit",
                                "value" => "Edit",
                                "class" => "button",
                                "style" => "",
                                "additional" => ""
                            )
                        );


                        $form->printForm($method, $action, $fields, $name, $style, $type);
                        ?>
                        <hr />
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