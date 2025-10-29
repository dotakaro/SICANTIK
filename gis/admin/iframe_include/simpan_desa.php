<?php
include "../koneksi.php";
$id_desa=$_GET['id_desa'];
$desa=$_GET['desa'];
$id_kecamatan=$_GET['id_kecamatan'];
$sql="INSERT INTO tb_desa (id_desa,desa,id_kecamatan) VALUES ('$id_desa','$desa','$id_kecamatan')";
$query=mysql_query($sql);
if($query){
	echo "sukses";
	}else{
		echo "gagal";
		}
?>