
<?php

	require_once('functions.php');
	class MailSender extends Utility {
		function __construct(){
			parent::__construct();
		}
		private function split_address ($emails) {
			return explode(",", $emails);
		}
		public function get_active_schedule () {
			try {
				$stmt = $this->conn->prepare("SELECT * FROM schedule WHERE ( NOW() >= start_date AND NOW() <= stop_date)");
				$stmt->execute();
				$res = $stmt->fetchAll(PDO::FETCH_ASSOC);

		    	if ( $stmt->rowCount() > 0){
		    		return $res;
		    	} else {
		    		return $res;
		    	}
			} catch (PDOException $ex) {
				echo $ex->getMessage();
			}
		}
		private function daily ($schedule_id) {
			try {
				$stmt = $this->conn->prepare("SELECT * FROM mails WHERE schedule_id = :schedule_id and DATE(NOW()) = DATE(date_added)");
				$stmt->bindParam(':schedule_id', $schedule_id);
				$stmt->execute();
				$res = $stmt->fetch(PDO::FETCH_ASSOC);

		    	if( $stmt->rowCount() > 0){
		    		return true;
		    	}else{
		    		return false;
		    	}
			}catch(PDOException $ex){
				echo $ex->getMessage();
			}
		}
		private function weekly ($schedule_id) {
			try {
				$stmt = $this->conn->prepare("SELECT * FROM mails WHERE schedule_id = :schedule_id and WEEK(NOW()) = WEEK(date_added)");
				$stmt->bindParam(':schedule_id', $schedule_id);
				$stmt->execute();
				$res = $stmt->fetch(PDO::FETCH_ASSOC);

		    	if( $stmt->rowCount() > 0){
		    		return true;
		    	}else{
		    		return false;
		    	}
			}catch(PDOException $ex){
				echo $ex->getMessage();
			}
		}
		private function monthly ($schedule_id) {
			try {
				$stmt = $this->conn->prepare("SELECT * FROM mails WHERE schedule_id = :schedule_id and MONTH(NOW()) = MONTH(date_added)");
				$stmt->bindParam(':schedule_id', $schedule_id);
				$stmt->execute();
				$res = $stmt->fetch(PDO::FETCH_ASSOC);

		    	if( $stmt->rowCount() > 0){
		    		return true;
		    	}else{
		    		return false;
		    	}
			}catch(PDOException $ex){
				echo $ex->getMessage();
			}
		}
		private function get_report ($report_group_id) {
			try {
				$stmt = $this->conn->prepare("SELECT * FROM report WHERE report_group_id = :report_group_id");
				$stmt->bindParam(':report_group_id', $report_group_id);
				$stmt->execute();
				$res = $stmt->fetchAll(PDO::FETCH_ASSOC);

		    	if( $stmt->rowCount() > 0){
		    		return $res;
		    	}else{
		    		return NULL;
		    	}
			}catch(PDOException $ex){
				echo $ex->getMessage();
			}
		}
		private function mail_callback ($schedule_id) {
			try {

				$stmt = $this->conn->prepare("INSERT INTO mails (schedule_id) VALUES (:schedule_id)");
				$stmt->bindParam(':schedule_id',$schedule_id);
				$stmt->execute();

			} catch(PDOException $ex){
				echo $ex->getMessage();
			}
		}

		private function send_mail ($report_title, $address, $report_file, $schedule_id) {
			

			$mail = new PHPMailer;

			//$mail->SMTPDebug = 3;                               // Enable verbose debug output

			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = SMTP_USER;                 // SMTP username
			$mail->Password = SMTP_PASSWORD;                           // SMTP password
			$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 587;                                    // TCP port to connect to

			$mail->setFrom('reportscheduler@barbara.com', 'Report Scheduler - '.$report_title);
			foreach($address as $ad){
				$mail->addAddress($ad, ''); 
			}
       // Add attachments
			$mail->addAttachment($report_file);    // Optional name
			$mail->isHTML(true);                                  // Set email format to HTML

			$mail->Subject = 'Scheduled Report';
			$mail->Body    = 'This is a System Scheduled Report of '. $report_title;

			if(!$mail->send()) {
			    return false;
			} else {
			    return true;
			}

			

		}

		public function redundancy_check ($activeSchedule) {
			foreach ($activeSchedule as $item) {
				if($item['frequency'] == "daily"){
					if($this->daily($item['_id'])){
						//pass dont send a mail
					}else {
						// get and send reports in the group
						$response = $this->get_report($item['report_group_id']);
						$status = false;
						// print_r($response);
						if($response != NULL) {
							foreach ($response as $rep) {
								if($this->send_mail($rep['report_title'], $this->split_address($item['email_addresses']), $rep['report_file'], $item['_id'])){
									$status = true;
								}else{
									$status = false;
								}
							}
							if($status){
								echo "Success";
								$this->mail_callback($item['_id']);
							}
						}

					}	
				} else if ($item['frequency'] == "weekly"){
					if($this->weekly($item['_id'])){
						//pass dont send a mail
					}else {
						// get and send reports in the group
						$response = $this->get_report($item['report_group_id']);
						$status = false;
						// print_r($response);
						if($response != NULL) {
							foreach ($response as $rep) {
								if($this->send_mail($rep['report_title'], $this->split_address($item['email_addresses']), $rep['report_file'], $item['_id'])){
									$status = true;
								}else{
									$status = false;
								}
							}
							if($status){
								echo "Success";
								$this->mail_callback($item['_id']);
							}
						}

					}
				} else if ($item['frequency'] == "monthly") {
					if($this->monthly($item['_id'])){
						//pass dont send a mail
					}else {
						// get and send reports in the group
						$response = $this->get_report($item['report_group_id']);
						$status = false;
						// print_r($response);
						if($response != NULL) {
							foreach ($response as $rep) {
								if($this->send_mail($rep['report_title'], $this->split_address($item['email_addresses']), $rep['report_file'], $item['_id'])){
									$status = true;
								}else{
									$status = false;
								}
							}
							if($status){
								echo "Success";
								$this->mail_callback($item['_id']);
							}
						}

					}
				} else {
					//Pass Dont Do anything
				}
			}
		}

	}

echo "Hello";
$mailer = new MailSender();
$mailer->redundancy_check($mailer->get_active_schedule());


?>