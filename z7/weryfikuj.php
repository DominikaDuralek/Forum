<?php
error_reporting(0);
$user = htmlentities ($_POST['user'], ENT_QUOTES, "UTF-8"); // rozbrojenie potencjalnej bomby w zmiennej $user
session_start();
$_SESSION['username'] = $user;
$pass = htmlentities ($_POST['pass'], ENT_QUOTES, "UTF-8"); // rozbrojenie potencjalnej bomby w zmiennej $pass
$link = mysqli_connect('', '', '', ''); // połączenie z BD
if(!$link) { echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); } // obsługa błędu połączenia z BD
mysqli_query($link, "SET NAMES 'utf8'"); // ustawienie polskich znaków
$result = mysqli_query($link, "SELECT * FROM user WHERE login='$user'"); // wiersza, w którym login=login z formularza
$rekord = mysqli_fetch_array($result); // wiersza z BD, struktura zmiennej jak w BD

$ipaddress = $_SERVER["REMOTE_ADDR"];
$datetime = date('Y-m-d H:i:s');

if(!$rekord) // jeśli brak, to nie ma użytkownika o podanym loginie
{
	session_start();
	$_SESSION['loginAttempts'] += 1;
	
	if($_SESSION['loginAttempts'] >= 3){
		$_SESSION['loginDisabled'] = time();	
		$sql = "INSERT INTO break_ins (datetime, ip, username) VALUES ('$datetime', '$ipaddress', '$user')";
		mysqli_query($link, $sql);
	}

	mysqli_close($link);
	header('Location: logowanie.php');
}
else
{ // jeśli $rekord istnieje
	if($rekord['password']==$pass) // czy hasło zgadza się z BD
	{
		session_start();
		$_SESSION['loginAttempts'] = 0;
		$_SESSION ['loggedin'] = true; //zmienna sesyjna, true = zalogowany
		?>
		
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
			<script type = "text/javascript">
			$(document).ready(function() {
			var screen_resolution = screen.width + "x" + screen.height;
			var browser_resolution = window.innerWidth + "x" + window.innerHeight;
			var colors = screen.colorDepth;
			var cookies_enabled = navigator.cookieEnabled;
			var java_enabled = navigator.javaEnabled();
			var browser_language = navigator.language;
			
				$.ajax({
					url: "index1visit.php",
					type: "POST",
					dataType: "json",
					data: {
						screen_resolution: screen_resolution,
						browser_resolution: browser_resolution,
						colors: colors,
						cookies_enabled: cookies_enabled,
						java_enabled: java_enabled,
						browser_language: browser_language
					}
				});
				
				window.location.replace("index1.php");
			});
			</script>
			
		<?php
	}
	else // błędne hasło
	{
		session_start();
		$_SESSION['loginAttempts'] += 1;
	
		if($_SESSION['loginAttempts'] >= 3){
			$_SESSION['loginDisabled'] = time();
			$sql = "INSERT INTO break_ins (datetime, ip, username) VALUES ('$datetime', '$ipaddress', '$user')";
			mysqli_query($link, $sql);			
		}
		
		mysqli_close($link);
		header('Location: logowanie.php');
	}
}
?>
			


