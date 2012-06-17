<?php
//require("php_serial.class.php"); //the one widely available on the Net

$host = "127.0.0.1";
$user = "test_user";
$pswd = "test";
$db = "ASRO";

include('functionsOnthaal.php');

//$names = getFormName();

if (isset($_POST["name"])) {
    $form_name = json_decode(str_replace("\\", "", $_POST['name']), true);
    $layout_name = "layout".$_POST["name"];
    $textbox_name = "textbox".$_POST["name"];

    /*while ($row = mysql_fetch_assoc($names)) {
        //var_dump($row);
        $layout_name = $row["layout"];
        $textbox_name = $row["texbox"];
    }*/

    /*$layout_name = "layout". (intval($temp)+1);
    $textbox_name = "textbox". (intval($temp)+1);*/

    createForm($form_name);

    //echo $form_name;

    if (isset($_POST['textbox'])) {
        $textbox = json_decode(str_replace("\\", "", $_POST['textbox']), true);

        echo "TEXTBOX: ".count($textbox);

        if (count($textbox) > 0) {
            echo " TextLoop ";
            formAddTextbox($form_name, $textbox_name);
            //empty_textboxes();
            open_connection();
            foreach($textbox as $t) {
                echo insertTextboxes($textbox_name, $t['x1'], $t['x2'], $t['y1'], $t['y2'], $t['textFont']);
            }
            close_connection();
        }
    }

    if (isset($_POST['coords'])) {
    	//$data = str_replace("\\", "", $_REQUEST["test"]);
        $coords = json_decode(str_replace("\\", "", $_POST['coords']), true);

        echo "COORDS: ".count($coords);

        if (count($coords) > 0) {
            //$string;
            //empty_table();
            formAddLayout($form_name, $layout_name);
            open_connection();
            foreach($coords as $t) {
        	echo insertXy($layout_name, $t['x'], $t['y']);
            	//writeNrToArduino($t['x']);
            	//writeNrToArduino($t['y']);
            	//$string .= chr($t['x']);
            }
            close_connection();
            //echo $string;
        }
        //exec('python ~/py/sql.py');
    }
}
?>
