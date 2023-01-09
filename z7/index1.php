<?php declare(strict_types=1); //włączenie typowania zmiennych w PHP >=7
session_start(); //zapewnia dostęp do zmienny sesyjnych w danym pliku

if (!isset($_SESSION['loggedin']))
{
	$username = 'gosc';
}
else{
	$username = $_SESSION['username'];	
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
	<link rel="stylesheet" href="styles.css">
	<style>
	</style>
</head>
<BODY>
	<div class="topbar">
		<p style="font-size:24px; color:black;">FORUM</p>
	</div>
	
	<div class="content">
	
		<div class="sidecontent">
			Zalogowano - 
			<?php
				error_reporting(0);
					
				$link = mysqli_connect('', '', '', ''); //połączenie z BD
				if(!$link) { echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); } //obsługa błędu połączenia z BD
				
				//czy uzytkownik jest zablokowany
				$isUserBlocked = mysqli_query($link, "SELECT blocked FROM user WHERE login='$username' LIMIT 1");
				foreach ($isUserBlocked as $row) {
					$blocked = $row['blocked'];
				}
				
				if (!isset($_SESSION['loggedin']))
				{	
					echo 'gosc'; //informacja o tym kto jest zalogowany
				}
				else{
					echo $_SESSION['username']; //informacja o tym kto jest zalogowany
				}
				echo "<br>--------------------------------------";
				date_default_timezone_set('Europe/Warsaw');
					
				//informacja o ostatniej probie wlamania sie na konto
				$breakins = mysqli_query($link, "SELECT * FROM break_ins WHERE username='$username' ORDER BY datetime DESC LIMIT 1");
				foreach ($breakins as $row) {
					if($row['datetime'] != ""){
						echo "<br><p style='color: black';>Ostatnia próba włamania:<br>DATA: " . $row['datetime'] . "<br>IP: " . $row['ip'] . "</p>";			
					}
				}
				
				if($username != 'gosc'){
					echo "<br><a href ='logout.php'>Wyloguj</a>";
					echo "<br><a href ='userthreads.php?username=$username'>Moje wątki</a>";
					echo "<br><a href ='messages.php?username=$username'>Wiadomości</a><br />";	
					if($blocked == 0){
						echo "<br><a href ='newthread.php'>Stwórz nowy wątek</a>";				
					}
				}
				else{
					echo "<br><a href='logowanie.php'>Zaloguj się</a>";
				}
					
				
				
				echo "<br>--------------------------------------";
				echo "<br><b>Wątki na forum:</b><br />";
					
				
				//lista watkow na forum
				$threads = mysqli_query($link, "SELECT * FROM threads");
				foreach ($threads as $row) {
					$threadname = $row['name'];
					echo "<br><a href='changethread.php?threadname=$threadname'>• " . $row['name'] . "</a>";			
				}
				
				//lista uzytkownikow na forum
				echo "<br>--------------------------------------";
				echo "<br><b>Użytkownicy forum:</b><br />";
				$users = mysqli_query($link, "SELECT * FROM user");
				foreach ($users as $row) {
					$login = $row['login'];
					echo "<br><a href='userthreads.php?username=$login'>• " . $login . "</a>";			
				}
				
			?>
			
		</div>
		
		<div class="maincontent">	
			<div class="topicbar">
				<?php
					if($_SESSION['currentthread'] == 'default'){
						echo "<br>TEMAT: (nie wybrano tematu)";
					}
					else{
						echo "<br>TEMAT: " . $_SESSION['currentthread'];
						$name = $_SESSION['currentthread'];
						$threads = mysqli_query($link, "SELECT * FROM threads WHERE name='$name'"); // pobranie autora watku
						foreach ($threads as $row) {
							$author = $row['author'];		
						}
						echo "<br>Autor: <a href='userthreads.php?username=$author'>" . $author . "</a>";
						if($blocked == 0 && isset($_SESSION['loggedin'])){
							echo "<br><a href ='newpost.php?threadname=$threadname'>Dodaj nowy post</a>";
						}
						//admin moze usuwac watki
						if($username == 'admin') //mozliwosc zablokowania uzytkownika
						{
							echo "<br><a href ='deletethread.php?threadname=$name'>&ensp;Usuń wątek</a>";
						}
					}
				?>	
			</div>

			<div class="threadboard">
				<?php
					//lista postow danego watku
					$currentthread= $_SESSION['currentthread'];
					$posts = mysqli_query($link, "SELECT * FROM posts WHERE thread='$currentthread'"); // wiersze, w którym login=login z formularza
					foreach ($posts as $row) {
						$author = $row['user'];
						echo "<a href='userthreads.php?username=$author'>" . $row['user'] . ":</a><br>" . $row['message'] . "<br>";
						
						$file = $row['file_name'];
						$file_extension = $row['file_extension'];
						if($file!= ""){
							if($file_extension == "png" || $file_extension == "jpg" || $file_extension == "jpeg" || $file_extension == "gif"){
								echo "<img src='$file'><br>";
							}
							if($file_extension == "mp4"){
								echo "<video controls autoplay muted width='320px' height='240px'><source src='$file' type='video/mp4'></video><br>";
							}
							if($file_extension == "mp3"){
								echo "<audio controls><source src='$file' type='audio/mpeg'></audio><br>";
							}
						}
						
						if($row['user'] == $_SESSION['username'] || $username == 'admin'){
							$id = $row['id'];
							echo "<a href='deletepost.php?id=$id'>Usuń</a><br>";
						}							
						echo "------------------------------------------------------------------------------------<br>";	
					}
				?>
			</div>
		</div>
		
	</div>
</BODY>
</HTML>

<?php mysqli_close($link); ?>