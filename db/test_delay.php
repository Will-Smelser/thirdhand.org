<?php
require_once 'includes/base.php';?>
<?php 

function redirect(){
echo <<<EOD
<SCRIPT language="JavaScript"> 
<!--
 function getgoing()
  {
    top.location="http://www.ybdb.austinyellowbike.org/start_shop.php";
   }
 
 setTimeout('getgoing()',5000);
//--> 
</SCRIPT>
EOD;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<?php if ($_GET['goto'] == 'yes' ) 
	redirect();
?> 

 
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>
gone in 2 seconds 
</body>
</html>
