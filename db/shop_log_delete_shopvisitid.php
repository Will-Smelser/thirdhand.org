<?php
require_once 'includes/base.php';?>
<?php 
require_once('Connections/YBDB.php');
require_once('Connections/database_functions.php'); 

if($_GET['visit_id']>0){
	$visit_id = $_GET['visit_id'];
} else {
	$visit_id =-1;
}

if($_GET['shop_id']>0){
	$shop_id = $_GET['shop_id'];
} else {
	$shop_id =-1;
}

if(isset($_GET['confirm_delete'])){
	$confirm_delete = $_GET['confirm_delete'];
} else {
	$confirm_delete ='no';
}

$page_delete_yes = $_SERVER['PHP_SELF'] . "?" . htmlentities($_SERVER['QUERY_STRING']) . "&confirm=yes";

if ($shop_id <> 90){
	//returns to the shop_log?shop_id page
	$page_delete_no = PAGE_SHOP_LOG . "?" . htmlentities($_SERVER['QUERY_STRING']);
} else {
	//returns to the individual hours page
	$page_delete_no = INDIVIDUAL_HOURS_LOG . "?" . htmlentities($_SERVER['QUERY_STRING']);	
}

if ((visit_id <> -1) && (shop_id <> -1) && ($_GET['confirm'] == 'yes')){
	
	$insertSQL = "DELETE FROM shop_hours WHERE shop_visit_id = {$visit_id}";
	mysql_select_db($database_YBDB, $YBDB);
	$Result1 = mysql_query($insertSQL, $YBDB) or die(mysql_error());
	
	$pagegoto = $page_delete_no;
	header(sprintf("Location: %s", $pagegoto));  //browse back to shop
} // end if to delete

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Third Hand - Shop Login</title>
<link href="css_yb_standard.css" rel="stylesheet" type="text/css" />
</head>

<body class="yb_standard">
<p>Do you really want to delete this visit:</p>
<p>
<a href="<?php echo $page_delete_yes ?>">Yes</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="<?php echo $page_delete_no ?>">No</a></p>
</body>
</html>
