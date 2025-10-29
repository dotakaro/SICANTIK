<?php
include "../koneksi.php";
$akhir = $_GET['akhir'];
$id_jenis=$_GET['id_jenis'];
$tahun=$_GET['tahun'];
$id_kecamatan=$_GET['id_kecamatan'];
if($akhir==1){
    $query = "SELECT * FROM tb_lokasi,tb_bangunan WHERE tb_lokasi.id_bangunan=tb_bangunan.id_bangunan ORDER BY tb_lokasi.id_lokasi DESC LIMIT 1";
}else{
    $query = "SELECT * FROM tb_lokasi,tb_bangunan,tb_desa WHERE tb_lokasi.id_bangunan=tb_bangunan.id_bangunan AND tb_bangunan.id_desa=tb_desa.id_desa AND tb_bangunan.id_jenis='$id_jenis' AND tb_bangunan.tahun='$tahun' AND tb_bangunan.id_kecamatan='$id_kecamatan' ";
	//SELECT * FROM tb_lokasi,tb_bangunan,tb_desa WHERE tb_lokasi.id_desa=tb_bangunan.id_desa AND tb_lokasi.id_desa=tb_desa.id_desa AND tb_bangunan.bulan='$bulan' AND tb_bangunan.tahun='$tahun'
}
$data = mysql_query($query);

$json = '{"wilayah": {';
$json .= '"petak":[ ';
while($x = mysql_fetch_array($data)){
	$desa=$x['nama_pemilik'];
    $json .= '{';
    $json .= '"id":"'.$x['id_lokasi'].'",
        "judul":"'.htmlspecialchars($desa).'",
        "x":"'.$x['lat'].'",
        "y":"'.$x['lng'].'",
		"tahun":"'.$x['tahun'].'",
		"idlokasi":"'.htmlspecialchars($x['id_lokasi']).'",
		"id_jenis":"'.htmlspecialchars($x['id_jenis']).'",
        "jenis":"'.htmlspecialchars($x['status_izin']).'",
		"id_kecamatan":"'.htmlspecialchars($x['id_kecamatan']).'",
		"id_desa":"'.htmlspecialchars($x['id_desa']).'"
    },';
}
$json = substr($json,0,strlen($json)-1);
$json .= ']';
$json .= '}}';
echo $json;

?>
