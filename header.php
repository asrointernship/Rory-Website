<?
if (!isset($auth) || (isset($auth) && ($auth != 1 && $auth != 2))) {
  header('Location: index.php');
}
?>
<html>
<head>
  <link href="css/style.css" rel="stylesheet" type="text/css" />
  <?
  if (isset($_GET["nav"]) && $_GET["nav"] == "layout") {
      $sqlForms = getAllForms();

      echo '<script type="text/javascript"> var validForms = new Array();';
      $forms = array();
      while ($row = mysql_fetch_assoc($sqlForms)) {
        $forms[] = $row["name"];
        echo "validForms.push(\"".$row["name"]."\");";
      }
      echo 'function getValidForms() { return validForms;
        }';
      echo '</script>';
    }
  ?>
  <script src="js/json2.js" type="text/javascript"></script>
  <script src="js/jquery.js" type="text/javascript"></script>
  <? if (isset($_GET["nav"]) && $_GET["nav"] == "layout") { echo '<script type="text/javascript" src="js/editForm.js"></script>'; } ?>
  <script type="text/javascript" src="js/onthaalForm.js"></script>
  <?
    if (!isset($_GET["nav"]) || (isset($_GET["nav"]) && $_GET["nav"] == "index")) {
	  javaScript();
	  echo '<link rel="stylesheet" type="text/css" href="css/default.css">';
	}
  ?>
</head>
<body>
  <div id="navigation">
    <a href='index.php?nav=mededeling'>Index</a>
    <a href='index.php?nav=times'>Openingstijden</a>
    <a href='index.php?nav=index'>Kalendar</a>
	<?
	if ($auth == 2) {
      echo "<a href='index.php?nav=layout'>Layouts</a>";
	  echo "<a href='index.php?nav=useradmin'>Gebruikers</a>";
	}
	?>
	<a href='login.php?action=logout'>Uitloggen</a>
  </div>
  <hr />