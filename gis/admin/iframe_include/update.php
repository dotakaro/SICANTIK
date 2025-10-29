<?php
include "../koneksi.php";
	$aksi=$_GET['aksi'];
	//$kd_hapus=$_GET['kd_hapus'];
	// hapus data debitur
if ($aksi=="kecamatan"){
	$id_kecamatan=$_GET['id_kecamatan'];
	$kecamatan=$_GET['kecamatan'];
	$sql="UPDATE tb_kecamatan SET kecamatan='$kecamatan' WHERE id_kecamatan='$id_kecamatan'";
	$result=mysql_query($sql);
	if ($result){
		echo "sukses_update";
	}else{
		echo "gagal_update";
		}
}
// Hapus data plafond
if ($aksi=="desa"){
	$id_desa=$_GET['id_desa'];
	$desa=$_GET['desa'];
	$id_kecamatan=$_GET['id_kecamatan'];
	$sql="UPDATE tb_desa SET desa='$desa',id_kecamatan='$id_kecamatan' WHERE id_desa='$id_desa'";
	$result=mysql_query($sql);
	if ($result){
		echo "sukses_update";
	}else{
		echo "gagal_update";
		}
}
// hapus data kredit konsumtif
if ($aksi=="bangunan"){
$id_bangunan=$_GET['id_bangunan'];
$nama_pemilik=$_GET['nama_pemilik'];
$alamat_pemilik=$_GET['alamat_pemilik'];
$telp=$_GET['telp'];
$nomor_bukti_diri=$_GET['nomor_bukti_diri'];
$fungsi_gedung=$_GET['fungsi_gedung'];
$luas_tanah=$_GET['luas_tanah'];
$jalan=$_GET['jalan'];
$id_desa=$_GET['id_desa'];
$id_kecamatan=$_GET['id_kecamatan'];
$status_izin=$_GET['status_izin'];
$tahun=$_GET['tahun'];
$id_jenis=$_GET['id_jenis'];
	$sql="UPDATE   tb_bangunan SET nama_pemilik='$nama_pemilik', alamat_pemilik='$alamat_pemilik', telp='$telp', nomor_bukti_diri='$nomor_bukti_diri', fungsi_gedung='$fungsi_gedung', luas_tanah='$luas_tanah', jalan='$jalan', id_desa='$id_desa', id_kecamatan='$id_kecamatan', status_izin='$status_izin', tahun='$tahun', id_jenis='$id_jenis'   WHERE id_bangunan='$id_bangunan' ";
	$result=mysql_query($sql);
	if ($result){
		echo "sukses_update";
	}else{
		echo "gagal_update";
		}
}
// update anggota
if ($aksi=="lokasi"){
	$id_lokasi=$_GET['id_lokasi'];
	$kd_desa=$_GET['kd_desa'];
	$alamat=$_GET['alamat'];
	$id_jenis=$_GET['id_jenis'];
	$lat=$_GET['lat'];
	$lng=$_GET['lng'];
	$sql="UPDATE tb_lokasi SET kd_desa='$kd_desa', alamat='$alamat', id_jenis='$id_jenis', lat='$lat', lng='$lng' WHERE id_lokasi='$id_lokasi'";
	$result=mysql_query($sql);
	if ($result){
		echo "sukses_update";
	}else{
		echo "gagal_update";
		}
}
if ($aksi=="jenis"){
	$id_jenis=$_GET['id_jenis'];
	$jenis=$_GET['jenis'];
	$deskripsi=$_GET['deskripsi'];
	$sql="UPDATE   tb_jenis SET jenis='$jenis', deskripsi='$deskripsi' WHERE id_jenis='$id_jenis'";
	$result=mysql_query($sql);
	if ($result){
		echo "sukses_update";
	}else{
		echo "gagal_update";
		}
}
?>
