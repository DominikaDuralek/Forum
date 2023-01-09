<?php
	//usuwanie watkow
	session_start(); // zapewnia dostęp do zmienny sesyjnych w danym pliku
	if (!isset($_SESSION['loggedin']))
	{
		header('Location: logowanie.php');
		exit();
	}
	
	$threadname = $_GET['threadname']; //id piosenki do usuniecia
	
	$link = mysqli_connect('', '', '', ''); // połączenie z BD
	if(!$link) { echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); } // obsługa błędu połączenia z BD
	
	//usuniecia postow watku
	$deleteposts = "DELETE FROM posts WHERE thread='$threadname'";
	
	mysqli_query($link, $deleteposts);
	
	$sql = "DELETE FROM threads WHERE name='$threadname'"; //usuniecie watku z bazy
	
	mysqli_query($link, $sql);
	mysqli_close($link);

	header('Location: index1.php');	
?>