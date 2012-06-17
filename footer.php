<?
if (!isset($auth) || (isset($auth) && ($auth != 1 && $auth != 2))) {
  header('Location: index.php');
}
?>

</body>
</html>