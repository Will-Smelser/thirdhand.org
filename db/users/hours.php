<?php
include '../includes/base.php';
include_once '../Connections/YBDB.php';

$count = isset($_GET['count']) ? $_GET['count'] : 50;
$page = (isset($_GET['page']) && $_GET['page']*1 > 0) ? $_GET['page'] : 1;
$page--;
$lower = $page * $count;
$upper = $lower + $count;
$orderOn = (isset($_GET['order'])) ? $_GET['order'] : 'last_name';
$sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'ASC';

//validate
if(!in_array($orderOn,array('last_name','first_name','email','hours'))){
	$orderOn = 'last_name';
}
if($sort != 'ASC' && $sort != 'DESC'){
	$sort = 'ASC';
}

$sql = 'SELECT c.*, SUM(UNIX_TIMESTAMP(time_out) - UNIX_TIMESTAMP(time_in)) AS hours FROM contacts AS c ' .
	'LEFT JOIN shop_hours AS s ON c.contact_id = s.contact_id ' .
	'WHERE time_out <> "0000-00-00 00:00:00" ' .
	
	'GROUP BY contact_id ' .
	"ORDER BY $orderOn $sort " .

	"LIMIT $lower, $count";

$result = mysql_query($sql);
if(!$result){
	echo mysql_error();exit;
}

//get the total number of entries
$sql = 'SELECT count(*) AS total FROM contacts AS c ' .
	'LEFT JOIN shop_hours AS s ON c.contact_id = s.contact_id ' .
	'WHERE time_out <> "0000-00-00 00:00:00" ' .
	
	'GROUP BY c.contact_id ';

$result2 = mysql_query($sql);
if(!$result2){
	echo mysql_error();exit;
}
$total = mysql_num_rows($result2);
$pages = ceil(($total/$count));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/YBDB Template.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>THDB</title>
<!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable -->
<link href="css_yb_standard.css" rel="stylesheet" type="text/css" />
</head>

<body class="yb_standard">
<table border="1">
<?php 
ob_start();
?>
	<tr>
		<th>
		<a href="hours.php?order=last_name&sort=ASC">Last Name</a>
		<?php if($orderOn == 'last_name'){
			echo " (<a href='hours.php?order=$orderOn&sort=";
			echo ($sort=='ASC') ? 'DESC' : 'ASC';
			echo "'>$sort</a>)";
		}
		?>
		</th>
		<th>
		<a href="hours.php?order=first_name&sort=ASC">First Name</a>
		<?php if($orderOn == 'first_name'){
			echo " (<a href='hours.php?order=$orderOn&sort=";
			echo ($sort=='ASC') ? 'DESC' : 'ASC';
			echo "'>$sort</a>)";
		}
		?>
		</th>
		<th>
		<a href="hours.php?order=email&sort=ASC">Email</a>
		<?php if($orderOn == 'email'){
			echo " (<a href='hours.php?order=$orderOn&sort=";
			echo ($sort=='ASC') ? 'DESC' : 'ASC';
			echo "'>$sort</a>)";
		}
		?>
		</th>
		<th>
		<a href="hours.php?order=hours&sort=ASC">Hours</a>
		<?php if($orderOn == 'hours'){
			echo " (<a href='hours.php?order=$orderOn&sort=";
			echo ($sort=='ASC') ? 'DESC' : 'ASC';
			echo "'>$sort</a>)";
		}
		?>
		</th>
	</tr>
<?php 
	$header = ob_get_contents();
	ob_end_clean();

	$j = 0;
	
	while($row=mysql_fetch_assoc($result)){ ?>
	
	<?php if($j%20 == 0) echo $header; ?>
	<tr>
		<td><?php echo $row['last_name']; ?></td>
		<td><?php echo $row['first_name']; ?></td>
		<td><?php echo $row['email']; ?></td>
		<td><?php echo $row['hours']/(3600*24); ?></td>
	</tr>
<?php $j++;} ?>
</table>
<?php 
for($i=1;$i<$pages;$i++){
	if($i == $page+1) echo " [<b>";
	echo "&nbsp;&nbsp;<a href='hours.php?page=$i&count=$count&order=$orderOn&sort=$sort'>$i</a>&nbsp;&nbsp;";
	if($i == $page+1) echo "</b>] ";
	
}
?>
</body>