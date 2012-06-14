<?php
function open_connection() {
    mysql_connect( $host, $user, $pswd) or die('Could not conn');
    //mysql_select_db($db) or die('Could not select database.');
    mysql_query("USE ".$db);
}

function close_connection() {
    mysql_close();
}

/*function empty_table() {
    mysql_query("DELETE FROM ASRO.layout");
}

function empty_textboxes() {
    mysql_query("DELETE FROM ASRO.textbox");
}*/

function insertXy($name, $x, $y) {
    $r = "";
    $s = mysql_query("INSERT INTO ASRO.layout (name,x,y) VALUES(\"$name\",$x,$y)") or 
die("insertXy: ".mysql_error()) ;
    if ($s) {
        return "$x - $y";
    } else {
        return "fail: $x - $y";
    }
}

function getFormName() {
    $s = mysql_query("SELECT name, layout, textbox FROM ASRO.form ORDER BY name DESC LIMIT 1") or die("getFormName: ".mysql_error());

    return $s;
}

function insertTextboxes($name, $x1, $x2, $y1, $y2, $font) {
    $r = "";
    $s = mysql_query("INSERT INTO ASRO.textbox (name,x1,x2,y1,y2,font) VALUES (\"$name\",$x1,$x2,$y1,$y2,$font)")
     or die ("insertTextboxes: ".mysql_error());
     if ($s) {
         return " x1: $x1 /y1: $y1 /x2: $x2 /y2: $y2 /f: $font ";
     } else {
         return "fail: x1: $x1 /y1: $y1 /x2: $x2 /y2: $y2 /f: $font ";
     }
}

function createForm($name) {
    open_connection();
    $s = mysql_query("INSERT INTO ASRO.form (name, active) VALUES (\"$name\", False)")
     or die ("createForm: ". mysql_error());
    if ($s) {
        return "$name";
    } else {
        return "fail: $name";
    }
    close_connection();
}

function formAddLayout($name, $layout) {
    open_connection();
    $r = "";
    $s = mysql_query("UPDATE ASRO.form SET layout = \"$layout\" WHERE name = \"$name\"")
      or die ("formAddLayout: ".mysql_error());
    if ($s) {
        return "$name | $layout";
    } else {
        return "fail: $name | $layout";
    }
    close_connection();
}

function formAddTextbox($name, $textbox) {
    open_connection();
    $r = "";
    $s = mysql_query("UPDATE ASRO.form SET textbox = \"$textbox\" WHERE name = \"$name\"")
      or die ("formAddTextbox: ".mysql_error());
    if ($s) {
        return "$name | $textbox";
    } else {
        return "fail: $name | $textbox";
    }
    close_connection();
}

function getMededelingen() {
	open_connection();
	$s = mysql_query("SELECT id, content FROM ASRO.mededelingen ORDER BY id DESC") or die("getMededelingen: ".mysql_error());
	close_connection();
    return $s;
}

function saveMededeling($mededeling) {
    open_connection();
    $s = mysql_query("INSERT INTO ASRO.mededelingen (content) VALUES (\"$mededeling\")") or die("saveMededeling: ".mysql_error());
}

function deleteMededeling($id) {
	open_connection();
	$s = mysql_query("DELETE FROM ASRO.mededelingen WHERE id = $id") or die("deleteMededeling: ".mysql_error());
	close_connection();
    return $s;
}

function getOpeningstijden() {
    open_connection();
    $s = mysql_query("SELECT dag, openu, openm, geslotenu, geslotenm FROM ASRO.openingstijden") or die("getOpeningstijden: ".mysql_error());
    close_connection();
    return $s;
}

function deleteOpeningstijden() {
    open_connection();
    $s = mysql_query("DELETE FROM ASRO.openingstijden") or die("deleteOpeningstijden: ".mysql_error());
    close_connection();
}

function fillOpeningstijden($tijden) {
    open_connection();
    foreach($tijden as $dag => $t) {
        $s = mysql_query("INSERT INTO ASRO.openingstijden (dag, openu, openm, geslotenu, geslotenm) VALUES (\"$dag\", $t[0], $t[1], $t[2], $t[3])") or die("fillOpeningstijden: ".mysql_error());
    }
}

function zoTerug($bool) {
    open_connection();
    $s = mysql_query("UPDATE ASRO.pauze SET status = ".$bool) or die ("zoTerug: ".mysql_error());
    close_connection();
    return $s;
}
?>