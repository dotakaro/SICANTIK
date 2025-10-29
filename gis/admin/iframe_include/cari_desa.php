<?php
include "../koneksi.php";
$id_desa=$_GET['id_desa'];
$sql="SELECT * FROM tb_desa WHERE id_desa='$id_desa'";
$result=mysql_query($sql);
$baris=mysql_fetch_array($result);
	//-- cek keadaan namauser
	if(!$baris){
	echo"nodata";
	//header("location: login.php?usr=error&pass=0&bag=pencari");
	}else{
	echo "adadata" ."|" .$baris['id_desa'] ."|" .$baris['desa'] ."|" .$baris['id_kecamatan'];
	}
?>