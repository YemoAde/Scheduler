<?php
	require('config.php');
	require 'vendor/phpmailer/phpmailer/PHPMailerAutoload.php';
	class MailSender {
		function __construct(){

		}
		public static function test_mail_function ($email) {

			$mail = new PHPMailer;

			//$mail->SMTPDebug = 3;                               // Enable verbose debug output

			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = SMTP_USER;                 // SMTP username
			$mail->Password = SMTP_PASSWORD;                           // SMTP password
			$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 587;                                    // TCP port to connect to

			$mail->setFrom('reportscheduler@barbara.com', 'Report Scheduler -  System Check');
			
			$mail->addAddress( $email, '');

			$mail->isHTML(true);                                 

			$mail->Subject = 'Scheduled Report';
			$mail->Body    = 'Hello Administrator, This is System Test on the Email function';

			if(!$mail->send()) {
				echo 'System Check ... <br>';
				echo 'Sending Email ...<br>';
				echo '. ...<br>';
				echo 'Error';
			    return false;
			} else {
				echo 'System Check ... <br>';
				echo 'Sending Email ... <br>';
				echo '. ... <br>';
				echo 'Email Was Sent';
			    return true;

			}
		}
	}

$test_mail = TEST_EMAIL;
MailSender::test_mail_function($test_mail);


?>