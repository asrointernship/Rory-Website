<?php
include("functions.php");
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
?>