<?php
session_start();
$time = date('H:i:s', time());

$user = $_SESSION['username']; //uzytkownik wysylajacy komunikat
$recipient = $_POST['recipient']; //uzytkownik odbierajacy komunikat
$post = $_POST['post']; //wyslany komunikat tekstowy

if (IsSet($_POST['post']) || file_exists($_FILES['uploaded_file']['tmp_name']))
{
	$dbhost=""; $dbuser=""; $dbpassword=""; $dbname="";
	$connection = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname); //polaczenie z BD
	if (!$connection)
	{
		echo " MySQL Connection error." . PHP_EOL;
		echo "Errno: " . mysqli_connect_errno() . PHP_EOL;
		echo "Error: " . mysqli_connect_error() . PHP_EOL;
		exit;
	}
	
	$datetime = date('Y-m-d H:i:s');
	$user_dir = "files/"; //katalog z plikami
	
	$file_name = $_FILES["uploaded_file"]["name"];
	$file_extension = pathinfo($_FILES["uploaded_file"]["name"], PATHINFO_EXTENSION); //rozszerzenie pliku
	if(file_exists($_FILES['uploaded_file']['tmp_name'])){$file_target_location = $user_dir . $file_name;}
	else{$file_target_location = "";}
	move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], $file_target_location);
	
	$result = mysqli_query($connection, "INSERT INTO messages (datetime, message, user, recipient, file_name, file_extension) 
	VALUES ('$datetime', '$post', '$user', '$recipient', '$file_target_location', '$file_extension');") or die ("DB error: $dbname");
	
	//czy zawiera 'cholera'
	$swear = 'cholera';
	$pos = stripos(strval($post), $swear);
	if($pos !== false){
		$sql = "UPDATE user SET blocked = 1 WHERE login='$user'"; //usuniecie pliku z bazy
		mysqli_query($link, $sql);
	}
	
	mysqli_close($connection);
}

header ('Location: messages.php');
?>