<?php
 	require_once('functions.php');

 	$schedule = new Schedule();

 	// if (!$schedule->isLoggedIn()) {
  //       $schedule->redirect('index.php?auth');
  //   }

 	if(isset($_GET['id'])){
 		$id = $_GET['id'];
	 		$schedule->delete_schedule($id);
 	}else{
 		$schedule->redirect('schedule-manage.php?error');
 	}
?>