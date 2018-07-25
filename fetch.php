<html><head>

<meta http-equiv="content-type" content="text/html; charset=utf-8" /></head><body>
<?php
error_reporting(E_ALL); echo "<pre>";
// start edbo treating
include("../loginserverrest.php");

// ТЕПЕР МОЖНА ПРАЦЮВАТИ ІЗ СПЕЦІАЛЬНИМИ МЕТОДАМИ
// як в прикладі:

// готуємо дані для відправки
$pagesize=$_GET['ps'];
$pageno=$_GET['pn'];
if ($pageno==NULL) $pagesize=null;
if ($pagesize==NULL) $pagesize="500";
  $replf=array("%N","%S");
  $repla=array($pageno,$pagesize);
  $json_send_data=str_replace($replf,$repla,$_GET['json']);
 echo $json_send_data."\n";
  $address1=substr($_GET["address1"], 18);
//echo "</pre></br><pre>";

// відкриваємо сокет до ЄДБО
$sock = fsockopen($sockshost,  $socksport, $errno, $errstr, 30);
if (!$sock) die("$errstr ($errno)\n");

//пишемо заголовки ПОСТ-запиту на адресу необхідного нам метода

fwrite($sock, "POST ".$address1." HTTP/1.0\r\n");
fwrite($sock, "Host: edbo.gov.ua\r\n");
fwrite($sock, "authorization: Bearer ".$recieved_access_token."\r\n");
fwrite($sock, "Content-type: application/json; charset=utf-8\r\n");
fwrite($sock, "Content-length: ".strlen($json_send_data)."\r\n");
fwrite($sock, "User-Agent:Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.112 Safari/537.36\r\n");
fwrite($sock, "Accept: */*\r\n");
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
//var_dump($recieved_arr1);

// повні заголовки відповіді вашого метода
//var_dump($headers1);

// повне тіло відповіді вашого метода
//var_dump($body1);
$filesave = fopen ("jsons/json".$_GET['pn'].".txt" , "w");
fwrite ($filesave, $body1);
fclose($filesave);
$q=$_GET['pn']+1;

if ($body1!="[]")
Header("Location: fetch.php?json=".$_GET["json"]."&address1=".$_GET["address1"]."&ps=".$pagesize."&pn=".$q."&access_token=".$recieved_access_token);
else
Header("Location: summarycsv.php?pn=".$_GET['pn']);
// end edbo treating
//echo "</pre>";
?>
</body></html>