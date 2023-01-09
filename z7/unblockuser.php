<?php
	error_reporting(0);
					
	$link = mysqli_connect('', '', '', ''); // połączenie z BD
	if(!$link) { echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); } // obsługa błędu połączenia z BD
	
	$username = $_GET['user'];
	
	$sql = "UPDATE user SET blocked = 0 WHERE login='$username'";
	
	mysqli_query($link, $sql);
	mysqli_close($link);

	header('Location: index1.php');	
?>