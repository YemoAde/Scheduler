<?php
session_start();
	require_once('functions.php');
	$utility = new Utility();
	$utility->logout();
?>