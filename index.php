<?php
require_once("config.php");
require_once("./lang/lang." . LANGUAGE_CODE . ".php");
require_once("functions.php");
include_once("functionsOnthaal.php");

# testing whether var set necessary to suppress notices when E_NOTICES on
$month = 
	(isset($_GET['month'])) ? (int) $_GET['month'] : null;
$year =
	(isset($_GET['year'])) ? (int) $_GET['year'] : null;
	
# set month and year to present if month 
# and year not received from query string
$m = (!$month) ? date("n") : $month;
$y = (!$year)  ? date("Y") : $year;

$scrollarrows = scrollArrows($m, $y);
$auth 		  = auth();

if ($auth == 0) {
	include("login.php");
} else {
include("header.php");
$warning = "";
$page = "mededeling.php";
if (isset($_GET["nav"])) {
  if ($_GET["nav"] == "index") {
    $page = "./templates/" . TEMPLATE_NAME . ".php";
  } else {
    $page = $_GET["nav"].".php";
  }
} else {
	$page = "./templates/" . TEMPLATE_NAME . ".php";
}
include("$page");
include("footer.php");
}
?>
