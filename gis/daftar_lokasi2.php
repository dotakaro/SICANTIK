<script type="text/javascript" src="Script/script.js"></script>
<style>
li{list-style:none;
	border-bottom:1px solid #0099FF;
	background-color:#E2E2E2;
	}
a{ text-decoration:none;}
li a:hover{ color:#0066FF; text-decoration:underline;}
.des{ font-size:10px; color:#666666; font-family:Tahoma; margin-left:5px;}
.lati{ font-size:10px; font-family:Tahoma; padding-left:5px;}
.long{ font-size:10px; font-family:Tahoma;}
</style>
<?php
include "koneksi.php";
//mysql_connect("localhost","root","");
//mysql_select_db("dbpdam");

$lokasi = mysql_query("select * from  tb_lokasi");
while($l=mysql_fetch_array($lokasi)){
	echo "<li>";
    echo "<a href=\"javascript:setpeta(".$l['lat'].",".$l['lng'].",".$l['id_lokasi'].")\">".$l['nama_tempat']."</a><br>\n";
    echo "<span class=\" des\" id=\"".$l['id_lokasi']."\" style=\"\">".$l['informasi_umum']."</span><br>";
	echo "<span class=\" lati\" id=\"".$l['lat']."\" style=\"\">Posisi : ".$l['lat']." - ".$l['lng']." </span><br>";
	//echo "<span class=\" long\"id=\"".$l['lng']."\" style=\"\">Longitude : ".$l['lng']."</span>\n";
	echo "</li>";
	
}
?>
