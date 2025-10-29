<?php
include "../koneksi.php";
$jenis=$_GET['jenis'];
$deskripsi=$_GET['deskripsi'];
$sql="INSERT INTO   tb_jenis (jenis,deskripsi) VALUES ('$jenis', '$deskripsi')";
$query=mysql_query($sql);
if($query){
	echo "sukses";
	}else{
		echo "gagal";
		}
?>