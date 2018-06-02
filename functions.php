<?php

class Utility {

	protected $conn;

	public function __construct() {
		require_once('connection.php');
		require_once   'vendor/phpmailer/phpmailer/PHPMailerAutoload.php';
		$db = new dbConnect();
		$this->conn = $db->connect();
	}


	public function isLoggedIn () {
		if (isset($_SESSION['email'])) {
			return true;
		}
		else{
			return false;
		}
	}

	public function isAdmin () {
		if($_SESSION['role'] == "admin"){
			return true;
		}else{
			return false;
		}
	}

	public function get_current_user_group () {
		return $_SESSION['group_id'];
	}

	public function redirect ($url) {
		header("Location: $url");
	}

	public function format_date ($date) {
		return DateTime::createFromFormat('Y-m-d', $date)->format("Y/m/d");

	}
	public function format_date_reverse ($date) {
		return DateTime::createFromFormat('Y-m-d', $date)->format("Y-m-d");

	}

	public function get_count ($table) {
		try {
			$stmt = $this->conn->prepare("SELECT * FROM $table WHERE 1");
			$stmt->execute();
			$res = $stmt->fetchAll(PDO::FETCH_ASSOC);

			return $stmt->rowCount();

		} catch (PDOException $ex) {

			echo $ex->getMessage();
		}
	}


	public function logout() {
		session_unset($_SESSION['user_id']);
		session_unset($_SESSION['email']);
		session_unset($_SESSION['role']);
		session_unset($_SESSION['group_id']);
		session_destroy();
		$this->redirect('index.php?logout');

	}	

	public function login ($email, $password) {
		try {
			$stmt = $this->conn->prepare("SELECT _id,email, password, role, group_id FROM user WHERE (email=:email and password=:password)"); 
	    	
		    $stmt->bindParam(':email', $email);
		    $stmt->bindParam(':password', $password);
	    	
			$stmt->execute();
			$res = $stmt->fetch(PDO::FETCH_ASSOC);
	    	if ( $stmt->rowCount() > 0 ) {

	    		$_SESSION['user_id'] = $res['_id'];
	    		$_SESSION['email'] = $res['email'];
	    		$_SESSION['role'] = $res['role'];
	    		$_SESSION['group_id'] = $res['group_id'];
	    		$this->redirect('dashboard.php');
	    	} else {
	    		$this->redirect('index.php?error');
	    		exit;
	    	}
		} catch (PDOException $ex) {
			$this->redirect('index.php?error');
	    	exit;
		}
	}

	public function get_all ($table) {
		try {
			$stmt = $this->conn->prepare("SELECT * FROM $table WHERE 1");
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

	public function get_all_restricted ($table) {
		try {
			$stmt = $this->conn->prepare("SELECT * FROM $table WHERE report_group._id IN (SELECT report_group_id from access WHERE group_id=:gid)");
			$stmt->execute(array(':gid' => $_SESSION['group_id']));
			$res = $stmt->fetchAll(PDO::FETCH_ASSOC);

	    	if ($stmt->rowCount() > 0){
	    		return $res;
	    	} else {
	    		return $res;
	    	}
		} catch (PDOException $ex) {
			echo $ex->getMessage();
		}
	}

}

class Employee extends Utility {

	function __construct () {
		parent::__construct();
		if(!$this->isAdmin()){
			$this->redirect('dashboard.php');
		}
	}

	public function employee ($array) {
		try {
			$stmt = $this->conn->prepare("INSERT INTO user (fullname, department, officeID, group_id, email,role, password) VALUES (:fullname, :department, :officeID, :group_id, :email, :role, :password)");
			$stmt->execute($array);

			$this->redirect('user-add.php?success');

		} catch(PDOException $ex) {

			echo $ex->getMessage();
		}
	}

	public function all_employee () {
		try {
			$stmt = $this->conn->prepare("SELECT * FROM user WHERE 1");
			$stmt->execute();
			$res = $stmt->fetchAll(PDO::FETCH_ASSOC);

	    	if( $stmt->rowCount() > 0){
	    		return $res;
	    	}else{
	    		return $res;
	    	}
		}catch(PDOException $ex){
			echo $ex->getMessage();
		}
	}

	public function get_employee ($id) {
		try {
			$stmt = $this->conn->prepare("SELECT fullname, department, officeID, group_id, email, role FROM user WHERE (_id=:id)");
			$stmt->bindParam(':id', $id);
			$stmt->execute();
			$res = $stmt->fetch(PDO::FETCH_ASSOC);

	    	if( $stmt->rowCount() > 0){
	    		return $res;
	    	}else{
	    		return $res;
	    	}
		}catch(PDOException $ex){
			echo $ex->getMessage();
		}
	}

	public function delete_employee ($id) {
		try {
			$stmt = $this->conn->prepare("DELETE FROM user WHERE (_id=:id)");
			$stmt->bindParam(':id', $id);
			$stmt->execute();

			$this->redirect('user-manage.php?success');
		}
		catch (PDOException $ex) {
			$this->redirect('user-manage.php?error');
		}
	}
	public function update_employee ($array, $id) {
		try {
			$stmt = $this->conn->prepare("UPDATE user SET fullname =:fullname, department=:department, officeID=:officeID, group_id=:group_id, email=:email, role=:role WHERE _id='$id'");
			$stmt->execute($array);

			$this->redirect('user-manage.php');	
		}
		catch (PDOException $ex) {
			echo $ex->getMessage();
		}
	}
}

class Schedule extends Utility {

	function __construct() {
		parent::__construct();
	}

	private function split_address ($emails) {
			return explode(",", $emails);
		}

	public function schedule ($array) {
		try {
			$stmt = $this->conn->prepare("INSERT INTO schedule (report_group_id, schedule_title, frequency, start_date, stop_date, added_by, email_addresses) VALUES (:report_group_id, :schedule_title, :frequency, :start_date, :stop_date, :added_by, :email_addresses)");
			$stmt->execute($array);

			

			switch ($array[':frequency']) {
				case 'daily':
					$this->schedule_mail_callback("24 Hours", $this->split_address($array[':email_addresses']));
					break;
				case 'weekly':
					$this->schedule_mail_callback("7 Days" , $this->split_address($array[':email_addresses']));
					break;
				default:
					$this->schedule_mail_callback("30 Days", $this->split_address($array[':email_addresses']));
					break;
			}

			$this->redirect('schedule-request.php?success');

			return $stmt;
		}catch (PDOException $ex) {
			//pass
			echo $ex->getMessage();
		}
	}

	public function all_schedule () {
		try {
		if($this->isAdmin()) {
			$stmt = $this->conn->prepare("SELECT *, schedule._id as id, report_group.report_group_name FROM schedule LEFT JOIN report_group ON (schedule.report_group_id = report_group._id)  WHERE 1");
		} else {
			$stmt = $this->conn->prepare("SELECT *, schedule._id as id, report_group.report_group_name FROM schedule LEFT JOIN report_group ON (schedule.report_group_id = report_group._id)  WHERE added_by=:user");
			$stmt->bindParam(':user', $_SESSION['user_id']);
		}
		
			
			$stmt->execute();
			$res = $stmt->fetchAll(PDO::FETCH_ASSOC);

	    	if( $stmt->rowCount() > 0){
	    		return $res;
	    	}else{
	    		return $res;
	    	}
		}catch(PDOException $ex){
			echo $ex->getMessage();
		}
	}

	public function get_schedule ($id) {
		try {
			$stmt = $this->conn->prepare("SELECT report_group_id, schedule_title, frequency, start_date, stop_date, email_addresses FROM schedule WHERE (_id=:id)");
			$stmt->bindParam(':id', $id);
			$stmt->execute();
			$res = $stmt->fetch(PDO::FETCH_ASSOC);

	    	if( $stmt->rowCount() > 0){
	    		return $res;
	    	}else{
	    		return $res;
	    	}
		}catch(PDOException $ex){
			echo $ex->getMessage();
		}
	}
	public function update_schedule ($array, $id) {
		try {
			$stmt = $this->conn->prepare("UPDATE schedule SET report_group_id =:report_group_id, schedule_title=:schedule_title, frequency=:frequency, start_date=:start_date, stop_date=:stop_date, email_addresses=:email_addresses WHERE (_id='$id')");
			$stmt->execute($array);

			$this->redirect('schedule-manage.php');
		}
		catch (PDOException $ex) {
			echo $ex->getMessage();
		}
	}

	public function delete_schedule ($id) {
		try {
			$stmt = $this->conn->prepare("DELETE FROM schedule WHERE (_id=:id)");
			$stmt->bindParam(':id', $id);
			$stmt->execute();

			$this->redirect('schedule-manage.php?success');
		}
		catch (PDOException $ex) {
			$this->redirect('schedule-manage.php?error');
		}
	}

	private function schedule_mail_callback ($message, $address) {
			

			$mail = new PHPMailer;

			//$mail->SMTPDebug = 3;                               // Enable verbose debug output

			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = SMTP_USER;                 // SMTP username
			$mail->Password = SMTP_PASSWORD;                           // SMTP password
			$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 587;                                    // TCP port to connect to

			$mail->setFrom('reportscheduler@barbara.com', 'Report Scheduler - Report Subscription Notice');
			foreach($address as $ad){
				$mail->addAddress($ad, ''); 
			}
       // Add attachments
			    // Optional name
			$mail->isHTML(true);                                  // Set email format to HTML

			$mail->Subject = 'Scheduled Report';
			$mail->Body    = 'This is to notify you that your Report Schedule was successfully Subscribed, Your Report mails will be sent every '.$message;

			if(!$mail->send()) {
			    return false;
			} else {
			    return true;
			}

			

		}
}

class Group extends Utility {

	function __contstruct () {
		parent::__contstruct();
		if(!$this->isAdmin()){
			$this->redirect('dashboard.php');
		}
	}

	

	public function all_group () {
		return $this->get_all('groups');
	}

	public function delete_group ($id) {
		try {
			$stmt = $this->conn->prepare("DELETE FROM groups WHERE _id=:id");
			$stmt->bindParam(':id', $id);
			$stmt->execute();
			$stmt = $this->conn->prepare("DELETE FROM access WHERE group_id=:id");
			$stmt->bindParam(':id', $id);
			$stmt->execute();

			$this->redirect('permission.php?success');
		}
		catch (PDOException $ex) {
			$ex->getMessage();
		}
	}

	public function add_group ($array) {
		try {
			$stmt = $this->conn->prepare("INSERT INTO groups (group_name) VALUES (:group_name)");
			$stmt->execute($array);

			return $this->conn->lastInsertId();

		} catch(PDOException $ex) {
			return NULL;
			echo $ex->getMessage();
		}
	}

	public function grant_access ($array) {
		try {
			$stmt = $this->conn->prepare("INSERT INTO access (group_id, report_group_id) VALUES (:group_id, :report_group_id)");
			$stmt->execute($array);

			$this->redirect('permission.php?success');

		} catch(PDOException $ex) {
			echo $ex->getMessage();
		}
	}
}

class Report extends Utility {

	function __construct () {
		parent::__construct();
		if(!$this->isAdmin()){
			$this->redirect('dashboard.php');
		}
	}

	public function report ($array) {
		try {
			$stmt = $this->conn->prepare("INSERT INTO report (report_title, report_group_id, report_file) VALUES (:report_title, :report_group, :report_file)");
			$stmt->execute($array);

			$this->redirect('reports.php?success');

		} catch(PDOException $ex) {

			echo $ex->getMessage();
		}

	}

	public function delete_report ($id) {
		try {
			$stmt = $this->conn->prepare("DELETE FROM report WHERE (_id=:id)");
			$stmt->bindParam(':id', $id);
			$stmt->execute();

			$this->redirect('reports.php?success');
		}
		catch (PDOException $ex) {
			$this->redirect('reports.php?error');
		}
	}

	public function report_group ($array) {
		try {
			$stmt = $this->conn->prepare("INSERT INTO report_group (report_group_name) VALUES (:report_group_name)");
			$stmt->execute($array);

			$this->redirect('groups.php?success');

		} catch(PDOException $ex) {
			echo $ex->getMessage();
			return NULL;
			
		}
	}

	public function delete_report_group ($id) {
		try {
			$stmt = $this->conn->prepare("DELETE FROM report_group WHERE (_id=:id)");
			$stmt->bindParam(':id', $id);
			$stmt->execute();

			$this->redirect('groups.php?success');
		}
		catch (PDOException $ex) {
			$this->redirect('groups.php?error');
		}
	}

}








?>