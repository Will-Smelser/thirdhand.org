<?php 
include '../includes/base.php';

include_once '../Connections/YBDB.php';
include_once '../Connections/database_functions.php';

$sql = "SELECT c . * , COUNT( sh.contact_id ) AS vh_visits, " .
	"ROUND( SUM( HOUR( SUBTIME( TIME( time_out ) , TIME( time_in ) ) ) + MINUTE( SUBTIME( TIME( time_out ) , TIME( time_in ) ) ) /60 ) ) " .
		"AS vh_hours " .
	"FROM shop_hours AS sh " .
	"LEFT JOIN contacts AS c ON c.contact_id = sh.contact_id " .
	"GROUP BY contact_id";


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/YBDB Template.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>THDB</title>
<!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable -->
<link href="../css_yb_standard.css" rel="stylesheet" type="text/css" />

<script src="http://code.jquery.com/jquery-1.6.1.min.js" ></script>
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
	  
	  	<h1>Remove Volunteer Hours</h1>
	  	<p>
	  		<span style="display:inline-block;width:120px">Choose User:</span>
	  		<?php echo list_contacts('contact'); ?></p>
	  	<div id="error" style="discplay:none;color:red;"></div>
	  	<div id="remove" style="display:none">
	  		<div>
	  		<form method="POST" action="process.php">
	  		<input type="hidden" name="action" value="hours_remove_update" />
	  		<input id="contact" name="contact" type="hidden" value="" />
	  			<span style="display:inline-block;width:120px">Update Hours:</span>
	  			<span id="loading" style="display:none">Loading...</span> 
	  			<input type="text" id="hours" name="hours" /><br/>
	  			<p>
	  				<span style="display:inline-block;width:120px">&nbsp;</span><input type="submit" value="- Update - " />
	  			</p>
	  		</form>
	  	</div>
	   </td>
 </tr>
 </table>
 
 <script>
 var hours = 0;
 $(document).ready(function(){
	$('select:first').change(function(){
		$('#loading').show();
		$('#remove').hide();
		$('#error').hide();
		var id = $(this).val();
		$('#contact').val(id);
		$.getJSON('process.php?action=get_users_hours_remove&contact_id='+id,function(data){

			var hours = 0;
			if(data.hours != null && data.hours > 0){
				hours = Math.floor(data.hours*100)/100;
			}

			
			$('#hours').val(hours);
			$('#unit').show();
			$('#loading').hide();
			$('#remove').show();
		});
	});

	$('select:first').trigger('change');
 });
 </script>
</body>
</html>