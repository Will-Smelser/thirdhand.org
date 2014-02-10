<?php 
include '../includes/base.php';
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/YBDB Template.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>THDB</title>
<!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable -->
<link href="../css_yb_standard.css" rel="stylesheet" type="text/css" />
</head>


<body class="yb_standard">
<table align="center">
	<tr valign="top">
	  <td height="40" align="right"><a href="../shop_log.php">Current Shop</a> | <a href="../start_shop.php"> All Shops</a> | <a href="../contact_add_edit_select.php">Edit Contact Info</a> | <a href="../stats.php">Statistics</a> | <a href="http://www.thirdhand.org/" target="_blank">THBC Home</a> | <?php echo loggedInNav(); ?></td>
	</tr>
	<tr>
	  <td><!-- InstanceBeginEditable name="Body" -->
	  	<?php if(isset($_SESSION['flash'])){ ?>
		<p style="border:solid black 2px;background-color:#333;color:#FFF;padding:5px;"><?php echo $_SESSION['flash']; ?></p>
		<?php 
				unset($_SESSION['flash']);
			} 
		?>
	  	<ul>
	  		<li><a href='login.php' >Login / Logout</a></li>
	  		<li><a href='permissions.php'>Update Permissions</a></li>
	  		<li><a href='hours_remove.php'>Remove Hours</a></li>
	  		<li><a href='hours_update_remove.php'>Refund Hours</a></li>
	  		<li><a href='hours_add.php'>Add Hours</a></li>
	  		<li><a href='process.php?action=fix_hours'>Logout All</a></li>
	  	</ul>
	  	
	  
	   </td>
 </tr>
 </table>
</body>
</html>