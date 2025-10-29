<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Laporan Data Bangunan</title>
<link rel="stylesheet" type="text/css" href="borderstyle.css" />
<link href="../../images/favicon.gif" rel="shortcut icon" />
<script type="text/javascript" src="../../jquery.min.js"></script>
<script type="text/javascript" src="../../jquery.printElement.js"></script>
<script type="text/javascript">
function print_tabel(){
	$("#tabel1").printElement();
	}
</script>
<style type="text/css">
<!--
.Estilo1 {color: #999999}
-->
</style>
</head>
<body>
<form name="form1" enctype="multipart/form-data" action="">
<input style="background-image:url(../../images/printer.gif); background-repeat:no-repeat; padding:3px 5px 3px 30px;" type="button" value="Print Data" onclick="print_tabel();" />

<select name="id_kecamatan" id="id_kecamatan" style="border-radius:5px 5px 5px 5px; border:3px solid #06F; background:linear-gradient( #CCC, #FFF); padding:3px 3px 3px 3px;">
  <option value="0" selected="selected">Kecamatan--</option>
  <?php
	   include "../koneksi.php";
	   $id_kecamatan=$_GET['id_kecamatan'];
	   $bulan=$_GET['bulan2'];
	   $tahun=$_GET['tahun2'];
       $sql_d="SELECT * FROM tb_kecamatan   ORDER BY id_kecamatan ASC";
	   $result_d=mysql_query($sql_d);
	   while ($data_d=mysql_fetch_array($result_d)){
	   ?>
  <option value="<?php echo $data_d['id_kecamatan'];?>">
    <?php echo $data_d['kecamatan'];?>
    </option>
  <?php } ?>
</select>
<select name="tahun2" id="tahun2" style="border-radius:5px 5px 5px 5px; border:3px solid #06F; background:linear-gradient( #CCC, #FFF); padding:3px 3px 3px 3px;">
  <option selected="selected">Tahun distribusi</option>
  <?php
for($i=date('Y'); $i>=date('Y')-6; $i-=1){
echo"<option value='$i'> $i </option>";
}
?>
</select>
<select name="bulan2" id="bulan2" style="border-radius:5px 5px 5px 5px; border:3px solid #06F; background:linear-gradient( #CCC, #FFF); padding:3px 3px 3px 3px;">
  <option value="0" selected="selected" >Bulan </option>
  <option value="Januari">Januari</option>
  <option value="Pebruari">Pebruari</option>
  <option value="Maret">Maret</option>
  <option value="April">April</option>
  <option value="Mei">Mei</option>
  <option value="Juni">Juni</option>
  <option value="Juli">Juli</option>
  <option value="Agustus">Agustus</option>
  <option value="September">September</option>
  <option value="Oktober">Oktober</option>
  <option value="November">November</option>
  <option value="Desember">Desember</option>
</select>
<input type="submit" id="btnsubmit" name="btnsubmit" value="Cari" />
</form>
<table id="tabel1" width="700" border="1" cellspacing="0" cellpadding="1">
  <tr align="center">
    <td width="885" colspan="2" valign="top">
	
	<center>
	<strong><img width="90" height="90" alt="no image here" style="float:left;" src="../../images/lhokseumawe.png" />GIS PEMETAAN LOKASI BANGUNAN BERIZIN</strong>
	</center><br />
	<center>
	  <strong>LAPORAN DATA BANGUNAN<br /></strong><span>Kecamatan : <?php $idcamat=$_GET['id_kecamatan'];
	  if($idcamat=="1"){ echo "Banda Sakti";} elseif($idcamat=="2"){ echo "Muara Dua";} elseif($idcamat=="3"){ echo "Muara Satu";} elseif($idcamat=="4"){ echo "Blang Mangat";}
	  ?></span><br />
	  <br />
      <span><?php $tgl=date("d/M/Y"); echo "<strong>Tanggal : $tgl</strong>"; ?></span>
	</center>
		<hr /></td>
  </tr>
  <?php
  include "../koneksi.php";
  $sql="SELECT * FROM  tb_distribusi,tb_desa,tb_kecamatan WHERE tb_distribusi.id_kecamatan=tb_kecamatan.id_kecamatan AND tb_distribusi.id_desa=tb_desa.id_desa AND tb_distribusi.bulan='$bulan' ORDER BY tb_distribusi.iddistribusi";
  $result=mysql_query($sql);
  $i=0;
  while ($baris=mysql_fetch_array($result)){
  $i=$i+1;
  ?>
  <?php } ?>
  <tr>
    <td><table width="100%" border="0" style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; color:#036;">

  <tr bgcolor="#66CCFF">
  	<td>ID Bangunan</td>
  	<td>Nama Pemilik Bangunan</td>
    <td>Alamat</td>
    <td>Telp/HP</td>
    <td>Nomor Bukti Identitas Diri</td>
    <td>Fungsi Utama Bangunan Gedung</td>
    <td>Luas tanah Keseluruhan</td>
    <td>Lokasi Bangunan Gedung</td>
    <td>Tahun/Status Izin IMB/Jenis IMB</td>
  </tr><?php
  include "../koneksi.php";
  $no=0;
  $query=mysql_query("SELECT * FROM   tb_bangunan, tb_kecamatan, tb_desa WHERE tb_bangunan.id_kecamatan=tb_kecamatan.id_kecamatan AND tb_bangunan.id_desa=tb_desa.id_desa ORDER BY id_bangunan ASC");
  while ($row=mysql_fetch_array($query)){
  $no=$no+1;
?>
  <tr>
  	<td width="81" ><?php echo $row['id_bangunan'];?></td>
  	<td width="335" ><?php echo $row['nama_pemilik'];?></td>
    <td width="335" ><?php echo $row['alamat_pemilik'];?></td>
    <td width="191" ><?php echo $row['telp'];?></td>
    <td width="330" ><?php echo $row['nomor_bukti_diri'];?></td>
    <td width="330" ><?php echo $row['fungsi_gedung'];?></td>
    <td width="330" ><?php echo $row['luas_tanah'];?></td>
    <td width="330" ><ul>
    <li>Jalan : <?php echo $row['jalan'];?></li>
    <li>Desa : <?php echo $row['id_desa'];?></li>
    <li>Kecamatan : <?php echo $row['id_kecamatan'];?></li>
    </ul></td>
    <td width="330" ><?php echo $row['tahun'];?>/<?php echo $row['status_izin'];?>/<?php echo $row['id_jenis'];?></td>
</tr><?php } ?>
</table></td>
  </tr>
</table>
</body>
</html>
