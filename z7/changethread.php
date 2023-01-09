<?php
	error_reporting(0);		
	session_start();
	
	$threadname = $_GET['threadname'];
	$_SESSION['currentthread'] = $threadname;
	
	header('Location: index1.php');
?>