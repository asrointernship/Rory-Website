<?php
require_once("config.php");
require_once("./lang/lang.admin." . LANGUAGE_CODE . ".php");
require_once("functions.php");

$flag = (isset($_GET['flag'])) ? $_GET['flag'] : null;
$auth = auth();

if ( $auth == 2 ) {
	switch ( $flag ) {
		case "add":
			editUserForm("Add");
			break;
		case "edit":
			$id = (int) $_GET['id'];
			if (!empty($id))
				editUserForm("Edit", $id);
			else
				$lang['accesswarning'];
			break;
		case "insert":
			insertNewUser();
			break;
		case "update":
			updateExistingUser();
			break;
		case "delete":
			$id = (int) $_GET['id'];
			if (!empty($id))
				deleteUser($id);
			else
				$lang['accesswarning'];
			break;
		default:
			userList();
	}
} elseif ( $auth == 1 ) {
	switch ( $flag ) {
		case "changepw":
			changePW($flag);
			break;
		case "updatepw":
			updatePassword();
			changePW($flag);
			break;
		default:
			header("location:index.php");
	}
} else {
	echo $lang['accessdenied'];
}

/***************************************
******** user admin functions **********
***************************************/

function editUserForm($mode, $id="", $error="")
{
	global $lang;
	
	$editorstr = "<option value=\"1\">" . $lang['editoroption'] . "</option>\n";
	
	if ($mode=="Edit") {
		mysql_connect(DB_HOST, DB_USER, DB_PASS) or die(mysql_error());
		mysql_select_db(DB_NAME) or die(mysql_error());
		
		$sql = "SELECT username, password, fname, lname, userlevel ";
		$sql .= "FROM " . DB_TABLE_PREFIX . "users WHERE uid=" . $id;
		
		$result = mysql_query($sql) or die(mysql_error());
		$row = mysql_fetch_row($result);
		
		$username	= $row[0];
		$password	= $row[1];
		$fname		= $row[2];
		$lname		= $row[3];
		$userlevel	= $row[4];
		$admin      = ($userlevel == 2) ? "selected" : null;
		$header 	= $lang['edituser'];
		$formaction = "update";
		$unameinput = "<span class=\"edit_user_label\">" . $username . "</span><input type=\"hidden\" name=\"username\" value=\"" . $username . "\">\n";
		
		if ($username == $_SESSION['authdata']['login']) { $editorstr = ""; }
		
	} else {
		$username   = null;
		$admin      = null;
		$password   = (isset($_POST['pw'])) 
			? $_POST['pw'] : null;
		$fname 		= (isset($_POST['fname'])) 
			? $_POST['fname'] : null;
		$lname 		= (isset($_POST['lname'])) 
			? $_POST['lname'] : null;
		$userlevel 	= (isset($_POST['userlevel'])) 
			? $_POST['userlevel'] : null;
		$header 	= $lang['adduser'];
		$formaction = "insert";
		$unameinput = "<input type=\"text\" name=\"username\" size=\"29\" maxlength=\"20\" value=\"" . $username . "\">";
	}
?>
	<html><head>
	<title>phpEventCalendar:  <?php echo $mode?> Calendar User</title>
	<link rel="stylesheet" type="text/css" href="css/adminpgs.css">
	
	<script language="JavaScript">
	
		function validate(f) {
			var regex = /\W+/;
			var un = f.username.value;
			var pw = f.pw.value;
			
			var str = "";
			if (f.fname.value == "") { str += "\n<?php echo $lang['fnameblank']?>"; }
			if (f.lname.value == "") { str += "\n<?php echo $lang['lnameblank']?>"; }
			if (un == "") { str += "\n<?php echo $lang['unameblank']?>"; }
			if (un.length < 4) { str += "\n<?php echo $lang['unamelength']?>"; }
			if (regex.test(un)) { str += "\n<?php echo $lang['unameillegal']?>"; }
			if (pw == "") { str += "\n<?php echo $lang['passblank']?>"; }
			if (pw != f.pwconfirm.value) { str += "\n<?php echo $lang['passmatch']?>"; }
			if (pw.length < 4) { str += "\n<?php echo $lang['passlength']?>"; }
			if (regex.test(pw)) { str += "\n<?php echo $lang['passillegal']?>"; }
			
			if (str == "") {
				f.method = "post";
				f.action = "index.php?nav=useradmin&flag=<?php echo $formaction ?>";
				f.submit();
			} else {
				alert(str);
				return false;
			}
		}
	
	</script>
	</head><body>
	
<?php
	if ( !empty($error) ) {
		echo "<p><span class=\"bad_user_name\">" . $lang['unameinuse'] . "</span></p>";
	}
?>
	<form onSubmit="return validate(this);">
	<table cellpadding="2" cellspacing="2" border="0">
	<tr>
		<td colspan="2"><span class="edit_user_header"><?php echo $header?>:</span></td>
	</tr>
	<tr><td><img src="images/clear.gif" width="1" height="3"></td></tr>
	<tr>
		<td align="right"><span class="edit_user_label"><?php echo $lang['username']?>:</span></td>
		<td><?php echo $unameinput?></td>
	</tr>
	<tr>
		<td align="right"><span class="edit_user_label"><?php echo $lang['password']?>:</span></td>
		<td><input type="password" name="pw" size="29" maxlength="20" value="<?php echo $password?>"></td>
	</tr>
	<tr>
		<td align="right"><span class="edit_user_label"><?php echo $lang['pwconfirm']?>:</span></td>
		<td><input type="password" name="pwconfirm" size="29" maxlength="20" value="<?php echo $password?>"></td>
	</tr>
	<tr>
		<td align="right"><span class="edit_user_label"><?php echo $lang['userlevel']?>:</span></td>
		<td><select name="userlevel">
				<?php echo $editorstr?>
				<option value="2" <?php echo $admin ?>><?php echo $lang['adminoption'] ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right"><span class="edit_user_label"><?php echo $lang['fname']?>:</span></td>
		<td><input type="text" name="fname" size="29" maxlength="20" value="<?php echo $fname?>"></td>
	</tr>
	<tr>
		<td align="right"><span class="edit_user_label"><?php echo $lang['lname']?>:</span></td>
		<td><input disable type="text" name="lname" size="29" maxlength="30" value="<?php echo $lname?>"></td>
	</tr>

	<tr><td><img src="images/clear.gif" width="1" height="7"></td></tr>
	<tr>
		<td colspan="2" align="right"><input type="submit" value="<?php echo $mode?> User">
		&nbsp;	<input type="button" value="cancel" onClick="location.replace('index.php?nav=useradmin');">
		</td>
	</tr>
	</table>
	</form>

	</body></html>
<?php
}

function insertNewUser()
{
	$uname	= $_POST['username'];
	$pw 	= encryptPassword($_POST['pw']);
	$ulevel = $_POST['userlevel'];
	$fname 	= $_POST['fname'];
	$lname 	= $_POST['lname'];
	
	mysql_connect(DB_HOST, DB_USER, DB_PASS) or die(mysql_error());
	mysql_select_db(DB_NAME) or die(mysql_error());
	
	$sql = "SELECT * FROM " . DB_TABLE_PREFIX . "users WHERE username='$uname'";
	
	$result = mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_row($result);
	
	if ( is_array($row) ) {
		editUserForm("Add", "", $uname);
	} else {
		$sql = "INSERT INTO " . DB_TABLE_PREFIX . "users SET ";
		$sql .= "username='$uname', password='$pw', fname='$fname', lname='$lname', ";
		$sql .= "userlevel='$ulevel'";
		mysql_query($sql) or die(mysql_error());
		
		echo "Gebruiker toegevoegd klik <a href='index.php?nav=useradmin'>hier</a> om terug te gaan";
		//header("location:index.php?nav=useradmin");
	}
}

function updateExistingUser()
{
	$uname	= $_POST['username'];
	$pw 	= encryptPassword($_POST['pw']);
	$ulevel	= $_POST['userlevel'];
	$fname 	= $_POST['fname'];
	$lname	= $_POST['lname'];
	
	mysql_connect(DB_HOST, DB_USER, DB_PASS) or die(mysql_error());
	mysql_select_db(DB_NAME) or die(mysql_error());
	
	$sql = "UPDATE " . DB_TABLE_PREFIX . "users SET password='$pw', fname='$fname', ";
	$sql .= "lname='$lname', userlevel='$ulevel' WHERE username='$uname'";
	mysql_query($sql) or die(mysql_error());
	
	if ( $uname==$_SESSION['authdata']['login'] )
		$_SESSION['authdata']['password'] = $pw;
	
	echo "Gebruiker aangepast klik <a href='index.php?nav=useradmin'>hier</a> om terug te gaan";
	//header("location:index.php?nav=useradmin");
}

function deleteUser($id)
{
	global $authdata;
	
	if ($authdata['uid'] != $id) {
		mysql_connect(DB_HOST, DB_USER, DB_PASS) or die(mysql_error());
		mysql_select_db(DB_NAME) or die(mysql_error());
		
		$sql = "DELETE FROM " . DB_TABLE_PREFIX . "users WHERE uid='$id'";
		mysql_query($sql) or die(mysql_error());
	}
	
	echo "Gebruiker verwijdert klik <a href='index.php?nav=useradmin'>hier</a> om terug te gaan";
	//header("location:index.php?nav=useradmin");
}

function userList()
{
	global $lang;
?>
	<html><head><title>phpEventCalendar User List</title>
	<link rel="stylesheet" type="text/css" href="css/adminpgs.css">
	
	<script language="JavaScript">
		function deleteConfirm(user, uid) {
			var msg = "<?php echo $lang['deleteconf']?>: \"" + user + "\"?";
			
			if (user == "<?php echo $_SESSION['authdata']['login'] ?>") {
				alert("<?php echo $lang['deleteown']?>");
				return;
			} else if (confirm(msg)) {
				location.replace("index.php?nav=useradmin&flag=delete&id=" + uid);
			} else {
				return;
			}
		}
	</script>
	</head>
	
	<body>
	<table cellpadding="0" cellspacing="0" border="0" width="600">
	<tr>
		<td><span class="user_list_header"><?php echo $lang['ulistheader']?></span></td>
		<td align="right" valign="bottom"><span class="user_list_options">[ <a href="index.php?nav=useradmin&flag=add"><?php echo $lang['adduser']?></a> | <a href="index.php"><?php echo $lang['return']?></a> ]</span></td>
	</tr>
	<tr><td><img src="images/clear.gif" width="1" height="5"></td></tr>
	</table>
	
	<table cellpadding="0" cellspacing="0" border="0" width="600" bgcolor="#000000">
	<tr><td>

	<table cellspacing="1" cellpadding="3" border="0" width="100%">
	<tr bgcolor="#666666">
		<td><span class="user_table_col_label"><?php echo $lang['username']?></span></td>
		<td><span class="user_table_col_label"><?php echo $lang['name']?></span></td>
		<td><span class="user_table_col_label"><?php echo $lang['userlevel']?></span></td>
		<td><span class="user_table_col_label"><?php echo $lang['edit']?></span></td>
		<td><span class="user_table_col_label"><?php echo $lang['delete']?></span></td>
	</tr>

<?php
	mysql_connect(DB_HOST, DB_USER, DB_PASS) or die(mysql_error());
	mysql_select_db(DB_NAME) or die(mysql_error());
	
	$sql = "SELECT * FROM " . DB_TABLE_PREFIX . "users";
	$result = mysql_query($sql) or die(mysql_error());
	
	$bgcolor = "#ffffff";
	
	while( $row = mysql_fetch_array($result) ) {
		$userlevel = ($row[5] == 2) ? $lang['admin'] : $lang['editor'];
	
		echo "<tr bgcolor=\"$bgcolor\">\n";
		echo "	<td><span class=\"user_table_txt\">" . $row[1] . "</span></td>\n";
		echo "	<td><span class=\"user_table_txt\">" . $row[3] . " " . $row[4] . "</span></td>\n";
		//echo "	<td><span class=\"user_table_txt\">" . $row[6] . "</span></td>\n";
		echo "	<td><span class=\"user_table_txt\">" . $userlevel . "</span></td>\n";
		echo "	<td><span class=\"user_table_txt\"><a href=\"index.php?nav=useradmin&flag=edit&id=" . $row[0] . "\">" . $lang['edit'] . "</a></span></td>\n";
		echo "	<td><span class=\"user_table_txt\"><a href=\"#\" onClick=\"deleteConfirm('" . $row[1] . "', '" . $row[0] . "');\">" . $lang['delete'] . "</a></span></td>\n";
		echo "</tr>\n";
	
	if ( $bgcolor == "#ffffff" )
		$bgcolor = "#dddddd";
	else
		$bgcolor = "#ffffff";
	}

	echo "</table></td></tr></table>";
}

function changePW($flag)
{
	global $lang;
	
	$username = $_SESSION['authdata']['login'];
	$id = $_SESSION['authdata']['uid'];
?>
	<html><head>
	<title><?php echo $lang['changepw']?></title>
	<link rel="stylesheet" type="text/css" href="css/adminpgs.css">
	<script language="JavaScript">
		function validate(f) {
			var regex = /\W+/;
			var pw = f.pw.value;
			var str = "";
			if (pw == "") { str += "\n<?php echo $lang['passblank']?>"; }
			if (pw != f.pwconfirm.value) { str += "\n<?php echo $lang['passmatch']?>"; }
			if (pw.length < 4) { str += "\n<?php echo $lang['passlength']?>"; }
			if (regex.test(pw)) { str += "\n<?php echo $lang['passillegal']?>"; }
			
			if (str == "") {
				f.method = "post";
				f.action = "index.php?nav=useradmin&flag=updatepw";
				f.submit();
			} else {
				alert(str);
				return false;
			}
		}
	</script>
	</head></body>

<?php
	if ( $flag=="changepw" ) {
?>

	<form onSubmit="return validate(this);">
	<input type="hidden" name="un" value="<?php echo $username ?>">
	<table cellpadding="2" cellspacing="2" border="0">
	<tr>
		<td colspan="2"><span class="edit_user_header"><?php echo $lang['changepw']?></span></td>
	</tr>
	<tr><td><img src="images/clear.gif" width="1" height="3"></td></tr>
	<tr>
		<td align="right"><span class="edit_user_label"><?php echo $lang['username']?>:</span></td>
		<td><span class="edit_user_label"><?php echo $username?></span></td>
	</tr>
	<tr>
		<td align="right"><span class="edit_user_label"><?php echo $lang['password']?>:</span></td>
		<td><input type="password" name="pw" size="29" maxlength="25" value=""></td>
	</tr>
	<tr>
		<td align="right"><span class="edit_user_label"><?php echo $lang['pwconfirm']?>:</span></td>
		<td><input type="password" name="pwconfirm" size="29" maxlength="25" value=""></td>
	</tr>
	<tr><td><img src="images/clear.gif" width="1" height="7"></td></tr>
	<tr>
		<td colspan="2" align="right"><input type="submit" value="<?php echo $lang['changepw']?>">
		&nbsp;	<input type="button" value="<?php echo $lang['cancel']?>" onClick="location.replace('index.php');">
		</td>
	</tr>
	</table>
	</form>

<?php
	} elseif ( $flag=="updatepw" ) { 
?>
	
	<span class="edit_user_label"><?php echo $lang['pwchanged']?> &nbsp;"<?php echo $username?>"</span>
	<p>
	<span class="user_list_options">[ <a href="index.php"><?php echo $lang['return']?></a> ]</span>
	
<?php
	} else {
		echo $lang['accessdenied'] . "<p>";
		echo "<span class=\"user_list_options\">[ <a href=\"index.php\">" . $lang['return'] . "</a> ]</span>";
	}
?>
	</body>
	</html>
<?php
}

function updatePassword()
{
	$pw = (isset($_POST['pw'])) ? $_POST['pw'] : null;
	$pw = encryptPassword($pw);
	$id = (isset($_SESSION['authdata']['uid'])) 
		? $_SESSION['authdata']['uid'] : null;

	if (!empty($pw) && !empty($id)) {
		mysql_connect(DB_HOST, DB_USER, DB_PASS) or die(mysql_error());
		mysql_select_db(DB_NAME) or die(mysql_error());
	
		$sql = "
			UPDATE " . DB_TABLE_PREFIX . "users SET password='$pw' 
			WHERE uid='$id'";
		$result = mysql_query($sql) or die(mysql_error());
		
		$_SESSION['authdata']['password'] = $pw;
	}
}
?>
