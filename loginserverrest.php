<?php
// start edbo treating
if($_GET['access_token']==NULL and $_COOKIE['access_token']==NULL){
	//echo "<a href='/login.php'>Войти</a>";
}
else{
		$recieved_access_token=$_COOKIE['access_token'];
		if($_GET['access_token']!=NULL)
		$recieved_access_token=$_GET['access_token'];
}

?>