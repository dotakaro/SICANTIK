<?php
include "koneksi.php";
$akhir = $_GET['jenis'];
if($akhir==1){
    $query = "SELECT * FROM peta_icon ORDER BY nomor DESC LIMIT 1";
}else{
    $query = "SELECT * FROM peta_icon where jenis='$akhir'";
}
$data = mysql_query($query);

$json = '{"wilayah": {';
$json .= '"petak":[ ';
while($x = mysql_fetch_array($data)){
    $json .= '{';
    $json .= '"id":"'.$x['nomor'].'",
        "judul":"'.htmlspecialchars($x['nama']).'",
        "deskripsi":"'.htmlspecialchars($x['deskripsi']).'",
        "x":"'.$x['lat'].'",
        "y":"'.$x['lng'].'",
        "jenis":"'.$x['jenis'].'"
    },';
}
$json = substr($json,0,strlen($json)-1);
$json .= ']';

$json .= '}}';
echo $json;

?>
