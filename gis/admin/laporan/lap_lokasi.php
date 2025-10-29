<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Laporan Data Lokasi</title>
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
<input type="submit" id="btnsubmit" name="btnsubmit" value="Cari" />
</form>
<table  id="tabel1" width="800" border="1" cellspacing="0" cellpadding="1">
  <tr align="center">
    <td colspan="15" valign="top">
	
	<center>
	<strong><img width="90" height="90" alt="no image here" style="float:left;" src="../../images/lhokseumawe.png" />GIS PEMETAAN LOKASI BANGUNAN</strong>
	</center><br />
	<center>
	  <strong>LAPORAN DATA LOKASI </strong><br />
	<?php $tgl=date("d/M/Y"); echo "<strong>Tanggal : $tgl</strong>"; ?>	
	</center><hr /></td>
  </tr>
  <tr align="center" bgcolor="#FFFFCC" >
    <td width="116">ID Lokasi</td>
    <td width="336">Nama Desa/Kelurahan</td>
    <td width="169">Latitude</td>
    <td width="251"><span >Longitudinal</span></td>
    </tr>
  <?php
  include "../koneksi.php";
  //$sql="SELECT  tb_lokasi.id_lokasi,  tb_lokasi.id_desa,  tb_lokasi.id_jenis,  tb_lokasi.nama_tempat,  tb_lokasi.informasi_umum, tb_lokasi.jalan, tb_lokasi.lat, tb_lokasi.lng, tb_desa.id_desa, tb_desa.desa, tb_jenis_fasilitas.id_jenis, tb_jenis_fasilitas.jenis FROM tb_lokasi, tb_desa, tb_jenis_fasiltas ORDER BY tb_lokasi.id_lokasi ASC  ";
 
  $sql="SELECT  tb_lokasi.id_lokasi, tb_lokasi.lat, tb_lokasi.lng, tb_desa.id_desa, tb_desa.desa  FROM tb_lokasi,tb_desa WHERE tb_lokasi.id_desa=tb_desa.id_desa AND tb_lokasi.id_kecamatan='$id_kecamatan'  ORDER BY tb_lokasi.id_lokasi ASC";
  $result=mysql_query($sql);
  while ($baris=mysql_fetch_array($result)){
  ?>
  <tr>
    <td ><?php echo $baris['id_lokasi'];?></td>
    <td><?php echo $baris['desa'];?></td>
    <td><?php echo $baris['lat'];?></td>
    <td><?php echo $baris['lng'];?></td>
  </tr><?php } ?>
  <tr>
    <td colspan="14">&nbsp;</td>
  </tr>
</table>
</body>
</html>
