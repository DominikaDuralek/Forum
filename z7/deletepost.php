<?php
	//usuwanie postow
	session_start(); // zapewnia dostęp do zmienny sesyjnych w danym pliku
	if (!isset($_SESSION['loggedin']))
	{
		header('Location: logowanie.php');
		exit();
	}
	
	$id = $_GET['id']; //id piosenki do usuniecia
	
	$link = mysqli_connect('', '', '', ''); // połączenie z BD
	if(!$link) { echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); } // obsługa błędu połączenia z BD
	
	$sql = "DELETE FROM posts WHERE id='$id'"; //usuniecie pliku z bazy
	
	mysqli_query($link, $sql);
	mysqli_close($link);

	header('Location: index1.php');	
?>