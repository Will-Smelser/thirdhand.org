<?php
session_start();

include_once $_SERVER['DOCUMENT_ROOT'].'/db/Connections/YBDB.php';

//setup the permission table
$perms = array();
if(!isset($_SESSION['permissions'])){
	$sql = 'SELECT g.*, p.id, p.path FROM groups AS g LEFT JOIN permissions AS p ON p.groups_id = g.groups_id ORDER BY g.name ASC';
	mysql_select_db($database_YBDB);
	$result = mysql_query($sql);
	
	while($row = mysql_fetch_assoc($result)){
		if(!key_exists($row['groups_id'], $perms)){
			$perms[$row['groups_id']] = array();
		}
		if(!empty($row['path'])) array_push($perms[$row['groups_id']],$row['path']);
	}
} else {
	$perms = $_SESSION['permissions'];
}

function checkPageProtected($page=''){
	global $perms;
	
	foreach($perms as $role=>$val){
		
		if(!empty($val) && in_array($page,$val)){
			return true;
		}
	}
	return false;
}
function checkUserPerms($page=''){
	global $perms;
	
	if(isset($_SESSION['User']['groups_id'])){
		$role = $_SESSION['User']['groups_id'];
		if(isset($perms[$role])){
			return (in_array($page,$perms[$role]));
		}
	}
	return false;
}

function isAdmin(){
	return (isset($_SESSION['User']['groups_id']) && $_SESSION['User']['groups_id'] == 1);
}
function isVolunteer(){
	return (isset($_SESSION['User']['groups_id']) && $_SESSION['User']['groups_id'] == 2);
}
function loggedInNav(){
	if(isset($_SESSION['User']['groups_id'])){
		$temp = "<a href='/db/users/process.php?action=logout'>Logout {$_SESSION['User']['email']}</a>";
		if(isAdmin()){
			$temp .= " | <a href='/db/users/index.php'>Admin</a>";
		}
		return $temp;
	} else {
		return "<a href='/db/users/login.php'>Login</a>";
	}
}
$page = $_SERVER['PHP_SELF'];

if(!preg_match('/login.php/i',$page) && checkPageProtected($page) !== false && !checkUserPerms($page)){
	$_SESSION['flash'] = 'Invalid Permissions';
	header('Location: /db/users/login.php');
	exit;
}

?>