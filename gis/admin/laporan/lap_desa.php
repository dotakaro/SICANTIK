<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Laporan Data Desa</title>
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
</form>
<table  id="tabel1" width="700" border="1" cellspacing="0" cellpadding="1">
  <tr align="center">
    <td colspan="15" valign="top">
	
	<center>
	<strong><img width="90" height="90" alt="no image here" style="float:left;" src="../../images/logo_lsm.gif" />PEMERINTAH KOTA GORONTALO</strong><br />
	</center><center>
	  <strong>LAPORAN DATA DESA</strong>
	</center><br />
	<?php $tgl=date("d/M/Y"); echo "<strong>Tanggal : $tgl</strong>"; ?>	</td>
  </tr>
  <tr align="center" bgcolor="#BCD0F5">
    <td width="87"><span >ID Desa</span></td>
    <td width="351"><span >Nama Desa</span></td>
    <td width="248"><span >Kecamatan</span></td>
    </tr>
  <?php
  include "../koneksi.php";
  $sql="SELECT  tb_desa.id_desa,  tb_desa.desa,  tb_desa.id_kecamatan, tb_kecamatan.kecamatan FROM  tb_desa, tb_kecamatan WHERE  tb_desa.id_kecamatan=tb_kecamatan.id_kecamatan";
  $result=mysql_query($sql);
  while ($baris=mysql_fetch_array($result)){
  ?>
  <tr>
    <td><?php echo $baris['id_desa'];?></td>
    <td><?php echo $baris['desa'];?></td>
    <td><?php echo $baris['kecamatan'];?></td>
  </tr><?php } ?>
  <tr>
    <td colspan="14">&nbsp;</td>
  </tr>
</table>
<iframe style="height:1px" src="" frameborder=0 width=1></iframe>
</body>
</html>
