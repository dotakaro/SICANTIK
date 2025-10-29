<?php
include "../koneksi.php";
$id_kecamatan=$_GET['id_kecamatan'];
$sql="SELECT * FROM tb_kecamatan WHERE id_kecamatan='$id_kecamatan'";
$result=mysql_query($sql);
$baris=mysql_fetch_array($result);
	//-- cek keadaan namauser
	if(!$baris){
	echo"nodata";
	//header("location: login.php?usr=error&pass=0&bag=pencari");
	}else{
	echo "adadata" ."|" .$baris['id_kecamatan'] ."|" .$baris['kecamatan'];
	}
?>