<?php
include "koneksi.php";
$akhir = $_GET['akhir'];
$bulan=$_GET['bulan'];
$tahun=$_GET['tahun'];
$id_kecamatan=$_GET['id_kecamatan'];
if($akhir==1){
    $query = "SSELECT * FROM tb_lokasi,tb_distribusi,tb_desa WHERE tb_lokasi.id_desa=tb_distribusi.id_desa AND tb_lokasi.id_desa=tb_desa.id_desa AND tb_distribusi.bulan='$bulan' AND tb_distribusi.tahun='$tahun' AND tb_distribusi.id_kecamatan='$id_kecamatan'";
}else{
    $query = "SELECT * FROM tb_lokasi,tb_distribusi,tb_desa WHERE tb_lokasi.id_desa=tb_distribusi.id_desa AND tb_lokasi.id_desa=tb_desa.id_desa AND tb_distribusi.bulan='$bulan' AND tb_distribusi.tahun='$tahun' AND tb_distribusi.id_kecamatan='$id_kecamatan' GROUP BY tb_distribusi.id_desa ";
}
$data = mysql_query($query);

$json = '{"wilayah": {';
$json .= '"petak":[ ';
while($x = mysql_fetch_array($data)){
	$desa=$x['desa'];
    $json .= '{';
    $json .= '"id":"'.$x['id_lokasi'].'",
        "judul":"'.htmlspecialchars($desa).'",
        "deskripsi":"'.htmlspecialchars($x['informasi_umum']).'",
        "x":"'.$x['lat'].'",
        "y":"'.$x['lng'].'",
		"jalan":"'.$x['jalan'].'",
		"idlokasi":"'.htmlspecialchars($x['id_lokasi']).'",
		"jumlah_rts":"'.htmlspecialchars($x['jumlah_rts']).'",
		"jumlah_pagu":"'.htmlspecialchars($x['jumlah_pagu']).'",
        "jenis":"'.htmlspecialchars($x['status_distribusi']).'",
		"id_kecamatan":"'.htmlspecialchars($x['id_kecamatan']).'",
		"id_desa":"'.htmlspecialchars($x['id_desa']).'"
    },';
}
$json = substr($json,0,strlen($json)-1);
$json .= ']';
$json .= '}}';
echo $json;
?>
