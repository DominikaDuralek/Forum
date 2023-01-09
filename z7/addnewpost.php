<?php
	error_reporting(0);		
	session_start();
	
		$link = mysqli_connect('', '', '', ''); // połączenie z BD
		if(!$link) { echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); }		
		//dodanie posta do bazy
		$user = $_SESSION['username'];
		$message = $_POST['message'];
		$datetime = date('Y-m-d H:i:s');
		$threadname = $_SESSION['currentthread'];
		
		$user_dir = "files/"; //katalog z plikami
		
		$file_name = $_FILES["uploaded_file"]["name"];
		$file_extension = pathinfo($_FILES["uploaded_file"]["name"], PATHINFO_EXTENSION); //rozszerzenie pliku
		if(file_exists($_FILES['uploaded_file']['tmp_name'])){$file_target_location = $user_dir . $file_name;}
		else{$file_target_location = "";}
		move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], $file_target_location);
		
		
		$query = mysqli_query($link, "INSERT INTO posts (thread, user, datetime, message, file_name, file_extension) 
		VALUES ('$threadname', '$user', '$datetime', '$message', '$file_target_location', '$file_extension')");
		
		//czy zawiera 'cholera'
		$swear = 'cholera';
		$pos = stripos(strval($message), $swear);
		if($pos !== false){
			$sql = "UPDATE user SET blocked = 1 WHERE login='$user'"; //usuniecie pliku z bazy
			mysqli_query($link, $sql);
		}
		
		mysqli_close($link);
	
	header('Location: index1.php');
?>