<?php
include "../koneksi.php";
	$id_lokasi=$_GET['id_lokasi'];
	$id_desa=$_GET['id_desa'];
	$id_kecamatan=$_GET['id_kecamatan'];
	$lat=$_GET['lat'];
	$lng=$_GET['lng'];
	//$nama_tempat=$_GET['nama_tempat'];
	//$informasi_umum=$_GET['informasi_umum'];
	//$jalan=$_GET['jalan'];
	$sql="UPDATE tb_lokasi SET id_desa='$id_desa', id_kecamatan='$id_kecamatan', lat='$lat', lng='$lng' WHERE id_lokasi='$id_lokasi'";
	$result=mysql_query($sql) or die (mysql_error());
	if ($result){
		echo "sukses_update";
	}else{
		echo "gagal_update";
		}
?>