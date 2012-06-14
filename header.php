<?php
# Calendar + Auth
require("calendar/config.php");
require("calendar/functions.php");
# testing whether var set necessary to suppress notices when E_NOTICES on
$month = 
  (isset($_GET['month'])) ? (int) $_GET['month'] : null;
$year =
  (isset($_GET['year'])) ? (int) $_GET['year'] : null;

# set month and year to present if month 
# and year not received from query string
date_default_timezone_set("Europe/Brussels");
$m = (!$month) ? date("n") : $month;
$y = (!$year)  ? date("Y") : $year;

$scrollarrows = scrollArrows($m, $y);
$auth       = auth();
?>
<html>
<head>
  <link href="style.css" rel="stylesheet" type="text/css" />
  <script src="json2.js" type="text/javascript"></script>
  <script src="jquery.js" type="text/javascript"></script>
  <? if (isset($_GET["nav"]) && $_GET["nav"] == "layout") { echo '<script type="text/javascript" src="editForm.js"></script>'; } ?>
  <script type="text/javascript" src="onthaalForm.js"></script>
</head>
<body>
  <div id="navigation">
    <a href='<?php echo $_SERVER['PHP_SELF']."?nav=mededeling"; ?>'>Index</a>
    <a href='<?php echo $_SERVER['PHP_SELF']."?nav=times"; ?>'>Openingstijden</a>
    <a href='<?php echo $_SERVER['PHP_SELF']."?nav=calendar"; ?>'>Kalendar</a>
    <a href='<?php echo $_SERVER['PHP_SELF']."?nav=layout"; ?>'>Layouts</a>
  </div>
  <hr />