<?php

require("../library/config.php");

//Here we have all values from POST and GET
$values = $f->getRequestValues();
$action = $values["action"];

switch ($action) {


    case "save_lang_file":

        $langsQu = mysqli_query($conn,"SELECT * FROM languages");
        while ($lang = mysqli_fetch_object($langsQu)) {

            if (!is_file("../../library/languages/" . $lang->code . ".xml")) {
                $myfile = fopen("../../library/languages/" . $lang->code . ".xml", "w");
            }

            $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
            $xml .= "<promenljive>\n";
            for ($i = 1; $i < 200; $i++) {
                if ($_POST['const_' . $i] != '') {
                    $xml .= "\t<konstanta>\n";
                    $xml .= "\t\t<const>" . $_POST['const_' . $i] . "</const>\n";
                    $xml .= "\t\t<value><![CDATA[" . $_POST[$lang->code . '_' . $i] . "]]></value>\n";
                    $xml .= "\t</konstanta>\n";
                } else {
                    continue;
                }
            }
            $xml .= "</promenljive>\n";

            file_put_contents("../../library/languages/" . $lang->code . ".xml", $xml);
        }

        $f->redirect("index.php");

        break;

    case "generate_xmls":

        $langCollection = new Collection("languages");
        $languages = $langCollection->getCollection();

        foreach ($languages as $lang) {
            $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
            $xml .= "<promenljive>\n";
            $langValues = $f->getValue($lang->code);

            if (!$langValues)//ako se ništa ne doda a ide na save da ne bi prijavljivao grešku
                $f->redirect("index.php");

            $constants = $langValues["constant"];
            $valuesCon = $langValues["value"];
            foreach ($constants as $key => $cval) {
                if ($cval != "" && $valuesCon[$key] != "") {
                    $xml .= "\t<konstanta>\n";
                    $xml .= "\t\t<const>" . $f->generateXmlTag($cval) . "</const>\n";
                    $xml .= "\t\t<value><![CDATA[" . $valuesCon[$key] . "]]></value>\n";
                    $xml .= "\t</konstanta>\n";
                }
            }
            $xml .= "</promenljive>\n";
            file_put_contents("../../library/languages/" . $lang->code . ".xml", $xml);
            $xml = "";
        }

        $f->redirect("index.php");

        break;
}
?>