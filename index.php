<?php
include("functions.php");
include("header.php");
$warning = "";
$page = "mededeling.php";
if (isset($_GET["nav"])) {
  $page = $_GET["nav"].".php";
}
include("$page");
include("footer.php");
?>