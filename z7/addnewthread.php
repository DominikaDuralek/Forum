<?php
	error_reporting(0);		
	session_start();
	$link = mysqli_connect('', '', '', ''); // połączenie z BD
	if(!$link) { echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); }		
	//dodanie watku do bazy
	$user = $_SESSION['username'];
	$name = $_POST['name'];
	$query = mysqli_query($link, "INSERT INTO threads (name, author) VALUES ('$name', '$user')");
	
	mysqli_close($link);
	header('Location: index1.php');
?>