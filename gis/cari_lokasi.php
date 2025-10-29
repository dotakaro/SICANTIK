<?php if(!isset($_GET['q'])):?>

<form name="form1" method="get" action=""> 
<table width="100%">
    <td><textarea  style="border-radius:5px 5px 5px 5px; border:3px solid #06F; background:linear-gradient( #CCC, #FFF); padding:3px 3px 3px 3px;" name="q" rows="2" cols="35" id="q"></textarea></td><td><input type="submit" value="Cari" style="border-radius:5px 5px 5px 5px; border:3px solid #06F; background:linear-gradient( #CCC, #FFF); padding:3px 3px 3px 3px;"/></td>
</table>
</form> 

<div id="result"></div>
<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript">
	var allow = true;
	$(document).ready(function(){
		$("#q").keyup(function(e){
			if(e.which == '13'){
				e.preventDefault();
					
				loadData();
			}else if($(this).val().length >= 0){
					
				loadData();
			}
		});
	});
	function loadData(){
	var query=document.getElementById('q').value;
		if(allow){
			allow = false;
			$("#result").html('loading...');
			$.ajax({
				url:'cari_lokasi.php?q='+query,
				success:
					function (data){
					$("#result").html(data);
					allow = true;
				}
			});
		}
	}
</script>
<?php endif;?>
<style>
.highlight
{
background: #CEDAEB;
}

.highlight_important
{
background: #9afa95;
}
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
//Fungsi Mark Teks
function hightlight($str, $keywords = '')
{
$keywords = preg_replace('/\s\s+/', ' ', strip_tags(trim($keywords))); // filter

$style = 'highlight';
$style_i = 'highlight_important';

/* Apply Style */

$var = '';

foreach(explode(' ', $keywords) as $keyword)
{
$replacement = "<span class='".$style."'>".$keyword."</span>";
$var .= $replacement." ";

$str = str_ireplace($keyword, $replacement, $str);
}
$str = str_ireplace(rtrim($var), "<span class='".$style_i."'>".$keywords."</span>", $str);
return $str;
}

//END Fungsi Mark Teks
if(isset($_GET['q']) && $_GET['q']){ 
 $conn = mysql_connect("localhost", "root", ""); 
 mysql_select_db("db_aset"); 
 $q = $_GET['q'];
 
//Menghitung Jumlah Yang Tampil 

$page = isset($_GET['page']) ? $_GET['page'] : 1;
$limit = 6;
$mulai_dari = $limit * ($page - 1);
$sqlCount = "select count(id_lokasi) FROM tb_lokasi where nama_tempat like '%$q%' or informasi_umum like '%$q%' or jalan like '%$q%'";
$rsCount = mysql_fetch_array(mysql_query($sqlCount));
$banyakData = $rsCount[0];
$sql = "select * from tb_lokasi where nama_tempat like '%$q%' or jalan like '%$q%' or informasi_umum like '%$q%' order by id_lokasi DESC limit $mulai_dari, $limit"; 
//Akhir Menghitung Jumlah Yang Tampil 
 $result = mysql_query($sql);
 

 if(mysql_num_rows($result) > 0){ 
 ?> 
 <?php if(isset($_GET['page'])):?>
<form name="form1" method="get" action=""> 
<table><td>Search : </td><td><textarea name="q" rows="1" id="q"></textarea></td><td><input type="submit" value="Search"/></td>
</table>
</form> 
 <?php endif;?>
 <table border="0" width="700px"><tr bgcolor="silver">
     <td>Nama Tempat</td>
     </tr>
 <?php 
 while($siswa = mysql_fetch_array($result)){?> 
 <tr> 
 <td><?php
 	//echo hightlight($siswa['id_lokasi'],$q);
	echo "<li>";
    //echo "<a href=\"javascript:setpeta(".$siswa['lat'].",".$siswa['lng'].",".$siswa['id_lokasi'].")\">".$siswa['nama_tempat']."</a><br>\n";
	$xjalan=htmlspecialchars("18888888888888888");
	echo "<a href=\"javascript:setpeta(".$siswa['lat'].",".$siswa['lng'].",".$siswa['id_lokasi'].");\">".$siswa['nama_tempat']."</a><br>\n";
	 //echo "<a href=\"javascript:setpeta(".$siswa['lat'].",".$siswa['lng'].",".$siswa['id_lokasi'].",".$siswa['jalan'].")\">".$siswa['nama_tempat']."</a><br>\n";
    echo "<span class=\" des\" id=\"".$siswa['id_lokasi']."\" style=\"\">".$siswa['informasi_umum']."</span><br>";
	 echo "<span class=\" des\" id=\"".$siswa['id_lokasi']."\" style=\"\">".$siswa['jalan']."</span><br>";
	echo "<span class=\" lati\" id=\"".$siswa['lat']."\" style=\"\">Posisi : ".$siswa['lat']." - ".$siswa['lng']." </span><br>";
	echo "</li>";
 ?>
 <input type="hidden" id="jl<?=$siswa['id_lokasi'];?>" name="xjalan" value="<?=$siswa['jalan'];?>" />
  <input type="hidden" id="xnama_tempat" name="xnama_tempat" value="<?=$siswa['nama_tempat'];?>" />
 </td> 
 </tr> 
 <?php }?> 
 </table> 
 <?php 
 }else{ 
 echo 'Data Tidak Ada'; 
 } 
 //Halaman
 $banyakHalaman = ceil($banyakData / $limit);
echo '</br><div id="page" style="font-size:12pt;">Halaman: ';
for($i = 1; $i <= $banyakHalaman; $i++){
 if($page != $i){
 echo '[<a href="cari_lokasi.php?page='.$i.'&q='.$q.'">'.$i.'</a>]  ';
 }else{
 echo "[<span style='color:silver'>$i</span>] ";
 }
}
//echo '&nbsp&nbsp<a href="cari_lokasi.php"><b>Ulangi Pencarian</b></a>';
//END HALAMAN
} 
?>