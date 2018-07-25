<html>
<head>
<meta charset="utf-8"/>
</head>
<body>
<?php
//sandbox
ERROR_REPORTING(0);
include("config.php");
?>
<html>
<head>
<meta charset="utf-8"/>
</head>
<body>
<hr />
<form method=POST id="formid">
<input type="radio" name="csv" value="csv" checked onclick="document.getElementById('formid').action='restsandbox.php'; document.getElementById('formid').method='POST';" /> - как json-ответ
<input type="radio" name="csv" value="nocsv" onclick="document.getElementById('formid').action='fetch.php'; document.getElementById('formid').method='GET';" /> - как выгрузка всех страниц в csv (номер страницы обязательно заменять на %N, размер опционально на %S) <br />


<textarea name="json" style="width:500px;height:500px"><?php echo $_POST["json"]; ?></textarea>
<input type="text" name="address1" value="<?php echo $_POST["address1"]; ?>" /> 
 <input type=submit /> <a href='login.php?out'>Выйти?</a>
</form>
<hr />
<pre>
<?php
include('loginserverrest.php');

echo "access_token:".$recieved_access_token."\r\n";
if($_POST["json"]!=NULL and $_POST["address1"]!=NULL){
$json_send_data=$_POST["json"];
$address1=substr($_POST["address1"], 18);
echo $json_send_data."<br />".$address1."<br />";

$sock = fsockopen($sockshost, $socksport, $errno, $errstr, 30);
if (!$sock) die("$errstr ($errno)\n");

//пишемо заголовки ПОСТ-запиту на адресу необхідного нам метода

fwrite($sock, "POST ".$address1." HTTP/1.0\r\n");
fwrite($sock, "Host: edbo.gov.ua\r\n");
fwrite($sock, "authorization: Bearer ".$recieved_access_token."\r\n");
fwrite($sock, "Content-type: application/json; charset=utf-8\r\n");
fwrite($sock, "Content-length: ".strlen($json_send_data)."\r\n");
fwrite($sock, "Origin:http://edbo.gov.ua\r\n");
fwrite($sock, "Referer:http://edbo.gov.ua/Entrance/ENT_NZ1_Orders\r\n");
fwrite($sock, "User-Agent:Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.112 Safari/537.36\r\n");
fwrite($sock, "Accept: *//*\r\n");
fwrite($sock, "\r\n");
fwrite($sock, $json_send_data);

//отримуємо заголовок відповіді ЄДБО
$headers1 = '';
while ($str1=trim(fgets($sock,4096))) $headers1 .= "$str1\n";

//отримуємо тіло відповіді ЄДБО
$body1 = '';
while (!feof($sock)) $body1 .= fgets($sock,4096);
// close socket
fclose($sock);



// переформатовуємо тіло відповіді у масив - далі його беремо в роботу
$recieved_arr1=json_decode($body1,true);
echo "<pre>";
var_dump($headers1);
var_dump($recieved_arr1);
echo "</pre>";
}
?>
<hr />
&lt;?php
ERROR_REPORTING(0);
include('../loginserverrest.php');
$json_send_data="<?php echo $_POST["json"]; ?>"

$sock = fsockopen(<?php echo '"'.$sockshost.'", '.$socksport; ?>, $errno, $errstr, 30);
if (!$sock) die("$errstr ($errno)\n");

//пишемо заголовки ПОСТ-запиту на адресу необхідного нам метода

fwrite($sock, "POST <?php echo $address1 ?> HTTP/1.0\r\n");
fwrite($sock, "Host: edbo.gov.ua\r\n");
fwrite($sock, "authorization: Bearer ".$recieved_access_token."\r\n");
fwrite($sock, "Content-type: application/json; charset=utf-8\r\n");
fwrite($sock, "Content-length: ".strlen($json_send_data)."\r\n");
fwrite($sock, "Origin:http://edbo.gov.ua\r\n");
fwrite($sock, "Referer:http://edbo.gov.ua/Entrance/ENT_NZ1_Orders\r\n");
fwrite($sock, "User-Agent:Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.112 Safari/537.36\r\n");
fwrite($sock, "Accept: *//*\r\n");
fwrite($sock, "\r\n");
fwrite($sock, $json_send_data);

//отримуємо заголовок відповіді ЄДБО
$headers1 = '';
while ($str1=trim(fgets($sock,4096))) $headers1 .= "$str1\n";

//отримуємо тіло відповіді ЄДБО
$body1 = '';
while (!feof($sock)) $body1 .= fgets($sock,4096);
// close socket
fclose($sock);

// переформатовуємо тіло відповіді у масив - далі його беремо в роботу
$recieved_arr1=json_decode($body1,true);
?&gt;
</pre>



