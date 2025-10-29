<?php
include "../koneksi.php";
$id_kecamatan=$_GET['id_kecamatan'];
$namakecamatan=$_GET['namakecamatan'];
$sql="INSERT INTO tb_kecamatan (id_kecamatan,kecamatan) VALUES ('$id_kecamatan','$namakecamatan')";
$query=mysql_query($sql);
if($query){
	echo "sukses";
	}else{
		echo "gagal";
		}
?>