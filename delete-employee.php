<?php
 	require_once('functions.php');

 	$employee = new Employee();

 	// if (!$employee->isLoggedIn()) {
  //       $employee->redirect('index.php?auth');
  //   }

 	if(isset($_GET['id'])){
 		$id = $_GET['id'];
	 		$employee->delete_employee($id);
 	}else{
 		$employee->redirect('user-manage.php?error');
 	}
?>