<?php
//zapisywanie do bazy danych o gosciu portalu
error_reporting(0);
date_default_timezone_set('Europe/Warsaw');

$ipaddress = $_SERVER["REMOTE_ADDR"];
$datetime = date('Y-m-d H:i:s');

function get_browser_name() { 
  $user_agent = $_SERVER['HTTP_USER_AGENT'];
  $name = 'Unknown';
  if(preg_match('/Opera/i',$user_agent)) {
    $name = 'Opera';
  }elseif(preg_match('/Edg/i',$user_agent)) {
    $name = 'Edge';
  }elseif(preg_match('/Chrome/i',$user_agent)) {
    $name = 'Chrome';
  }elseif(preg_match('/Safari/i',$user_agent)) {
    $name = 'Safari';
  }elseif(preg_match('/Firefox/i',$user_agent)) {
    $name = 'Mozilla Firefox';
  }elseif (strpos($user_agent, 'MSIE') || strpos($user_agent, 'Trident/7')) {
	$name =  'Internet Explorer';
  }
	return $name;
}
$browser_name = get_browser_name(); //nazwa przegladarki
		
$link = mysqli_connect('', '', '', ''); // połączenie z BD
if(!$link) { echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); } // obsługa błędu połączenia z BD

session_start();
$username = $_SESSION['username'];

$screen_resolution = $_POST['screen_resolution'];
$browser_resolution = $_POST['browser_resolution'];
$colors = $_POST['colors'];
$cookies_enabled = $_POST['cookies_enabled'];
$java_enabled = $_POST['java_enabled'];
$browser_language = $_POST['browser_language'];

$sql = "INSERT INTO goscieportalu (ipaddress, datetime, browser, username, screen_resolution, browser_resolution, colors, cookies_enabled, java_enabled, browser_language) 
VALUES ('$ipaddress', '$datetime', '$browser_name', '$username', '$screen_resolution', '$browser_resolution', '$colors', '$cookies_enabled', '$java_enabled', '$browser_language')";
mysqli_query($link, $sql);
mysqli_close($link);
?>
		
