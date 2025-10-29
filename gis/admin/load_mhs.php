<?php
include "koneksi.php";
$akhir = $_GET['akhir'];
if($akhir==1){
    $query = "SELECT * FROM tb_lokasi ORDER BY id_lokasi DESC LIMIT 1";
}else{
    $query = "SELECT * FROM tb_lokasi";
}
$data = mysql_query($query);

$json = '{"wilayah": {';
$json .= '"petak":[ ';
while($x = mysql_fetch_array($data)){
    $json .= '{';
    $json .= '"id":"'.$x['id_lokasi'].'",
        "judul":"'.htmlspecialchars($x['kd_desa']).'",
        "deskripsi":"'.htmlspecialchars($x['alamat']).'",
        "x":"'.$x['lat'].'",
        "y":"'.$x['lng'].'",
		"kd_kelompok":"'.$x['kd_kelompok'].'",
        "jenis":"group-2"
    },';
}
$json = substr($json,0,strlen($json)-1);
$json .= ']';

$json .= '}}';
echo $json;

?>
