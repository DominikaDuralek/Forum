<?php declare(strict_types=1); // włączenie typowania zmiennych w PHP >=7
session_start(); // zapewnia dostęp do zmienny sesyjnych w danym pliku
if (!isset($_SESSION['loggedin']))
{
	header('Location: logowanie.php');
	exit();
}

if (!isset($_SESSION['currentthread']))
{
	$_SESSION['currentthread'] = 'default';
}
//strona po zalogowaniu uzytkownika
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="fonts/fontawesome/fontawesome/css/all.css">
	<style>
		.content{height:auto; width:95%;}
		
		.topbar{height:50px; width:95%; background-color:Grey;}
		.sidecontent{height:100%; width:15%; float:left; background-color:Silver;}
		.maincontent{height:100%; width:80%; float:left; background-color:LightBlue;}
		
		.topicbar{height:10%; width:100%; background-color:SteelBlue;}
	</style>
</head>
<BODY>
	NOWY POST<br>
	Wątek:&ensp;
	<?php
		$threadname = $_SESSION['currentthread'];
		echo $threadname;
	?>
	
	<form method="post" action="addnewpost.php" enctype="multipart/form-data">
		Treść:<input type="text" name="message" maxlength="2000" size="100"><br>
		File: <input type="file" name="uploaded_file" id="uploaded_file"><br><br>
		<input type="submit" value="Send" />
	</form>
	
	<br><a href='index1.php'>Poprzednia strona</a>
	
</BODY>
</HTML>
