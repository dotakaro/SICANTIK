<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>GIS Izin Bangunan</title>
<link href="images/logo-karo.gif" rel="shortcut icon">
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" type="text/css" href="style_img.css">
<link rel="stylesheet" type="text/css" href="form_style.css">
<style type="text/css">
/* menu styles */
#jsddm
{	margin: 0;
	padding: 0}

	#jsddm li
	{	float: left;
		list-style: none;
		font: 12px Tahoma, Arial}

	#jsddm li a
	{	display: block;
		background: #324143;
		padding: 5px 12px;
		text-decoration: none;
		border-right: 1px solid white;
		width: 70px;
		color: #EAFFED;
		white-space: nowrap}

	#jsddm li a:hover
	{	background: #24313C}
		
		#jsddm li ul
		{	margin: 0;
			padding: 0;
			position: absolute;
			visibility: hidden;
			border-top: 1px solid white}
		
			#jsddm li ul li
			{	float: none;
				display: inline}
			
			#jsddm li ul li a
			{	width: auto;
				background: #A9C251;
				color: #24313C}
			
			#jsddm li ul li a:hover
			{	background: #8EA344}
</style>
<link href="http://code.google.com/apis/maps/documentation/javascript/examples/standard.css" rel="stylesheet" type="text/css" />
<script src="//maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&key=AIzaSyDunJe4RaSitgdPg-cKqVlo2PNRHL9yzQs" async="" defer="defer" type="text/javascript"></script>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript">
var peta;
var pertama = 0;
var jenis="restoran";
var namax=new Array();
var jumlah_rtsx=new Array();
var jumlah_pagux=new Array();
var desx=new Array();
var judulx=new Array();
var idlokasix=new Array();
var iddesax=new Array();
var id_jenisx=new Array();
var jenisx=new Array();
var jalanx=new Array();
var idkecamatanx=new Array();
var latx=new Array();
var lngx=new Array();
var i;
var url;
var gambar_tanda;
var shape;
//peta awal loading
function peta_awal(){
    var gorontalo = new google.maps.LatLng(3.1131818828698368,98.4862430846083);
    var petaoption = {
        zoom: 12,
        center: gorontalo,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
		navigationControl: true,
          navigationControlOptions: {
              style: google.maps.NavigationControlStyle.ZOOM_PAN,
              position: google.maps.ControlPosition.TOP_RIGHT
          },
		  scaleControl: true,
          scaleControlOptions: {
              position: google.maps.ControlPosition.BOTTOM_RIGHT
          }
		  //poligon
        };
    peta = new google.maps.Map(document.getElementById("petaku"),petaoption);
	peta.setTilt(45);
  	peta.setHeading(90);
    google.maps.event.addListener(peta,'click',function(event){kasihtanda(event.latLng);});
	google.maps.event.addListener(peta, "mousemove",function(event){tampil_latlng(event.latLng);});
    ambildatabase('awal');
}
//google.maps.event.addDomListener(window, 'load', initialize);
// function point
function addPoint(e) {
        var vertices = shape.getPath();
        vertices.push(e.latLng);
      }
//peta pilihan
//
$(document).ready(function(){

	});
	
function kasihtanda(lokasi){
    set_icon(jenis);
    tanda = new google.maps.Marker({
            position: lokasi,
			draggable: false,
            map: peta,
            icon: gambar_tanda
    });
}
function set_icon(jenisnya){
    switch(jenisnya){	
		case "belum":
			gambar_tanda='icon/merah.png';
			break;
		case "sudah":
			gambar_tanda='icon/biru.png';
			break;
    }
}
function ambildatabase(akhir){
	var image = 'icon/merah.png';
	var bulan,tahun,id_kecamatan;
	bulan="<?php echo $_GET['bulan'];?>"; //alert(bulan);
	tahun="<?php echo $_GET['tahun'];?>"; //alert(tahun);
	id_kecamatan="<?php echo $_GET['id_kecamatan3'];?>";
	var datanya="&bulan="+bulan+"&tahun="+tahun+"&id_kecamatan="+id_kecamatan;
    if(akhir=="akhir"){
        url = "ambildata.php?akhir=1";
    }else{
        url = "ambildata.php?akhir=0";
    }
    $.ajax({
        url: url,
        dataType: 'json',
		data:datanya,
        cache: false,
        success: function(msg){
            for(i=0;i<msg.wilayah.petak.length;i++){
                namax[i] = msg.wilayah.petak[i].judul;
                jumlah_rtsx[i] = msg.wilayah.petak[i].jumlah_rts;
				jumlah_pagux[i] = msg.wilayah.petak[i].jumlah_pagu;
				jenisx[i] = msg.wilayah.petak[i].jenis;
				latx[i]=msg.wilayah.petak[i].x;
				lngx[i]=msg.wilayah.petak[i].y;
				idlokasix[i] = msg.wilayah.petak[i].idlokasi;
				idkecamatanx[i]=msg.wilayah.petak[i].id_kecamatan;
				iddesax[i]=msg.wilayah.petak[i].id_desa;
                set_icon(msg.wilayah.petak[i].jenis);
                var point = new google.maps.LatLng(
                    parseFloat(msg.wilayah.petak[i].x),
                    parseFloat(msg.wilayah.petak[i].y));
                tanda = new google.maps.Marker({
                    position: point,
                    map: peta,
					draggable: true,
                    icon: gambar_tanda
                });
                setinfo(tanda,i);
				google.maps.event.addListener(tanda, 'click', function() {
				
				});
				google.maps.event.addListener(tanda, 'dragstart', function() {
				
				});
				 google.maps.event.addListener(tanda, 'drag', function(event) {
				
				});
				google.maps.event.addListener(tanda, 'dragend', function(event) {
				});

            }
        }
    });
}
function setjenis(jns){
    jenis = jns;
}
function setinfo(petak, nomor){
    google.maps.event.addListener(petak, 'mouseover', function() {
        $("#jendelainfo").fadeIn(); 
		$("#textid").val(idlokasix[nomor]);
		$("#textidlokasi2").val(idlokasix[nomor]);	
        $("#teksnama").html(namax[nomor]);
        $("#teksjumlahrts").html(jumlah_rtsx[nomor]);
		$("#teksjumlahpagu").html(jumlah_pagux[nomor]);
		$("#textstatusdistribusi").html(jenisx[nomor]);
		
    });
}

</script>
</head>
<body onLoad="peta_awal();">
<!--top panel--><!--left panel-->
<div id="leftPanel" style=" font-size:12px;border:1px solid #999; width:350px; height:100%;float:left;   padding:5px 2px 2px 3px; margin-top:0px;">
  <center><h3><strong>DINAS PMPPTSP KABUPATEN KARO</strong></h3></center>
<p align="center"><strong>SISTEM INFORMASI GEOGRAFIS PEMETAAN GEDUNG BERIZIN</strong></p><hr>
<div style="background-color:#FFF;">
<span id="btnLihat" style="cursor:pointer; "><a><strong>Load Data</strong></a></span>&nbsp;|&nbsp;<span id="btnRefresh" style="color:#0099FF; cursor:pointer; "><strong>Refresh</strong></span> | <a target="_blank" href="admin/index.php">Admin</a>
</div>
<div style="background-color:#FFF;"></div>
    <!-- left panel data-->
	<div id="teks" style="border:1px solid #999; bottom:0px; height:100%; overflow:auto; padding:3px 3px 3px 7px;
	font-family:Arial, Helvetica, sans-serif; font-size:14px; background-color:#FFF;"><form method="get"  name="formcari"  action="index.php"><table width="300" border="0">
  <tr>
    <td colspan="3" align="center" bgcolor="#3399FF"><strong>Lihat Data Bangunan Berizin</strong></td>
    </tr>
  <tr>
    <td width="86">Tahun</td>
    <td width="6">:</td>
    <td width="186"><select name="tahun" id="tahun" style="border-radius:5px 5px 5px 5px; border:3px solid #06F; background:linear-gradient( #CCC, #FFF); padding:3px 3px 3px 3px;">
      <option selected="selected">Tahun </option>
      <?php
for($i=date('Y'); $i>=date('Y')-3; $i-=1){
echo"<option value='$i'> $i </option>";
}
?>
    </select></td>
  </tr>
  <tr>
    <td>Bulan</td>
    <td>:</td>
    <td><select name="bulan" id="bulan" style="border-radius:5px 5px 5px 5px; border:3px solid #06F; background:linear-gradient( #CCC, #FFF); padding:3px 3px 3px 3px;">
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
    </select></td>
  </tr>
  <tr>
    <td>Kecamatan</td>
    <td>:</td>
    <td><select name="id_kecamatan3" id="id_kecamatan3" style="border-radius:5px 5px 5px 5px; border:3px solid #06F; background:linear-gradient( #CCC, #FFF); padding:3px 3px 3px 3px;">
      <option value="0" selected="selected">Kecamatan--</option>
      <?php
	   include "koneksi.php";
	   $id_kecamatan=$_GET['id_kecamatan'];
	   $bulan=$_GET['bulan2'];
       $sql_d="SELECT * FROM tb_kecamatan   ORDER BY id_kecamatan ASC";
	   $result_d=mysql_query($sql_d);
	   while ($data_d=mysql_fetch_array($result_d)){
	   ?>
      <option value="<?php echo $data_d['id_kecamatan'];?>">
        <?php echo $data_d['kecamatan'];?>
        </option>
      <?php } ?>
    </select></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input type="submit" id="btn1" name="btn1" value="Cari Data"  /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center" bgcolor="#33CCCC"><strong>Informasi Jumlah Bangunan Berizin</strong></td>
    </tr>
  <tr>
    <td colspan="3" align="center" bgcolor="#33CCCC">Tahun : <?php echo $_GET['tahun'];?></td>
  </tr>
  <tr>
    <td colspan="3" align="center" bgcolor="#33CCCC">Bulan : <?php echo $_GET['bulan'];?></td>
  </tr>
  <tr>
    <td colspan="3"><img src="icon/biru.png">Sudah berizin :
    <?php
	$id_kecamatan=$_GET['id_kecamatan3']; $tahun=$_GET['tahun']; $bulan=$_GET['bulan']; 
	$qryS=mysql_query("SELECT * FROM tb_distribusi WHERE id_kecamatan='$id_kecamatan' AND tahun='$tahun' AND bulan='$bulan' AND status_distribusi='sudah' GROUP BY id_desa");
	$dataS=mysql_num_rows($qryS); echo $dataS;
	?>
    </td>
    </tr>
  <tr>
    <td colspan="3"><img src="icon/merah.png" alt="">Belum berizin:
     <?php
	$id_kecamatan=$_GET['id_kecamatan3']; $tahun=$_GET['tahun']; $bulan=$_GET['bulan']; 
	$qryB=mysql_query("SELECT * FROM tb_distribusi WHERE id_kecamatan='$id_kecamatan' AND tahun='$tahun' AND bulan='$bulan' AND status_distribusi='belum' GROUP BY id_desa");
	$dataB=mysql_num_rows($qryB); echo $dataB;
	?></td>
    </tr>
    </table></form>
	</div>
<!-- end left panel data--></div>
<div id="petaku" class="frmPeta" ></div>
<div id="form_lokasi" style=" position:fixed; bottom:50px; left:200px; display:none; z-index:9999;background-color:yellow;width:300px; text-align:left;padding:0px 0px 20px 0px; border-bottom:2px solid #666666; border-left:1px solid #666666; border-right:2px solid #FFFFFF;">
<div style="background-color:#CCCCCC; padding:1px 1px 1px 5px; border-bottom:2px solid #999999; border-top:2px solid #999999;">Masukkan Data<span  id="btnClose" style="float:right; cursor:pointer; color:#006699; background-color:#FF9900; padding:2px 2px 0px 2px;"><strong>X</strong></span></div>
	<div style="padding:5px 0px 1px 5px;  ">
	
		X &nbsp;<input type=text name=latlng id=cx size=25><br>
		Y &nbsp;<input type=text name=latlngy id=cy size=25><br>
		Nama Lokasi : <br>&nbsp;&nbsp;&nbsp;&nbsp;<input type=text name="nama" id="namax"><br>
		Desrkripsi :<br>&nbsp;&nbsp;&nbsp;&nbsp;
		<!--<textarea cols=20 rows=2 name="deskripsi2" id="deskripsi"></textarea><br>-->
		
		<button id="simpan">Simpan</button>
		<button id="batal">Batal</button>
		<img src="ajax-loader.gif" style="display:none" id="loading">
	</div>
</div>
<!--form input-->
<div id="jendelainfo" align="center">
	<table  border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#FFCC00" width="100%">
	  <tr><td width="248" bgcolor="#000000" style="color:#FFF;" height="12"><strong>Informasi Bangunan</strong></td>
		<td width="30" bgcolor="#000000" >
		<p align="center"><font color="#FFFFFF" ><a style="cursor:pointer" id="tutup"><b>X</b></a></font></td>
	  </tr>
	  <tr>
		<td width="290" bgcolor="" height="100" valign="top" colspan="2">
        <font>Nama Bangunan : <strong><span id="teksnama"></span></strong></font>
		<p align="left">Jumlah Bangunan: <strong><span id="teksjumlahrts"></span></strong></p>
        <font>Jumlah Bangunan Berizin : </font><strong><span id="teksjumlahpagu"></span></strong><br>
        <font>Status Bangunan : </font><strong><span id="textstatusdistribusi"></span></strong>
        <div id="data_anggota"></div>
        </td>
	  </tr>
	</table></div>
<!-- panel bawah--><!-- daftar Lokasi-->
</body>
</html>
