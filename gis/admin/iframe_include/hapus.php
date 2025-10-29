<?php
include "../koneksi.php";
$aksi=$_GET['aksi'];
$data_hapus=$_GET['data_hapus'];
// hapus dosen
if ($aksi=="kecamatan"){
	$sql="DELETE FROM tb_kecamatan WHERE id_kecamatan='$data_hapus'";
	$result=mysql_query($sql)or die (mysql_error());
	//jika berhasil di hapus
	if ($result){
		echo "sukses";
	}else{
		echo "gagal";
	}
}
// hapus mahasiswa
if ($aksi=="desa"){
	$sql="DELETE FROM tb_desa WHERE id_desa='$data_hapus'";
	$result=mysql_query($sql)or die (mysql_error());
	//jika berhasil di hapus
	if ($result){
		echo "sukses";
	}else{
		echo "gagal";
	}
}
// hapus desa
if ($aksi=="jenis"){
	$sql="DELETE FROM   tb_jenis WHERE id_jenis='$data_hapus'";
	$result=mysql_query($sql)or die (mysql_error());
	//jika berhasil di hapus
	if ($result){
		echo "sukses";
	}else{
		echo "gagal";
	}
}
// hapus desa
if ($aksi=="bangunan"){
	$sql="DELETE FROM   tb_bangunan WHERE id_bangunan='$data_hapus'";
	$result=mysql_query($sql)or die (mysql_error());
	//jika berhasil di hapus
	if ($result){
		echo "sukses";
	}else{
		echo "gagal";
	}
}
// hapus kelompok
if ($aksi=="kelompok"){
	$sql="DELETE FROM tbkelompok WHERE kd_kelompok='$data_hapus'";
	$result=mysql_query($sql)or die (mysql_error());
	//jika berhasil di hapus
	if ($result){
		echo "sukses";
	}else{
		echo "gagal";
	}
}
// hapus lokasi
if ($aksi=="anggota"){
	$sql="DELETE FROM tb_anggota WHERE idanggota='$data_hapus'";
	$result=mysql_query($sql)or die (mysql_error());
	//jika berhasil di hapus
	if ($result){
		echo "sukses";
	}else{
		echo "gagal";
	}
}
// hapus lokasi
if ($aksi=="kegiatan"){
	$sql="DELETE FROM tbkegiatan WHERE id_kegiatan='$data_hapus'";
	$result=mysql_query($sql)or die (mysql_error());
	//jika berhasil di hapus
	if ($result){
		echo "sukses";
	}else{
		echo "gagal";
	}
}
// hapus lokasi
if ($aksi=="lokasi"){
	$sql="DELETE FROM tb_lokasi WHERE id_lokasi='$data_hapus'";
	$result=mysql_query($sql)or die (mysql_error());
	//jika berhasil di hapus
	if ($result){
		echo "sukses";
	}else{
		echo "gagal";
	}
}
?>