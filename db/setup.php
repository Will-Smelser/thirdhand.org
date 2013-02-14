<?php
	//DATABASE CONFIGURATION and CONNECTION
	include_once 'Connections/YBDB.php';

	//check we are writable
	if(!is_writable('./')){
		echo 'Cannot write to this directory.  Please change apache / php permissions.<br/><br/>';
		exit;
	}
	
	//verify connection with query
	if(!mysql_query('SELECT * FROM `thirdhan_db`.`groups`')){
		echo 'Failed to perform test query.  Issue with database connection<br/>'.mysql_error();
		exit;
	}
	
	//LETS PASSWORD PROTECT
	$DS = DIRECTORY_SEPARATOR;
	//setup .htaccess protection
	$htaccess = "
AuthName \"Restricted Area\" 
AuthType Basic 
AuthUserFile {$_SERVER['DOCUMENT_ROOT']}{$DS}db{$DS}.htpasswd 
AuthGroupFile /dev/null 
require valid-user";
	
	$result = file_put_contents('.htaccess', $htaccess);
	echo ($result) ? 'Created .htaccess file.' : 'Failed to create .htaccess file.';
	echo '<br/><br/>';
	
	//Password1
	$htpass = 'admin:$apr1$khn5.Me6$G/kBavgSjfFydKGj6sd6z1';
	$result = file_put_contents('.htpasswd',$htpass);
	echo ($result) ? 'Created .htpasswd file.' : 'Failed to create .htpasswd file.';
	echo '<br/><br/>';

	
	
	//MODIFY THE DATABASE
	
	//modify the database
	$sql1 = "ALTER TABLE  `thirdhan_db`.`contacts` ADD  `groups_id` INT NOT NULL DEFAULT  '2' AFTER  `contact_id`;";
	
	$sql2 = "CREATE TABLE  `thirdhan_db`.`groups` (`groups_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,`name` VARCHAR( 25 ) NOT NULL) ENGINE = INNODB ;";
	$sql3 = "INSERT INTO `thirdhan_db`.`groups` (`groups_id`,`name`) VALUES (1,'Admin'),(2,'Volunteer'),(3,'None')";
	$sql4 = "CREATE TABLE  `thirdhan_db`.`permissions` (`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,`groups_id` INT NOT NULL ,`path` VARCHAR( 150 ) NOT NULL) ENGINE = INNODB ;";
	
	if(!mysql_query($sql1)) echo 'Failed running query: '.$sql1.'<br/>'.mysql_error().'<br/><br/>';
	if(!mysql_query($sql2)) echo 'Failed running query: '.$sql2.'<br/>'.mysql_error().'<br/><br/>';
	if(!mysql_query($sql3)) echo 'Failed running query: '.$sql3.'<br/>'.mysql_error().'<br/><br/>';
	if(!mysql_query($sql4)) echo 'Failed running query: '.$sql4.'<br/>'.mysql_error().'<br/><br/>';
	
	
?>
DONE!