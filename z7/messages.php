<?php declare(strict_types=1); // włączenie typowania zmiennych w PHP >=7
session_start(); // zapewnia dostęp do zmienny sesyjnych w danym pliku

if (!isset($_SESSION['loggedin']))
{
	$username = 'gosc';
	//header('Location: logowanie.php');
	//exit();
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
					
				$link = mysqli_connect('', '', '', ''); // połączenie z BD
				if(!$link) { echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); } // obsługa błędu połączenia z BD
					
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

				//$username = $_SESSION['username'];
					
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
				echo "<br><a href ='messages.php?username=$username>'>Wiadomości</a><br />";	
				if($blocked == 0){
					echo "<br><a href ='newthread.php'>Stwórz nowy wątek</a>";				
				}
			}
			else{
				echo "<br><a href='logowanie.php'>Zaloguj się</a>";
			}
			?>
			<br>--------------------------------------
			<br><b>Wątki na forum:</b><br />
			
			<?php
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
				<a href='index1.php'>Poprzednia strona</a><br>
				<?php
					echo "WIADOMOŚCI UŻYTKOWNIKA: " . $_GET['username'];
				?>	
			</div>

			<div class="threadboard">
				<form method="POST" action="addpost.php" enctype="multipart/form-data"><br>
				Post: <input type="text" name="post" maxlength="90" size="90"><br><br>
				File: <input type="file" name="uploaded_file" id="uploaded_file"><br><br>

				<?php 
				$dbhost="mariadb106.server701675.nazwa.pl"; $dbuser="server701675_domdur7"; $dbpassword="6D6zB4WuURKzU@h"; $dbname="server701675_domdur7";
				$connection = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
				if (!$connection)
				{
				echo " MySQL Connection error." . PHP_EOL;
				echo "Errno: " . mysqli_connect_errno() . PHP_EOL;
				echo "Error: " . mysqli_connect_error() . PHP_EOL;
				exit;
				}

				$result = mysqli_query($connection, "SELECT * FROM user") or die ("DB error: $dbname");
				?>

				<label for="recipient">Odbiorca:</label>
				<select id="recipient" name="recipient">
				  <?php 
				  while ($row = mysqli_fetch_array ($result))
				  {
					$user = $row[1]; //nazwy uzytkownikow
					?>
					<option value=<?=$user?>><?=$user?></option>
					<?php
				  }
				  mysqli_close($connection);
				  ?>
				</select>

				<br><input type="submit" value="Send"/><br>

				<a href="index1.php">Strona główna zadania</a>
				</form>

				<?php
				$dbhost="mariadb106.server701675.nazwa.pl"; $dbuser="server701675_domdur7"; $dbpassword="6D6zB4WuURKzU@h"; $dbname="server701675_domdur7";
				$connection = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
				if (!$connection)
				{
					echo " MySQL Connection error." . PHP_EOL;
					echo "Errno: " . mysqli_connect_errno() . PHP_EOL;
					echo "Error: " . mysqli_connect_error() . PHP_EOL;
					exit;
				}

				//tablica z wiadomosciami uzytkowika
				$username = $_SESSION['username'];

				if($username == 'admin'){
					$result = mysqli_query($connection, "SELECT * FROM messages") or die ("DB error: $dbname");	
				}else{
					$result = mysqli_query($connection, "SELECT * FROM messages WHERE user='$username' OR recipient='$username'") or die ("DB error: $dbname");	
				}
			
				echo "Tablica użytkownika - $username:<br><br>";
				while ($row = mysqli_fetch_array ($result))
				{
					$user = $row[0];
					$recipient = $row[1];
					$date = $row[2];
					$message= $row[3];
					$file = $row[4];
					$file_extension = $row[5];

					//pojedynczy wpis uzytkownika
					echo "-------------------------------------------------------<br>";
					echo "<b>$user:</b><br>";
					if($message != ""){echo $message . "<br>";}
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
				}
				?>
				<br><br>
			</div>
		</div>
		
	</div>
</BODY>
</HTML>

<?php mysqli_close($link); ?>