<?php 
include '../includes/base.php';

include_once '../Connections/YBDB.php';

$sql = 'SELECT g.*, p.id, p.path FROM groups AS g LEFT JOIN permissions AS p ON p.groups_id = g.groups_id ORDER BY g.name ASC';
mysql_select_db($database_YBDB);
$result = mysql_query($sql);

$perms = array();
$info = array();
while($row = mysql_fetch_assoc($result)){
	if(!key_exists($row['groups_id'], $perms)){
		$perms[$row['groups_id']] = array();
		$info[$row['groups_id']] = $row;
	}
	if(!empty($row['path'])) array_push($perms[$row['groups_id']],$row['path']);
}

function buildFileList($path, &$data){
	$normPath = str_replace($_SERVER['DOCUMENT_ROOT'],'',$path);
	$normPath = str_replace('\\','/',$normPath);
	foreach(scandir($path) as $file){
		if($file[0] != '.'){
			if(is_dir($path . '/' . $file)){
				if(!in_array($file,array('Connections','Templates','_mmServerScripts','includes'))) 
					buildFileList($path . '/' . $file, $data);
			} else if(preg_match('/(\.php)$/i',$file)){
				array_push($data,$normPath . '/' . $file);
			}
		}
	}
}

$files = array();
buildFileList($_SERVER['DOCUMENT_ROOT'] . '/db', $files);

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
	  
<h2>Manage Permissions</h2>

<?php 
	$curGroup = '';
	foreach($perms as $id=>$group){
		$first = true;
		
		foreach($files as $file){
			if($first){
				echo "<form action='process.php' method='POST' >\n";
				echo "<input type='hidden' value='permissions_update' name='action' />";
				echo "<input type='hidden' value='{$id}' name='group' />";
				echo "<h4>{$info[$id]['name']}</h4>\n";
				echo "<select multiple=\"multiple\" name=\"{$id}[]\" />\n";
			}
			$selected = (in_array($file,$group)) ? 'selected' : '';
			echo "<option value='{$file}' $selected>{$file}</option>\n";
			$first = false;
		}
		echo "</select>\n<br/>";
		echo "<input type='submit' value='Update' />";
		echo "</form>\n";
	}
?>
 
 </td>
 </tr>
 </table>
</body>
</html>