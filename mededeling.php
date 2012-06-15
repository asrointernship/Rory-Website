<?php
if (!isset($auth) || (isset($auth) && ($auth != 1 && $auth != 2))) {
  header('Location: index.php');
}
if (isset($_POST["mededeling"]) && $_POST["mededeling"] != "" && !strstr($_POST["mededeling"], "Mededeling...")) {
  saveMededeling($_POST["mededeling"]);
  $warning = "Mededeling is opgeslagen.";
  $_POST["mededeling"] = "";
}
?>
  <div id="mededelingContent">
    <button type="button" class="bigButton" id="statusButton" onclick='zoTerug("zoterug");'>Zo Terug</button>
    <form action="<?php echo $_SERVER['PHP_SELF']."?nav=mededeling"; ?>" id="mededelingForm" method="POST">
      <textarea name="mededeling" id="mededeling" onclick="selectText()">Mededeling...</textarea>
      <input type="submit" name="submitMededeling" value="Opslaan" />
    </form>
    <table id="mededelingenList">
    <?php
    $s = getMededelingen();
    $tc = 0;
    while ($row = mysql_fetch_assoc($s)) {
      echo '<tr><td>';
      if ($row['content'].length > 100) {
        echo substr($row['content'],0,100)."...";
      } else {
        echo $row['content'];
      }
      echo "</td><td><button onclick=\"deleteMededeling(".$row['id'].",".$tc.")\">Verwijder</button></td></tr>";
      $tc ++;
    }
    ?>
    </table>
  </div>