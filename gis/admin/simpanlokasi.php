<?php
include "koneksi.php";
$x=$_GET['x'];
$y=$_GET['y'];
//$id_desa=$_GET['id_desa'];
$id_bangunan=$_GET['id_bangunan'];
//$nama_tempat= $_GET['nama_tempat'];
//$informasi_umum= $_GET['informasi_umum'];
//$jalan= $_GET['jalan'];
$sql="insert into tb_lokasi (id_bangunan,lat,lng) values ('$id_bangunan','$x','$y')";
$masuk = mysql_query($sql);
if($masuk){
    echo "<b style='color:skyblue;'>Penyimpanan Berhasil</b>";
}else{
    echo "<b style='color:red;'>Error..!</b>";
}
?>
