<?php
require_once 'includes/base.php';?>
<?php 
require_once('Connections/YBDB.php'); 
require_once('Connections/database_functions.php');

if($_GET['shop_id']>0){
	$shop_id = $_GET['shop_id'];
} else {
	$shop_id = current_shop_by_ip();
}

switch ($_GET['error']) {
case 'new_error_message':	//this is a sample error message.  insert error case here		
   $error_message = '';
   break;
default:
   $error_message = 'Enter or Update Contact Information - </span><span class="yb_standard"> Third Hand uses this information solely to support the project and it is kept entirely private.  When we apply for grants it helps us to know a little bit about our shop users.  <p>Thanks for supporting The Third Hand Bicycle Cooperative. </p> </span><span class="yb_heading3red">';
   break;
}

$page_shop_log = PAGE_SHOP_LOG . "?shop_id=$shop_id";

if($_GET['contact_id'] == 'new_contact'){
	//adds contact is new_contact is selected
	$insertSQL = sprintf("INSERT INTO contacts (date_created) VALUES (%s)",
						   GetSQLValueString('current_time', "date"));
	mysql_select_db($database_YBDB, $YBDB);
	$Result1 = mysql_query($insertSQL, $YBDB) or die(mysql_error());
	
	mysql_select_db($database_YBDB, $YBDB);
	$query_Recordset2 = "SELECT MAX(contact_id) as new_contact_id FROM contacts;";
	$Recordset2 = mysql_query($query_Recordset2, $YBDB) or die(mysql_error());
	$row_Recordset2 = mysql_fetch_assoc($Recordset2);
	$totalRows_Recordset2 = mysql_num_rows($Recordset2);
	
	$contact_id = $row_Recordset2['new_contact_id'];
	$contact_id_entry = 'new_contact';
	mysql_free_result($Recordset2);
} elseif(isset($_GET['contact_id'])) {
	//else contact_id is assigned from passed value
	$contact_id = $_GET['contact_id'];
	$contact_id_entry = $_GET['contact_id'];
} else {
	$contact_id = -1;
	$contact_id_entry = -1;
}

$editFormAction = $_SERVER['PHP_SELF'] . "?contact_id={$contact_id}&shop_id={$shop_id}";

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	$groupid = (isAdmin()) ? $_post['groups_id'] : 3;
  $updateSQL = sprintf("UPDATE contacts SET first_name=%s, middle_initial=%s, last_name=%s, email=%s, DOB=%s, receive_newsletter=%s, phone=%s, address1=%s, address2=%s, city=%s, `state`=%s, zip=%s, pass=ENCODE(%s,'yblcatx'), groups_id=%s WHERE contact_id=%s",
                       GetSQLValueString($_POST['first_name'], "text"),
                       GetSQLValueString($_POST['middle_initial'], "text"),
                       GetSQLValueString($_POST['last_name'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
					   GetSQLValueString($_POST['DOB'], "date"),
					   GetSQLValueString($_POST['list_yes_no'], "int"),
                       GetSQLValueString($_POST['phone'], "text"),
                       GetSQLValueString($_POST['address1'], "text"),
                       GetSQLValueString($_POST['address2'], "text"),
                       GetSQLValueString($_POST['city'], "text"),
                       GetSQLValueString($_POST['state'], "text"),
                       GetSQLValueString($_POST['zip'], "text"),
					   GetSQLValueString($_POST['password'], "text"),
					   GetSQLValueString($groupid, "int"),
					   GetSQLValueString($_POST['contact_id'], "int")
					   );
	
  mysql_select_db($database_YBDB, $YBDB);
  $Result1 = mysql_query($updateSQL, $YBDB) or die(mysql_error());
  
  if ($_POST['contact_id_entry'] == 'new_contact'){
  	//navigate back to shop that it came from
	
	//if there is an email address submitted pass this to google groups signup.  Otherwise redirect to shop log.
	if ((strpos($_POST['email'], '@') > 0) && ($_POST['list_yes_no'] == 1)) {
		$email = $_POST['email'];
		$pagegoto = "contact_add_edit_confirmation_iframe.php" . "?shop_id={$shop_id}&new_user_id={$contact_id}&email=$email";
	} else { 
		$pagegoto = PAGE_SHOP_LOG . "?shop_id={$shop_id}&new_user_id={$contact_id}";
	}
		
	header(sprintf("Location: %s", $pagegoto));
  }
}

mysql_select_db($database_YBDB, $YBDB);
$query_Recordset1 = "SELECT *, DECODE(pass,'yblcatx') AS passdecode FROM contacts WHERE contact_id = $contact_id";
$Recordset1 = mysql_query($query_Recordset1, $YBDB) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/YBDB Template.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Third Hand</title>
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
<link href="css_yb_standard.css" rel="stylesheet" type="text/css" />
</head>


<body class="yb_standard">
<table align="center">
	<tr valign="top">
	  <td height="40" align="right"><a href="shop_log.php">Current Shop</a> | <a href="start_shop.php"> All Shops</a> | <a href="contact_add_edit_select.php">Edit Contact Info</a> | <a href="stats.php">Statistics</a>  |  |   <a href="http://www.thirdhand.org/" target="_blank">THBC Home</a> | <?php echo loggedInNav(); ?></td>
	</tr>
	<tr>
	  <td><!-- InstanceBeginEditable name="Body" -->
<table>
	<tr valign="top">
		<td width="760" align="left"><span class="yb_heading3red"><?php echo $error_message; ?></span></td>
	</tr>
	<tr>
		<td align="center">
	
		<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
		  <table width="500" border="1" cellpadding="1" cellspacing="0" bordercolor="#CCCCCC">
			<tr valign="baseline">
			  <td width="200" align="right" nowrap>Contact_id:</td>
			  <td><?php echo $row_Recordset1['contact_id']; ?></td>
			  </tr>
			<tr valign="baseline">
			  <td nowrap align="right">First_name:</td>
			  <td><input type="text" name="first_name" value="<?php echo $row_Recordset1['first_name']; ?>" size="32"></td>
			  </tr>
			<tr valign="baseline">
			  <td nowrap align="right">Middle_initial:</td>
			  <td><input name="middle_initial" type="text" value="<?php echo $row_Recordset1['middle_initial']; ?>" size="1" maxlength="1"></td>
			  </tr>
			<tr valign="baseline">
			  <td nowrap align="right">Last_name:</td>
			  <td><input type="text" name="last_name" value="<?php echo $row_Recordset1['last_name']; ?>" size="32"></td>
			  </tr>
			<tr valign="baseline">
			  <td nowrap align="right">Email:</td>
			  <td><input type="text" name="email" value="<?php echo $row_Recordset1['email']; ?>" size="32"></td>
			  </tr>
			<tr valign="baseline">
			  <td nowrap align="right">Date of Birth: </td>
			  <td><input type="text" name="DOB" value="<?php echo $row_Recordset1['DOB']; ?>" size="10" /> 
			    (YYYY-MM-DD) </td>
			  </tr>
			
			<tr valign="baseline">
			  <td nowrap align="right">Receive THBC Newsletter?:</td>
			  <td><?php list_yes_no(list_yes_no,$row_Recordset1['receive_newsletter']); ?></td>
			  </tr>
			<tr valign="baseline">
			  <td nowrap align="right">Phone:</td>
			  <td><input type="text" name="phone" value="<?php echo $row_Recordset1['phone']; ?>" size="32"></td>
			  </tr>
			<tr valign="baseline">
			  <td nowrap align="right">Address1:</td>
			  <td><input type="text" name="address1" value="<?php echo $row_Recordset1['address1']; ?>" size="32"></td>
			  </tr>
			<tr valign="baseline">
			  <td nowrap align="right">Address2:</td>
			  <td><input type="text" name="address2" value="<?php echo $row_Recordset1['address2']; ?>" size="32"></td>
			  </tr>
			<tr valign="baseline">
			  <td nowrap align="right">City:</td>
			  <td><input type="text" name="city" value="<?php echo $row_Recordset1['city']; ?>" size="32"></td>
			  </tr>
			<tr valign="baseline">
			  <td nowrap align="right">State:</td>
			  <td><input name="state" type="text" value="<?php echo $row_Recordset1['state']; ?>" size="2" maxlength="2"></td>
			  </tr>
			<tr valign="baseline">
			  <td nowrap align="right">ZIP:</td>
			  <td><input type="text" name="zip" value="<?php echo $row_Recordset1['zip']; ?>" size="5"></td>
			  </tr>
			<tr valign="baseline">
			  <td nowrap align="right">New Password:</td>
			  <td><input name="password" type="password" id="password" value="<?php echo $row_Recordset1['passdecode']; ?>" size="32">
			    <br />
			    Your password keeps others from viewing your personal information. </td>
			  </tr>
			
		     <?php 
		     //admin stuff
		     if(isAdmin()){
		     	?>
		     <tr>
		     	<td nowrap align="right">User Group</td><td>
		     	<select name="groups_id">
		     	<?php 
		     	
		     		foreach($_SESSION['groups'] as $id=>$name){
		     			$selected = ($row_Recordset1['groups_id'] == $id) ? 'selected' : '';
		     			echo "<option value='$id' $selected>$name</option>";
		     		}
		     	?>
		     	</select>
		     	</td>
		     </tr>	
		     <?php
		     } else {
		     	echo "<input type='hidden' name='groups_id' value='3' />";
		     }
		     ?>
		     <tr valign="baseline">
			  <td nowrap align="right">&nbsp;</td>
			  <td><input type="submit" value="Update Contact Info"></td>
		      </tr>
		  </table>
		  <input type="hidden" name="MM_insert" value="form1">
		  <input type="hidden" name="contact_id" value="<?php echo $row_Recordset1['contact_id']; ?>">
		  <input type="hidden" name="contact_id_entry" value="<?php echo $contact_id_entry; ?>">
		</form>	  </td>
	</tr>
</table>

<p>&nbsp;</p>
<!-- InstanceEndEditable --></td>
	</tr>
</table>

</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($Recordset1);
?>
