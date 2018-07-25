<?php
error_reporting(0);
function showfiles($files,$flag){
$ret="";
$file=file_get_contents($files);
//print_r($file);
$recieved_arr=json_decode($file,true);
//print_r($recieved_arr);
if($flag==1){
foreach ($recieved_arr[0] as $bkey => $bstring){
$ret.=$bkey.";";
}
$ret.="\r\n";
}
foreach ($recieved_arr as $bulbarr){
$bulbarr["universitySpecialitiesName"]=str_replace("\n","",$bulbarr["universitySpecialitiesName"]);
$bulbarr["phone"]=str_replace(";",".,",$bulbarr["phone"]);
$bulbarr["foreignTypeName"]=str_replace(";",".,",$bulbarr["foreignTypeName"]);
foreach ($bulbarr as $bstring){
$ret.=$bstring.";";
}
$ret.="\r\n";
}
return $ret;
}
$csv="";
$csv.=showfiles("jsons\\json.txt",1);

for($i=1;$i<$_GET['pn']+1;$i++){
$csv.=showfiles("jsons\\json".$i.".txt",0);
}
//$csv= iconv("UTF-8", "Windows-1251", $csv);
header('Content-type: application/csv;charset=Windows-1251');
header('Content-Disposition: attachment; filename=csv.csv');
echo $csv;
//$filesave = fopen ("csv.csv" , "w");
//fwrite ($filesave, $csv);
//fclose($filesave);
//Header("Location: restsandbox.php");
?>
