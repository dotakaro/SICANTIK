<?php
include "koneksi.php";
$x = $_GET['x'];
$y = $_GET['y'];
$judul = $_GET['judul'];
$des = $_GET['des'];
$jenis  = $_GET['jenis'];
$katagori= $_GET['katagori'];

$masuk = mysql_query("insert into data_lokasi
values(null,'$judul','$jenis','$des',$x,$y)");
if($masuk){
    echo "<b style='color:skyblue;'>Penyimpanan Berhasil</b>";
}else{
    echo "<b style='color:red;'>Error..!</b>";
}
?>
