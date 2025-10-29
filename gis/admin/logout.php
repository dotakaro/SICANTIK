<?php
session_start();
$_SESSION=array();
//session_destroy();
//setcookie('PHPSESSID','',time()-3600,'/','',0);
$bagian=$_SESSION['bagian'];
if ($bagian=='pencari'){
	//session_start();
	$_SESSION=array();
	session_destroy();
	setcookie('PHPSESSID','',time()-3600,'/','',0);
	header("location: index.php?usr=0&pass=0&bag=pencari");
}else{
	//session_start();
	$_SESSION=array();
	session_destroy();
	setcookie('PHPSESSID','',time()-3600,'/','',0);
	header("location: index.php?usr=0&pass=0&bag=penyedia");
}

?>