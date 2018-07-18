<?php
// start edbo treating
error_reporting(0);
if(isset($_GET["out"])){
	setcookie("access_token", "");
	$_COOKIE['access_token']=NULL;
	header("Location: login.php");
}
include("config.php");
if($_GET['access_token']==NULL and $_COOKIE['access_token']==NULL and $_POST['login']!=NULL){
// готуємо дані для авторизації
$data="grant_type=password&username=".$_POST['login']."&password=".$_POST['pass']."&app_key=".$apikey;
// відкриваємо сокет до ЄДБО
$sock = fsockopen($sockshost, $socksport, $errno, $errstr, 30);
if (!$sock) die("$errstr ($errno)\n");

// пишемо заголовок і тіло запиту авторизації в сокет
fwrite($sock, "POST /data/EDEBOWebApi/oauth/token HTTP/1.0\r\n");
fwrite($sock, "Host: edbo.gov.ua\r\n");
fwrite($sock, "Content-type: application/x-www-form-urlencoded; charset=utf-8\r\n");
fwrite($sock, "Content-length: ".strlen($data)."\r\n");
fwrite($sock, "Accept: */*\r\n");
fwrite($sock, "\r\n");
fwrite($sock, $data);


//отримуємо заголовок відповіді ЄДБО
$headers = '';
while ($str=trim(fgets($sock,4096))) $headers .= "$str\n";


//отримуємо тіло відповіді ЄДБО
$body = '';
while (!feof($sock)) $body .= fgets($sock,4096);

// закриваємо сокет
fclose($sock);

// переформатовуємо тіло відповіді у масив
$recieved_arr=json_decode($body,true);

// тут отриманий токен для роботи в ЄДБО
$recieved_access_token=$recieved_arr['access_token'];
setcookie("access_token",$recieved_access_token,time()+3600);
//$expires_in=$recieved_arr['expires_in'];
// повні заголовки відповіді
//echo "<pre>";
//var_dump($headers);

// повне тіло відповіді
//var_dump($body);
//echo '</pre>';
if (($_POST['login']!=NULL and $_COOKIE['access_token']!=NULL) or $recieved_access_token!=NULL)
	header("Location: restsandbox.php");
}
else{
if ($_GET['access_token']!=NULL){
	$recieved_access_token=$_GET['access_token'];
	setcookie("access_token",$recieved_access_token,time()+86400);
}
if ($_COOKIE['access_token']!=NULL){
	$recieved_access_token=$_COOKIE['access_token'];
	setcookie("access_token",$recieved_access_token,time()+86400);
	echo "<a href='login.php?out'>Выйти?</a>";
}
else
{
?>
<html>
<head>
<title> EDBO REST LOGIN </title>
<link rel="stylesheet" href="/styles/pure/pure-min.css">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
<div class="pure-g" style="margin:50px">
<div class="pure-u-1 is-center">
<form class="pure-form" method=POST>
<input class="pure-input-rounded" type="text" id=login name=login placeholder="Логин@edbo.gov.ua" />
<input class="pure-input-rounded" type="password" id=pass name=pass placeholder="Пароль" />
<input type="submit" class="pure-button pure-button-primary" />
</form>
</div>
</div>

</body>
</html>
<?php
}
}

?>