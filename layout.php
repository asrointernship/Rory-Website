<?
if (!isset($auth) || (isset($auth) && ($auth != 1 && $auth != 2))) {
  header('Location: index.php');
}
?>
<h1>Layout</h1>
<button type="button" onclick='setMode("line")'>Line</button>
<button type="button" onclick='setMode("rectangle")'>Rectangle</button>
<button type="button" onclick='setMode("freehand")'>Freehand</button>
<button type="button" onclick='setMode("textbox")'>Textbox</button>
<button type="button" onclick='setColor("white")' id="colorSelect">White</button>
<button type="button" onclick='setMode("image")'>Image</button>
<button type="button" onclick='undo()'>Undo</button>
<input type="text" id="formName" value="Form Name" />
<button type="button" onclick='validFormName()'>Save</button>
<div id="options"></div>
<div id="canvasContainer">
<canvas id="canvas" width="480" height="256"></canvas>
</div>

<h1>Textbox</h1>

<p>Select a form:
<select id="formSelect" onchange="getTextBoxFromForm(this)">
<?
foreach ($forms as $f) {
	echo "<option>$f</option>";
}
?>
</select></p>
<div id="editTextboxes">
</div>
<button type="button" onclick='saveTextboxes()'>Save</button>

<h1>Screen</h1>

<p>Screen 1: <select id="screen1Sel">
<?
$aforms = getActiveForms();
while ($row = mysql_fetch_assoc($aforms)) {
	$selected = "";
	if ($row["active"] == 1) {
		$selected = 'selected = "selected"';
	}
	echo '<option '.$selected.'>'.$row["name"].'</option>';
}
?>
</select></p>
<p>Screen 2: <select id="screen2Sel">
<?
$aforms = getActiveForms();
while ($row = mysql_fetch_assoc($aforms)) {
	$selected = "";
	if ($row["active"] == 2) {
		$selected = 'selected = "selected"';
	}
	echo '<option '.$selected.'>'.$row["name"].'</option>';
}
?>
</select></p>
<p>Screen 3: <select id="screen3Sel">
<?
$aforms = getActiveForms();
while ($row = mysql_fetch_assoc($aforms)) {
	$selected = "";
	if ($row["active"] == 3) {
		$selected = 'selected = "selected"';
	}
	echo '<option '.$selected.'>'.$row["name"].'</option>';
}
?>
</select></p>
<button type="button" onclick='saveActiveScreens()'>Save</button>

<h1>Delete</h1>
<table id="delformList">
    <?php
    $s = getAllForms();
    $tc = 0;
    while ($row = mysql_fetch_assoc($s)) {
      echo '<tr><td>';
      echo $row['name'];
      echo '</td><td><button onclick=\'deleteForm("'.$row['name'].'",'.$tc.')\'>Delete</button></td></tr>';
      $tc ++;
    }
    ?>
</table>