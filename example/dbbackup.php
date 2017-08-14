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
	// $fileName="bak_".$dbinfo['dbname']."_".date('YmdHis').".sql";
	// $myfile = fopen($fileName, "w") or die("Unable to open file!");
	// fwrite($myfile, $sqlfile);
	// fclose($myfile);

	// header("Content-type: text/plain");
	// header("Content-Disposition: attachment; filename=ST_bak_".$dbinfo['dbname'].".sql");

	// // // do your Db stuff here to get the content into $content
	// print $sqlfile;
/**
 * This example shows sending a message using a local sendmail binary.
//  */
// // echo ini_get('sendmail_path');
// require 'PHPMailer/PHPMailerAutoload.php';

// //Create a new PHPMailer instance
// $mail = new PHPMailer;
// // Set PHPMailer to use the sendmail transport
// // $mail->isSendmail();
// // $mail->SMTPDebug = 3;                               // Enable verbose debug output

// $mail->isSMTP();                                      // Set mailer to use SMTP
// $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
// $mail->SMTPAuth = false;                               // Enable SMTP authentication
// $mail->Username = 'sunilthatal007@gmail.com';                 // SMTP username
// $mail->Password = 'password785670';                       // SMTP password
// $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
// $mail->Port = 587;

// //Set who the message is to be sent from
// $mail->setFrom('sunilthatal007@gmail.com', 'STdbBackup Class');
// //Set an alternative reply-to address
// $mail->addReplyTo('sunilthatal007@gmail.com', 'DB Backup');
// //Set who the message is to be sent to
// $mail->addAddress('luhit785673@gmail.com', 'Sunil');
// //Set the subject line
// $mail->Subject = 'Database Backup '.date('d-m-Y H:i:s');
// $mail->IsHTML(true);
// //Read an HTML message body from an external file, convert referenced images to embedded,
// //convert HTML into a basic plain-text alternative body
// //Replace the plain text body with one created manually
// $mail->AltBody = 'Thank You For Using Thatal DataBase Backup using PHP';
// $mail->Body = 'Thank You For Using Thatal DataBase Backup using PHP<br> Find the sql Attachment below: ';
// //Attach an image file
// // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
// $mail->addAttachment($fileName, $fileName);
// // $mail->addAttachment();

// //send the message, check for errors
// if (!$mail->send()) {
// 	echo "Mailer Error: " . $mail->ErrorInfo;
// } else {
// 	echo "Database Backuped and Successfully Sent to Mail Id";
// }


?>