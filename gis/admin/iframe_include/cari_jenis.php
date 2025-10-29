<?php
include "../koneksi.php";
$id_cari=$_GET['id_cari'];
$sql="SELECT * FROM  tb_jenis WHERE id_jenis='$id_cari'";
$result=mysql_query($sql);
$baris=mysql_fetch_array($result);
	//-- cek keadaan namauser
	if(!$baris){
	echo"nodata";
	//header("location: login.php?usr=error&pass=0&bag=pencari");
	}else{
	echo "adadata" ."|" .$baris['id_jenis'] ."|" .$baris['jenis'] ."|" .$baris['deskripsi'];
	}
?>