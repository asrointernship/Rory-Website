<?php
include("functionsOnthaal.php");
if (isset($_POST["status"])) {
	$status = json_decode(str_replace("\\", "", $_POST['status']), true);

	if ($status == "zoterug") {
		echo zoTerug(1);
	} else {
		echo zoTerug(0);
	}
}
if (isset($_POST["mededeling"])) {
	$mededeling = json_decode(str_replace("\\", "", $_POST['mededeling']), true);

	deleteMededeling($mededeling);
}
if (isset($_POST["form"])) {
	$form = json_decode(str_replace("\\", "", $_POST['form']), true);

	$boxes = getTextBoxesFromForm($form); // id, font, content
	$return = array("data" => array());
	while ($row = mysql_fetch_assoc($boxes)) {
		if ($row["content"] == null) {
			$row["content"] = "empty";
		}
		$return["data"][] = array("id" => $row["id"], "font" => $row["font"], "content" => $row["content"]);
	}
	echo json_encode($return);
}
if (isset($_POST["deleteForm"])) {
	$form = json_decode(str_replace("\\", "", $_POST['deleteForm']), true);

	deleteForm($form);
}

if (isset($_POST["saveText"])) {
	$data = json_decode(str_replace("\\", "", $_POST['saveText']), true);

	for ($i = 0; $i < count($data); $i++) {
		echo updateTextbox($data[$i][0], $data[$i][1]);
	}
}
if (isset($_POST["saveScreens"])) {
	$data = json_decode(str_replace("\\", "", $_POST['saveScreens']), true);
	echo clearActiveScreens();
	for ($i = 0; $i < count($data); $i++) {
		echo updateActiveScreen($i+1, $data[$i]);
	}
}
if (isset($_POST["validform"])) {
	$form = json_decode(str_replace("\\", "", $_POST['validform']), true);

	$valid = 1;
	$validforms = getAllForms();
	while ($row = mysql_fetch_assoc($validforms)) {
		if ($row["name"] == $form) {
			$valid = 0;
		}
	}
	echo $valid;
}
?>