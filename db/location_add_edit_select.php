<?php
require_once 'includes/base.php';?>
<?php 
require_once('Connections/YBDB.php'); 
require_once('Connections/database_functions.php');

$page_edit_contact = PAGE_EDIT_LOCATION;

if($_GET['contact_id']>0){
	$contact_id = $_GET['contact_id'];
} else {
	$contact_id =-1;}
	
switch ($_GET['error']) {
case 'incorrect_password':
   $error_message = 'ERROR: Password for user was incorrect.  Talk to a coordinator if you cannot remember it.';
   break;
case 'new_error_message':	//this is a sample error message.  insert error case here		
   $error_message = '';
   break;
default:
   $error_message = 'Select a Location and click Submit to edit';
   break;
}

mysql_select_db($database_YBDB, $YBDB);
$query_Recordset1 = "SELECT * , CONCAT(contacts.last_name, ', ', contacts.first_name, ' ',contacts.middle_initial) AS full_name FROM contacts;";
$Recordset1 = mysql_query($query_Recordset1, $YBDB) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

$editFormAction = $_SERVER['PHP_SELF'];

if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  	  
	if($_POST['contact_id'] == 'new_contact'){
		//if contact is new do not check password and pass to contact form
		$insertGoTo = PAGE_EDIT_LOCATION . "?contact_id=new_contact";
		header(sprintf("Location: %s", $insertGoTo));
	} else {	
		$insertGoTo = PAGE_EDIT_LOCATION . "?contact_id=" . $_POST['contact_id'];
		header(sprintf("Location: %s", $insertGoTo));
	  } //end else
} 

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/YBDB Template.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>YBDB</title>
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
<link href="css_yb_standard.css" rel="stylesheet" type="text/css" />
</head>


<body class="yb_standard">
<table align="center">
	<tr valign="top">
	  <td height="40" align="right"><a href="shop_log.php">Current Shop</a> | <a href="start_shop.php"> All Shops</a> | <a href="contact_add_edit_select.php">Edit Contact Info</a> | <a href="stats.php">Statistics</a>  | <a href="transaction_log.php">Transaction Log</a> |   <a href="http://www.austinyellowbike.org/" target="_blank">YBP Home</a></td>
	</tr>
	<tr>
	  <td><!-- InstanceBeginEditable name="Body" -->
<table>
	<tr valign="top">
		<td width="760" align="left"><span class="yb_heading3red"><?php echo $error_message;?></span> </td>
	</tr>
	<tr>
		<td>
<form id="form1" name="form1" method="post" action="">

  <table width="760" border="1" cellpadding="1" cellspacing="0">
    <tr>
      <td><div align="right"><strong>Contact:&nbsp;</strong></div></td>
      <td><?php list_donation_locations_edit_add(contact_id, $contact_id);?></td>
      <td>Select Contact to Edit</td>
    </tr>
    
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="Submit" value="Submit" /></td>
      <td>&nbsp;</td>
    </tr>
  </table>  
  <input type="hidden" name="MM_insert" value="form1">
</form>		</td>
	</tr> 
</table>
<!-- InstanceEndEditable --></td>
	</tr>
</table>

</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($Recordset1);
?>
