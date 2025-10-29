<?php
$host="localhost";
$user="root";
$pass="lingga";
$db="imb_gis";
$koneksi=mysql_connect($host,$user,$pass);
mysql_select_db($db);
if(!$koneksi){
	echo "gagal koneksi";
	}
?>
