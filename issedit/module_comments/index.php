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
                        <?php
                        if ($content_type != "" && $parent_id == "") {
                            ?>
                            <h1>All comments for type <?= $db->getValue("title", "content_type", "id", $content_type); ?></h1>
                            <?php
                        } else if ($content_type != "" && $parent_id != "") {
                            ?>
                            <h1>All comments for content - <?= $db->getValue("title", "content", "id", $parent_id); ?> and type - <?= $db->getValue("title", "content_type", "id", $content_type); ?></h1>
                            <?php
                        } else {
                            ?>
                            <h1>All comments for content</h1>
                            <?php
                        }
                        ?>

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
                            "author_name" => array(
                                "type" => "text",
                                "value" => $_SESSION['admin_info']['fullname'],
                                "class" => "lf",
                                "style" => "",
                                "required" => "1",
                                "label" => "",
                                "error" => "Field can not be empty!.::Max field size is 200!",
                                "description" => "",
                                "additional" => "",
                                "js_valid" => "text,0,201"
                            ),
                            "author_email" => array(
                                "type" => "text",
                                "value" => $_SESSION['admin_info']['email'],
                                "class" => "lf",
                                "style" => "",
                                "required" => "1",
                                "label" => "",
                                "error" => "Field can not be empty!.::You must enter valid email address!",
                                "description" => "",
                                "additional" => "",
                                "js_valid" => "email,0"
                            ),
                            "comment_text" => array(
                                "type" => "textarea",
                                "value" => "",
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
                                "selected" => "1",
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
                                "value" => "replay_comment",
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
                            "save_button" => array(
                                "type" => "submit",
                                "value" => "Replay",
                                "class" => "button",
                                "style" => "",
                                "additional" => ""
                            )
                        );

                        if ($parent_id != "" && $content_type != "") {
                            $form->printForm($method, $action, $fields, $name, $style, $type);
                        }
                        ?>
                        <hr />

                        <p>	

                            <form method="GET" action="index.php" class="left_form w590">

                                <select name="sort_status" class="w120">
                                    <option value="">Choose status...</option>
                                    <option value="1" <?php if ($sort_status == 1) echo 'selected="selected"'; ?>>Not Approved</option>
                                    <option value="2" <?php if ($sort_status == 2) echo 'selected="selected"'; ?>>Approved</option>
                                </select>

                                <select name="sort_other" class="w120">
                                    <option value="">Choose fiter...</option>
                                    <option value="1" <?php if ($sort_other == 1) echo 'selected="selected"'; ?>>Date ASC</option>
                                    <option value="2" <?php if ($sort_other == 2) echo 'selected="selected"'; ?>>Date DESC</option>
                                </select>

                                <input type="hidden" name="parent_id" value="<?= $parent_id; ?>" />
                                <input type="hidden" name="content_type" value="<?= $content_type; ?>" />

                                <input type="submit" class="button" value="Filter" />

                            </form>

                            <input type="text" name="search_input" id="search_input" class="sf right_input" onblur="if (this.value == '')
                                        this.value = this.defaultValue;" onfocus="if (this.value == this.defaultValue)
                                                    this.value = '';" value="Live search..." />
                        </p>			

                        <br clear="all" />

                        <p>
                            <?php $comments->printCommentsTable($parent_id, $content_type, $currentLanguage, $sort_status, $sort_other); ?>
                        </p>	

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