<?php

if(empty($_POST)){
	$_POST = $_GET;
}

if(!empty($_POST)){
	include_once '../Connections/YBDB.php';
	mysql_select_db($database_YBDB);
	
	switch($_POST['action']){
		case 'delete_history_entry':
			//protects
			include '../includes/base.php';
			
			$id = (int) $_POST['shop_visit_id'];
			$sql = 'DELETE FROM shop_hours WHERE shop_visit_id = ' . $id . ' LIMIT 1';
			
			$result = mysql_query($sql);
			if(!$result){echo mysql_error();exit;}
			
			session_start();
			$_SESSION['flash'] = 'Removed Entry';
			header('Location: ../individual_history_log.php');
		exit;
		case 'login':
			$password = $_POST['password'];
			$email = mysql_real_escape_string($_POST['email']);
			
			$sql="PREPARE stmnt FROM \"SELECT *, DECODE(pass,'yblcatx') AS pass2 FROM contacts WHERE email = ?\"";
			
			$result = mysql_query($sql);
			if(!$result){echo mysql_error();exit;}
			
			$result = mysql_query('SET @a = "'.$email.'";');
			if(!$result){echo mysql_error();exit;}
			
			$result = mysql_query('EXECUTE stmnt USING @a;');
			if(!$result){echo mysql_error();exit;}
			
			$row = mysql_fetch_assoc($result);
			
			session_start();
			if(empty($row) || $row['pass2'] != $password){
				$_SESSION['flash'] == 'Invalid Username or Password';
			} else {
				$_SESSION['User'] = $row;
				unset($_SESSION['User']['pass2']);
				$_SESSION['flash'] = 'Successful Login';
				
				//lets get the groups, needed for any logged in user
				$sql = 'SELECT * FROM groups';
				$result = mysql_query($sql);
				if(!$result){echo mysql_error();exit;}
				
				
				$_SESSION['groups'] = array();
				while($row = mysql_fetch_assoc($result)){
					$_SESSION['groups'][$row['groups_id']] = $row['name'];
				}	
			}
						
			header('Location: login.php');
			exit;
		case 'logout':
			session_start();
			unset($_SESSION['User']);
			$_SESSION['flash'] = 'Logged Out';
			header('Location: login.php');
			exit;
		case 'permissions_update':
			session_start();
			
			$groupId = $_POST['group'];
			
			unset($_POST['action']);
			unset($_POST['group']);
			
			reset($_POST);
			$group = current($_POST);
			
			
			//remove the current entries
			mysql_query("DELETE FROM permissions WHERE groups_id = $groupId");
			
			if(empty($_POST)){
				$_SESSION['flash'] = 'Updated Permissions';
				header('Location: permissions.php');
				exit;
			}
			
			//add the new entries
			$sql = "PREPARE stmnt FROM \"INSERT INTO permissions (`groups_id`,`path`) VALUES (?,?)\"";
			
			$result = mysql_query($sql);
			if(!$result){echo mysql_error();exit;}
			
			if(!mysql_query("SET @a = $groupId")){echo mysql_error();exit;}
			
			foreach($group as $path){
				$temp = mysql_real_escape_string($path);
				mysql_query("SET @b = \"$temp\"");
				
				$result = mysql_query('EXECUTE stmnt USING @a, @b;');
				if(!$result){echo mysql_error();exit;}
			}
			
			unset($_SESSION['permissions']);
			$_SESSION['flash'] = 'Updated Permissions';
			header('Location: permissions.php');
			exit;
		case 'get_user_hours':
			$id = (int) $_GET['contact_id'];
			$sql = "SELECT c . * , COUNT( sh.contact_id ) AS vh_visits, " .
				"TRUNCATE(SUM( UNIX_TIMESTAMP( time_out ) - UNIX_TIMESTAMP( time_in ) )/3600,2) " .
					"AS vh_hours " .
				"FROM shop_hours AS sh " .
				"LEFT JOIN contacts AS c ON c.contact_id = sh.contact_id " .
				"WHERE c.contact_id = $id && shop_user_role='Volunteer'";
			
			$result = mysql_query($sql);
			
			if(!$result){echo "{result:false}";exit;}
			
			$row = mysql_fetch_assoc($result);
			echo json_encode($row);		
			exit;
		case 'hours_remove':
			$contact = $_POST['contact'];
			$hours = $_POST['hours'] * 1.00;
			
			session_start();
			if($hours == 0){
				$_SESSION['flash'] = "Updated Volunteer Hours (Removed $hours hrs)";
				header('Location: hours_remove.php');
			}
			
			//2011-12-22 00:00:00
			$format = 'Y-m-d H:i:00';
			$time_in = time();
			$time_out = $time_in - ($hours * 3600);
			$time_in = date($format,$time_in);
			$time_out = date($format, $time_out);
			
			$shop = 0;
			
			$sql = "PREPARE stmnt FROM \"INSERT INTO shop_hours (contact_id, shop_id, shop_user_role, time_in, time_out, comment) VALUES (?,?,?,?,?,?)\"";
			$result = mysql_query($sql);
			if(!$result){echo mysql_error();exit;}
			
			if(!mysql_query("SET @a = $contact")){echo '1-'.mysql_error();exit;}
			if(!mysql_query("SET @b = $shop")){echo '2-'.mysql_error();exit;}
			if(!mysql_query("SET @c = 'Volunteer'")){echo '3-'.mysql_error();exit;}
			if(!mysql_query("SET @d = '$time_in'")){echo '4-'.mysql_error();exit;}
			if(!mysql_query("SET @e = '$time_out'")){echo '5-'.mysql_error();exit;}
			if(!mysql_query("SET @f = 'Removing Volunteer Hours'")){echo '5-'.mysql_error();exit;}
			
			$result = mysql_query('EXECUTE stmnt USING @a,@b,@c,@d,@e,@f;');
			if(!$result){echo mysql_error();exit;}
			
			$_SESSION['flash'] = "Updated Volunteer Hours (Removed $hours hrs)";
			header('Location: hours_remove.php');
			
			exit;
		case 'hours_add':
			$contact = $_POST['contact'];
			$hours = $_POST['hours'] * 1.00;
			
			session_start();
			if($hours == 0){
				$_SESSION['flash'] = "Updated Volunteer Hours (Removed $hours hrs)";
				header('Location: hours_add.php');
			}
			//2011-12-22 00:00:00
			$format = 'Y-m-d H:i:00';
			$time_in = time();
			$time_out = $time_in + ($hours * 3600);
			$time_in = date($format,$time_in);
			$time_out = date($format, $time_out);
			
			$shop = 0;
			
			$sql = "PREPARE stmnt FROM \"INSERT INTO shop_hours (contact_id, shop_id, shop_user_role, time_in, time_out, comment) VALUES (?,?,?,?,?,?)\"";
			$result = mysql_query($sql);
			if(!$result){echo mysql_error();exit;}
			
			if(!mysql_query("SET @a = $contact")){echo '1-'.mysql_error();exit;}
			if(!mysql_query("SET @b = $shop")){echo '2-'.mysql_error();exit;}
			if(!mysql_query("SET @c = 'Volunteer'")){echo '3-'.mysql_error();exit;}
			if(!mysql_query("SET @d = '$time_in'")){echo '4-'.mysql_error();exit;}
			if(!mysql_query("SET @e = '$time_out'")){echo '5-'.mysql_error();exit;}
			if(!mysql_query("SET @f = 'Adding Volunteer Hours'")){echo '5-'.mysql_error();exit;}
			
			$result = mysql_query('EXECUTE stmnt USING @a,@b,@c,@d,@e,@f;');
			if(!$result){echo mysql_error();exit;}
			
			$_SESSION['flash'] = "Added Volunteer Hours ($hours hrs)";
			header('Location: hours_add.php');
			exit;
	}
}
?>
<h1 style='color:red'>No Action Given</h1>