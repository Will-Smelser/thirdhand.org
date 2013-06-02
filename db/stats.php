<?php
require_once 'includes/base.php';?>
<?php 
require_once('Connections/YBDB.php');
require_once('Connections/database_functions.php'); 

$page_shop_log = PAGE_SHOP_LOG;

//?shop_id=2
if($_GET['shop_id']>0){
	$shop_id = $_GET['shop_id'];
} else {
	$shop_id =0;}
	
switch ($_GET['error']) {
case 'no_shop':
   $error_message = 'ERROR: A Shop at this location for today does not exist: Start New Shop';
   break;
case 'new_error_message':	//this is a sample error message.  insert error case here		
   $error_message = '';
   break;
default:
   $error_message = 'Start a New Shop OR View and Existing Shop';
   break;
}


//shop_date
if($_GET['shop_date']>0){
	$shop_date = $_GET['shop_date'];
} else {
	$shop_date =current_date();}	
	
//dayname
if($_GET['shop_dayname']=='alldays'){
	$shop_dayname = '';
} elseif(isset($_GET['shop_dayname'])) {
	$shop_dayname = "AND DAYNAME(date) = '" . $_GET['shop_dayname'] . "'";
} else {
	$shop_dayname = '';
}	

//record_count
if($_GET['record_count']>0){
	$record_count = $_GET['record_count'];
} else {
	$record_count = 10;}	

$ctrl_shoplocation = "ctrl_shoplocation";
$ctrl_shoptype = "ctrl_shoptype";

$editFormAction = $_SERVER['PHP_SELF'] . "?shop_date=$shop_date&shop_id=$shop_id";
$editFormAction_no_shopid = $_SERVER['PHP_SELF'] . "?shop_date=$shop_date";

mysql_select_db($database_YBDB, $YBDB);
$query_Recordset1 = "SELECT shops.shop_id, date, DAYNAME(date) as day ,shop_location, shop_type, ip_address, COUNT(shop_visit_id) AS num_visitors, ROUND(SUM(HOUR(SUBTIME( TIME(time_out), TIME(time_in))) + MINUTE(SUBTIME( TIME(time_out), TIME(time_in)))/60)) AS total_hours FROM shops LEFT JOIN shop_hours ON shops.shop_id = shop_hours.shop_id WHERE date <= '{$shop_date}' {$shop_dayname} GROUP BY shop_id ORDER BY date DESC LIMIT  0, $record_count;";
$Recordset1 = mysql_query($query_Recordset1, $YBDB) or die(mysql_error());
//$row_Recordset1 = mysql_fetch_assoc($Recordset1);   //Wait to fetch until do loop
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

// action on submit
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_new")) {
  $insertSQL = sprintf("INSERT INTO shops (shop_location, shop_type, date, ip_address) VALUES (%s, %s, %s, %s)", 
  					GetSQLValueString($_POST['ctrl_shoplocation'], "text"), 
					GetSQLValueString($_POST['ctrl_shoptype'], "text"), 
					GetSQLValueString($_POST['ctrl_date'], "date"), 
					GetSQLValueString($_SERVER['REMOTE_ADDR'], "text"));

  mysql_select_db($database_YBDB, $YBDB);
  $Result1 = mysql_query($insertSQL, $YBDB) or die(mysql_error());
  
  //determines the shop_id just added to the database
  mysql_select_db($database_YBDB, $YBDB);
  $query_Recordset2 = "SELECT MAX(shop_id) AS shop_id FROM shops;";
  $Recordset2 = mysql_query($query_Recordset2, $YBDB) or die(mysql_error());
  $row_Recordset2 = mysql_fetch_assoc($Recordset2);
  $totalRows_Recordset2 = mysql_num_rows($Recordset2);
  $shop_id = $row_Recordset2["shop_id"];

  //the added shop_id is passed as a variable to the shop page
  $insertGoTo = "{$page_shop_log}?shop_id=" . $shop_id;
  mysql_free_result($Recordset2);
  header(sprintf("Location: %s", $insertGoTo));
  
  //header(sprintf("Location: %s", "index.html"));
}

//Update Record     isset($_POST["MM_update"])
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "FormEdit")) {
  $updateSQL = sprintf("UPDATE shops SET date=%s, shop_location=%s, shop_type=%s WHERE shop_id=%s",
                       GetSQLValueString($_POST['date'], "date"),
                       GetSQLValueString($_POST['shop_location'], "text"),
					   GetSQLValueString($_POST['shop_type'], "text"),
                       GetSQLValueString($_POST['shop_id'], "int"));
					   //"2006-10-12 18:15:00"

  mysql_select_db($database_YBDB, $YBDB);
  $Result1 = mysql_query($updateSQL, $YBDB) or die(mysql_error());
  
  header(sprintf("Location: %s",$editFormAction_no_shopid ));   //$editFormAction
}

//Change Date     isset($_POST["MM_update"])
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "ChangeDate")) {
  $editFormAction = $_SERVER['PHP_SELF'] . "?shop_date={$_POST['shop_date']}&shop_dayname={$_POST['dayname']}&record_count={$_POST['record_count']}";
  header(sprintf("Location: %s",$editFormAction ));   //$editFormAction
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/YBDB Template.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>THDB</title>
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
<link href="css_yb_standard.css" rel="stylesheet" type="text/css" />
</head>


<body class="yb_standard">
<table align="center">
	<tr valign="top">
	  <td height="40" align="right"><a href="shop_log.php">Current Shop</a> | <a href="start_shop.php"> All Shops</a> | <a href="contact_add_edit_select.php">Edit Contact Info</a> | <a href="stats.php">Statistics</a> | <a href="http://www.thirdhand.org/" target="_blank">THBC Home</a> | <?php echo loggedInNav(); ?></td>
	</tr>
	<tr>
	  <td><!-- InstanceBeginEditable name="Body" -->
	    <p class="yb_heading3red">Statistics</p>
	    <ul>
          <li><a href="stats_userhours.php">Hours by User</a> </li>
	      <li><a href="stats_usersbyweek.php">New and Total Users by Week</a> </li>
	      <li><a href="stats_usersbydayweek.php">New and Total Users by Day/Week</a></li>
      </ul>
	  <!-- InstanceEndEditable --></td>
	</tr>
</table>

</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($Recordset1);
?>
