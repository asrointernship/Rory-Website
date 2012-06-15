<?
if (!isset($auth) || (isset($auth) && ($auth != 1 && $auth != 2))) {
  header('Location: index.php');
}
?>
<div id = "timesContent">
<?php
if (isset($_POST["MaandagOU"])) {
  deleteOpeningstijden();
  $tijden["Maandag"] = array($_POST["MaandagOU"],$_POST["MaandagOM"],$_POST["MaandagGU"],$_POST["MaandagGM"]);
  $tijden["Dinsdag"] = array($_POST["DinsdagOU"],$_POST["DinsdagOM"],$_POST["DinsdagGU"],$_POST["DinsdagGM"]);
  $tijden["Woensdag"] = array($_POST["WoensdagOU"],$_POST["WoensdagOM"],$_POST["WoensdagGU"],$_POST["WoensdagGM"]);
  $tijden["Donderdag"] = array($_POST["DonderdagOU"],$_POST["DonderdagOM"],$_POST["DonderdagGU"],$_POST["DonderdagGM"]);
  $tijden["Vrijdag"] = array($_POST["VrijdagOU"],$_POST["VrijdagOM"],$_POST["VrijdagGU"],$_POST["VrijdagGM"]);
  $tijden["Zaterdag"] = array($_POST["ZaterdagOU"],$_POST["ZaterdagOM"],$_POST["ZaterdagGU"],$_POST["ZaterdagGM"]);
  $tijden["Zondag"] = array($_POST["ZondagOU"],$_POST["ZondagOM"],$_POST["ZondagGU"],$_POST["ZondagGM"]);
  fillOpeningstijden($tijden);
}
?>
<form action="<?php echo $_SERVER['PHP_SELF']."?nav=times"; ?>" method="POST">
<?php
$s = getOpeningstijden();
while ($row = mysql_fetch_assoc($s)) {
  /*$row['dag']
  $row['openu']
  $row['openm']
  $row['geslotenu']
  $row[geslotenu]
  */
  echo '<p>'.$row['dag'].': ';
  echo '<input type="text" name="'.$row['dag'].'OU" size="2" maxLength="2" value="'.$row['openu'].'"/>';
  echo '<input type="text" name="'.$row['dag'].'OM" size="2" maxLength="2" value="'.$row['openm'].'"/>';
  echo '  -  ';
  echo '<input type="text" name="'.$row['dag'].'GU" size="2" maxLength="2" value="'.$row['geslotenu'].'"/>';
  echo '<input type="text" name="'.$row['dag'].'GM" size="2" maxLength="2" value="'.$row['geslotenm'].'"/>';
}
?>
<input type="submit" value="Opslaan" />
</form>
</div>