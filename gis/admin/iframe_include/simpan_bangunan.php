<?php
include "../koneksi.php";
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
$sql="INSERT INTO   tb_bangunan(id_bangunan,nama_pemilik,alamat_pemilik,telp,nomor_bukti_diri,fungsi_gedung,luas_tanah,jalan,id_desa,id_kecamatan,status_izin,tahun,id_jenis) VALUES ('$id_bangunan','$nama_pemilik','$alamat_pemilik','$telp','$nomor_bukti_diri','$fungsi_gedung','$luas_tanah','$jalan','$id_desa','$id_kecamatan','$status_izin','$tahun','$id_jenis')";
$query=mysql_query($sql);
if($query){
	echo "sukses";
	}else{
		echo "gagal";
		}
?>