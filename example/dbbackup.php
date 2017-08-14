<?php 
set_time_limit(3600);
	require 'STdbBackup.php';
	$dbinfo=array(
		'host'		=>'localhost',
		'dbname' 	=> 'wp_blog',
		'username'	=>'root',
		'password'	=> ''
		);
	$backup= new STdbBackup($dbinfo);
	$sqlfilelink=$backup->BackUp();
	if ($sqlfilelink) {
		echo "Successfully Backup in Dir ".$sqlfilelink;
	}else{
		echo $backup->dbError;
	}
?>
