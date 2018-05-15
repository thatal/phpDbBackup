<?php
/**
 * Author: Sunil Thatal
*09/08/2017
Wed
Created By Sunil Thatal
*/
class STdbBackup
{
	// $dbServer Contain mysql
	private $dbInfo=array(
			'server'	=>	'mysql',
			'host'		=>	'localhost',
			'username'	=>	'username',
			'password'	=>	'password',
			'dbname'	=>	'dbname'
		);
	// var 	$mailId="example@example.com";
	private 	$dirPath="DbBackup";
	private 	$Mysql;
	private 	$sqlInfo="";
	public 		$dbError="";
	var 		$timeZone="Asia/Kolkata";
	
	function __construct($arr=array(),$mailId="")
	{
		if ($this->ValidateInfo($arr)==true) {
			// $dbInfo['server']	=$arr['server'];
			$this->dbInfo['username']	=$arr['username'];
			$this->dbInfo['password']	=$arr['password'];
			$this->dbInfo['dbname']		=$arr['dbname'];
			$this->dbInfo['host']		=$arr['host'];
		}
		$this->OpenConnection();
		// $this->backup=$this->BackUp();
	}

	private function ValidateInfo($arr){
		$errorMsg=array();
		if (!isset($arr['dbname'])) {
			$errorMsg['dbname']="<br>Error!! Database Name not defined.";
		}
		if (!isset($arr['username'])) {
			$errorMsg['username']="<br>Error!! Username Name not defined.";
		}
		if (!isset($arr['password'])) {
			$errorMsg['password']="<br>Error!! Password Name not defined.";
		}
		if (sizeof($errorMsg)>0) {
			foreach ($errorMsg as $key => $value) {
				$this->dbError.=$value."\n";
			}
			return false;
		}else{
			return true;
		}
	}

	public function BackUp(){
		$fileName="";
		$this->sqlInfo="-- STdbBackup SQL Backup\n-- Created By Sunil Thatal
				\n-- Host: ".$this->dbInfo['host']."\n-- Generation Time:".date('d-m-Y h:i A')." \n-- Server version: 5.6.17\n";
		$this->sqlInfo.="\n---- Database: `".$this->dbInfo['dbname']."`--\n\n";
		$this->sqlInfo.='SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";'."\n".'SET time_zone = "+00:00";';
		/************Dumping Database Table Name, Engine , Auto Increament***/

		$sql1="SELECT TABLE_NAME, ENGINE, AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA='".$this->dbInfo['dbname']."'";
		$result1 = $this->Mysql->query($sql1);
		if ($result1) {
			if ($result1->num_rows > 0) {
			// $dbTableName=array();
				while($STrow = $result1->fetch_assoc()) {
			        $this->sqlInfo.="\n--\n-- Table structure for table `".$STrow['TABLE_NAME']."`\n--\n";
			        // $this->sqlInfo.="CREATE TABLE IF NOT EXISTS `".$STrow['TABLE_NAME']."` (\n";
			        $sql2="SHOW CREATE TABLE `".$STrow['TABLE_NAME']."`";
			        $result2 = $this->Mysql->query($sql2);
			        if (!$result2) {
			        	$this->dbError="$sql2";
			        	echo $sql2;
			        }else{
						if ($result2->num_rows > 0) {
							
							$dbTableKey=array();
							$counter=0;
							while($STtableColumns = $result2->fetch_assoc()) {

								/****************Table Schema Generating****************/
								$this->sqlInfo.=$STtableColumns['Create Table'];
								$this->sqlInfo.=";";
							}

					        $sql3="SELECT * FROM `".$STrow['TABLE_NAME']."`";
					        $result3 = $this->Mysql->query($sql3);
							if ($result3->num_rows > 0) {

		/****************Dumping Data From table***************************/

								$this->sqlInfo.="\n\n--\n-- Dumping data for table `".$STrow['TABLE_NAME']."`\n--\n";
								$tableDataArr=array();
								while($STtableData = $result3->fetch_assoc()) {
									$tableDataArr[]=$STtableData;
								}
								$counter=0;
								$this->sqlInfo.="INSERT INTO `".$STrow['TABLE_NAME']."` (";
								foreach ($tableDataArr[0] as $key => $dw) {
									if ($counter>0) {
										$this->sqlInfo.=", ";
									}
									$this->sqlInfo.="`".$key."`";
									$counter++;
								}
								$this->sqlInfo.=") VALUES \n";
								$counter12=0;
								foreach ($tableDataArr as $key => $value) {
									if ($counter12>0) {
										$this->sqlInfo.=",\n";
									}
									$this->sqlInfo.="(";
									$counter=0;
									foreach ($value as $index => $data) {
										if ($counter>0) {
											$this->sqlInfo.=", ";
										}
										if (is_numeric($data)!=false) {
											$this->sqlInfo.=$data;
										}else{
											$order   = array("\r\n", "\n", "\r");
											$replace = '\n';
											$data=str_replace("'", "\'", $data);
											$data=str_replace($order, $replace, $data);
											$this->sqlInfo.="'".$data."'";
											// $this->sqlInfo.="'".$data."'";
										}
										
										$counter++;
									}
									$this->sqlInfo.=")";
									$counter12++;
								}
								$this->sqlInfo.=";\n";
							}
						}
					}
			    }
			}else{
				$this->dbError=$this->$dbInfo['dbname']." Database is Empty.";
				return false;
			}
		}else{
			$this->dbError="Database `".$this->dbInfo['dbname']."` not found!.";
			return false;
		}
		if ($this->dbError==="") {
			$fileName="db_backup_".$this->dbInfo['dbname']."_".date('YmdHis').".sql";
			// print($this->sqlInfo);
			if (!file_exists($this->dirPath)) {
			    mkdir($this->dirPath, 0777, true);
			}
			$this->dirPath=rtrim($this->dirPath,"/");
			$handle = fopen($this->dirPath."/".$fileName, 'w') or die();
			if (!$handle) {
				$this->dbError='Cannot open file:  '.$fileName;
				return false;
			}

			if (!fwrite($handle, $this->sqlInfo)) {
				$this->dbError="Unable to Write file ".$this->dirPath."/".$fileName;
				return false;
			}
			// return $this->sqlInfo;
			return $this->dirPath."/".$fileName;
		}else{
			return false;
		}
		
		$this->CloseConnection();

	}

	private function OpenConnection(){
		$this->Mysql = new mysqli($this->dbInfo['host'], $this->dbInfo['username'], $this->dbInfo['password'], $this->dbInfo['dbname']);
		// Check connection
		if ($this->Mysql->connect_error) {
		    //die("Connection failed: " . $this->Mysql->connect_error);
		    $this->dbError="Database Connection Failed: ".$this->Mysql->connect_error;
		}else{
			return true;
		}
	}

	private function CloseConnection(){
		$this->Mysql->close();
	}

	public function setTimeZone($timeZone=""){
		
	}
}