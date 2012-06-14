<?php
require("calendar/config.php");
require("calendar/lang/lang.admin." . LANGUAGE_CODE . ".php");
require("calendar/functions.php");

$action = 'show_form';

# it a pain to have to check before using, but php complains when E_NOTICES on 
if (isset($_GET['action'])) {
	$action	= $_GET['action'];
}

# get month and year to preserve state
$m 		= (int) $_GET['month'];
$y 		= (int) $_GET['year'];

# if login, and auth returns true, then refresh month view, and close window
if ($action == "login" 
	&& auth($_POST['username'], $_POST['password']) ) {
	echo "<script language=\"JavaScript\">";
	echo "opener.location = \"index.php?month=$m&year=$y\";";
	echo "window.setTimeout('window.close()', 500);";
	echo "</script>";
} elseif ($action == "logout") {
	session_start();
	session_destroy();
	header ("Location: index.php?month=$m&year=$y");
} else {
?>
	<html>
	<head>
	<script language="JavaScript">
	function firstFocus()
	{
		if (document.forms.length > 0) {
			var TForm = document.forms[0];
			for (i=0;i<TForm.length;i++) {
				if ((TForm.elements[i].type=="text")|| 
				    (TForm.elements[i].type=="textarea")|| 
					(TForm.elements[i].type.toString().charAt(0)=="s")) {
					document.forms[0].elements[i].focus();
					break;
				}
			}
		}
	}
	</script>
	<title><?php echo $lang['logintitle']?></title>
	<link rel="stylesheet" type="text/css" href="css/adminpgs.css">
	</head>
	<body onLoad="firstFocus()">
<?php 
	if( isset( $_POST['username'] ) ) {
		echo "<span class=\"login_auth_fail\">" . $lang['wronglogin'] . "</span><p>\n";
	}
?>
	<span class="login_header"><?php echo $lang['loginheader']?></span>
	<br><img src="images/clear.gif" width="1" height="5"><br>
	
	<table>
	<form action="<?php echo $HTTP_SERVER_VARS['PHP_SELF'] ?>?action=login&month=<?php echo $m ?>&year=<?php echo $y ?>" method="post">
			<tr>
				<td nowrap valign="top" align="right" nowrap>
				<span class="login_label"><?php echo $lang['username']?></span></td>
				<td><input type="text" name="username" size="30" maxlength="30"></td>
			</tr>
			<tr>
				<td nowrap valign="top" align="right" nowrap>
				<span class="login_label"><?php echo $lang['password']?></span></td>
				<td><input type="password" name="password" size="30" maxlength="30"></td>
			</tr>
			<tr><td colspan="2" align="right"><input type="submit" value="<?php echo $lang['login']?>"><td><tr>
	</form>
	</table>

	</body></html>
<?php
}
?>
