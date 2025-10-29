<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>GIS Bangunan | Admin</title>
<link href="../images/logo-karo.png" rel="shortcut icon">
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" type="text/css" href="style_img.css">
<link rel="stylesheet" type="text/css" href="form_style.css">
<style type="text/css">
#dialog-overlay {

	/* set it to fill the whil screen */
	width:100%; 
	height:100%;
	
	/* transparency for different browsers */
	filter:alpha(opacity=50); 
	-moz-opacity:0.5; 
	-khtml-opacity: 0.5; 
	opacity: 0.5; 
	background:#000; 

	/* make sure it appear behind the dialog box but above everything else */
	position:absolute; 
	top:0; left:0; 
	z-index:3000; 

	/* hide it by default */
	display:none;
}
#dialog-box {
	
	/* css3 drop shadow */
	-webkit-box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
	-moz-box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
	
	/* css3 border radius */
	-moz-border-radius: 5px;
    -webkit-border-radius: 5px;
	
	background:#eee;
	/* styling of the dialog box, i have a fixed dimension for this demo */ 
	width:500px;
	
	/* make sure it has the highest z-index */
	position:absolute; 
	z-index:5000; 

	/* hide it by default */
	display:none;
}
#dialog-box .dialog-content {
	/* style the content */
	text-align:left; 
	padding:10px; 
	margin:13px;
	color:#666; 
	font-family:arial;
	font-size:11px; 
}
a.button {
	/* styles for button */
	margin:10px auto 0 auto;
	text-align:center;
	background-color: #e33100;
	display: block;
	width:50px;
	padding: 5px 10px 6px;
	color: #fff;
	text-decoration: none;
	font-weight: bold;
	line-height: 1;
	
	/* css3 implementation :) */
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	-moz-box-shadow: 0 1px 3px rgba(0,0,0,0.5);
	-webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.5);
	text-shadow: 0 -1px 1px rgba(0,0,0,0.25);
	border-bottom: 1px solid rgba(0,0,0,0.25);
	position: relative;
	cursor: pointer;
}

a.button:hover {
	background-color: #c33100;	
}

/* extra styling */
#dialog-box .dialog-content p {
	font-weight:700; margin:0;
}

#dialog-box .dialog-content ul {
	margin:10px 0 10px 20px; 
	padding:0; 
	height:50px;
}
</style>
<link href="http://code.google.com/apis/maps/documentation/javascript/examples/standard.css" rel="stylesheet" type="text/css" />
<script src="//maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&key=AIzaSyDunJe4RaSitgdPg-cKqVlo2PNRHL9yzQs" async="" defer="defer" type="text/javascript"></script>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="../wilayah.js"></script>
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
//peta terpilih
//peta awal loading
function peta_awal(){
    var gorontalo = new google.maps.LatLng(3.1131818828698368, 98.4862430846083);
    var petaoption = {
        zoom: 10,
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
	//buat poligon
	//load wilayah
	//load_banda_sakti();
	//load_muara_dua();
	//load_muara_satu();
	//load_blang_mangat();
	//end poligon
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
	//btnInput click
	var tampil=0;
	$("#btnInput").click(function(){
		$("#frmInput").slideDown();
	});
		// btnRefresh klick
	$("#btnRefresh").click(function(){
		//alert("Refresh...");
		//$("#teks").load("daftar_lokasi.php");
		peta_awal();
	});
	var sh=0;
	//tbarKatagori click
	$("#tbarKatagori").click(function(){
		$("#frmKatagori").slideUp();
		sh=0;
		//alert("dafkj");
	});
	//btnSosialA klick
	// btnClose2 klik
	$("#btnClose2").click(function(){
		$("#leftPanel").slideUp();
	});
	// btnKatagori klik
	$("#btnKatagori").click(function(){
		if (sh==0){
			$("#frmKatagori").slideDown();
			sh=1;
		}else{
			$("#frmKatagori").slideUp();
			sh=0;
		}
	});
	//*daftar lokasi
	$("#btnLihat").click(function(){
		$("#teks").load("daftar_lokasi.php");
	});
	$("#tombol_batal").click(function(){
		$("#frmInput").slideUp();
	});
	//--#--
    $("#tombol_simpan").click(function(){
        //var id_desa=$("#cbodesa").val();
		var id_bangunan=$("#cbo_idbangunan").val();
		//var nama_tempat=$("#textnama").val();
		//var informasi_umum=$("#textinformasi").val();
		//var jalan=$("#textjalan").val();
		var x = $("#x").val();
        var y = $("#y").val();
        $("#loading").show();
        $.ajax({
            url: "simpanlokasi.php",
            data: "x="+x+"&y="+y+"&id_bangunan="+id_bangunan,
            cache: false,
            success: function(msg){
                //alert(msg);
				$("#lblStatus").html(msg);
                $("#loading").hide();
                $("#x").val("");
                $("#y").val("");
                //$("#cbodesa").val("");
                $("#cbo_idbangunan").val("");
				//$("#textnama").val("");
				//$("#textinformasi").val("");
				//$("#textjalan").val("");
				//$("#frmInput").slideUp();
                //ambildatabase('akhir');
				//$("#teks").load("daftar_lokasi.php");
            }
        });
    });
    $("#tutup").click(function(){
        $("#jendelainfo").fadeOut();
    });
// message box
// if user clicked on button, the overlay layer or the dialogbox, close the dialog	
	$('a.btn-ok, #dialog-overlay, #dialog-box').click(function () {		
		$('#dialog-overlay, #dialog-box').hide();		
		return false;
	});
	// if user resize the window, call the same function again
	// to make sure the overlay fills the screen and dialogbox aligned to center	
	$(window).resize(function () {
		//only do it if the dialog box is not hidden
		if (!$('#dialog-box').is(':hidden')) popup();		
	});	
/// #end message
});
function kasihtanda(lokasi){
    set_icon(jenis);
    tanda = new google.maps.Marker({
            position: lokasi,
			draggable: true,
            map: peta,
            icon: gambar_tanda
    });
    $("#x").val(lokasi.lat());
    $("#y").val(lokasi.lng());
	$("#namax").focus();
	$("#frmInput").show(200);
}
function set_icon(jenisnya){
    switch(jenisnya){	
		case "belum":
			gambar_tanda='icon/merah.png';
			//$("#txtKatagori").val('Sosial A');
			break;
		case "sudah":
			gambar_tanda='icon/biru.png';
			//$("#txtKatagori").val("Sosial B");
			break;
    }
}
function ambildatabase(akhir){
	var image = 'icon/merah.png';
	var id_jenis,tahun,id_kecamatan;
	id_jenis="<?php echo $_GET['id_jenis'];?>"; //alert(bulan);
	tahun="<?php echo $_GET['tahun'];?>"; //alert(tahun);
	id_kecamatan="<?php echo $_GET['id_kecamatan3'];?>";
	var datanya="&id_jenis="+id_jenis+"&tahun="+tahun+"&id_kecamatan="+id_kecamatan;
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
                //jumlah_rtsx[i] = msg.wilayah.petak[i].jumlah_rts;
				//jumlah_pagux[i] = msg.wilayah.petak[i].jumlah_pagu;
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
				//$("#teks").load("iframe_include/edit_lokasi.php");
				});
				google.maps.event.addListener(tanda, 'dragstart', function() {
				tampil_latlng_e(event.latLng);
				});
				 google.maps.event.addListener(tanda, 'drag', function(event) {
				tampil_latlng_e(event.latLng);
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
        $("#jendelainfo").fadeIn(); $("#jendelacommand").fadeIn();
		$("#textid").val(idlokasix[nomor]);
		$("#textidlokasi2").val(idlokasix[nomor]);	
        $("#teksnama").html(namax[nomor]);
        //$("#teksjumlahrts").html(jumlah_rtsx[nomor]);
		//$("#teksjumlahpagu").html(jumlah_pagux[nomor]);
		$("#textstatusdistribusi").html(jenisx[nomor]);
		$("#cbodesa").val(iddesax[nomor]);
		$("#cbokecamatan").val(idkecamatanx[nomor]);
		$("#textlat_e").val(latx[nomor]);
		$("#textlng_e").val(lngx[nomor]);
		
    });
//cari data anggota
}
// rumus set lokasi peta
function setpeta(x,y,id){
    var lokasibaru = new google.maps.LatLng(x, y);
    var petaoption = {
        zoom: 14,
        center: lokasibaru,
        mapTypeId: google.maps.MapTypeId.SATELLITE
        };
    peta = new google.maps.Map(document.getElementById("petaku"),petaoption);
    tanda = new google.maps.Marker({
        position: lokasibaru,
        map: peta
    });
    var idnya = "#"+id;
    var isistring = $(idnya).html();
    var infowindow = new google.maps.InfoWindow({
        //content: isistring
		content: "<b>STMIK Bina Bangsa</b><BR>" + isistring + "<br>" + 
		"Jln. Merdeka Timur No. 92 Telp.0645 41626/081361610684 Cunda<br>" +  "Latitude (5.171628769510711)" + "Longitudinal(97.13207920887373)"
    });
    google.maps.event.addListener(tanda, 'click', function() {
      infowindow.open(peta,tanda);
    });
    google.maps.event.addListener(peta,'click',function(event){
        kasihtanda(event.latLng);
    });
	}
// loading data
function load_kecamatan(){
	//$("#teks").load("iframe_include/dosen.php");
	$(".ui").fadeIn(200);
	$(".ui").load("iframe_include/kecamatan.php");
	}
function load_desa(){
	$(".ui").fadeIn(300);
	$(".ui").load("iframe_include/desa.php");
	}
function load_distribusi(){
	$(".ui").fadeIn(300);
	$(".ui").load("iframe_include/bangunan.php");
	}
function load_lokasi(){
	//$(".ui").fadeIn(300);
	//$(".ui").load("iframe_include/lokasi.php");
	$("#teks").load("iframe_include/edit_lokasi.php");
	}
function load_jenis(){
	$(".ui").fadeIn(300);
	$(".ui").load("iframe_include/jenis_fasilitas.php");
	}
function load_laporan(){
	//$("#teks").load("iframe_include/laporan.php");
	$(".ui").fadeIn(200);
	$(".ui").load("iframe_include/laporan.php");
	}
function load_home(){
	document.location="admin.php?tahun=Tahun+distribusi&bulan=0&id_kecamatan3=0&btn1=Cari+Data";
	}
// function message
//Popup dialog
function popup(message) {	
	// get the screen height and width  
	var maskHeight = $(document).height();  
	var maskWidth = $(document).width();
	// calculate the values for center alignment
	var dialogTop =  (maskHeight/3) - ($('#dialog-box').height());  
	var dialogLeft = (maskWidth/2) - ($('#dialog-box').width()/2); 
	// assign values to the overlay and dialog box
	$('#dialog-overlay').css({height:maskHeight, width:maskWidth}).show();
	$('#dialog-box').css({top:dialogTop, left:dialogLeft}).show();
	// display the message
	$('#dialog-message').html(message);		
}
// # end pop up
// form_ data
$("#close_bar2").click(function(){
	})
// tampil koordinat mousemove
function tampil_latlng(lokasi){
	$("#textlat2").val(lokasi.lat());
	$("#textlng2").val(lokasi.lng());
	}
function tampil_latlng_e(lokasi){
	$("#textlat_e").val(lokasi.lat());
	$("#textlng_e").val(lokasi.lng());
	}
// # end
//update data marker
function update_data_marker(){
	//var aksi="balai";
	alert("update data");
	var id_lokasi2, nama_tempat2, lat2,lng2;
	id_lokasi=$("#textidlokasi2").val();
	nama_tempat=$("#textalamat2").val();
	lat=$("#textlat_e").val();
	lng=$("#textlng_e").val();
	datanya="&id_lokasi2="+id_lokasi2+"&lat2="+lat2+"&lng2="+lng2+"&nama_tempat2="+nama_tempat2;
		$.ajax({
		url : "update_marker.php",
		data : datanya,
		cache : false,
		success : function(msg){
			if(msg=="sukses_update"){
				alert("data berhasil diupdate ..!");
				//$("#txt_idmesjid").val(""); $("#txtlat").val(""); $("#txtlng").val("");
				//$("#petaku").load();
				
				var validator = $("#data").validate();
				validator.refresh();
				}
			if(msg=="gagal_update"){
				alert("data tidak dapat diupdate ..!");
				}
			}
		})
	}
// # update data

</script>
<script type="text/javascript" src="event.js"></script>
</head>
<body onLoad="peta_awal();">
<div id="info_kecamatan" class="info_kecamatan">Kecamatan</div>
<!--top panel-->
<div class="topPanel">
<div id="">

<!--
Time, time, take us back before the line was drawn
Before the sky turned black. -->

<!-- script src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js" type="text/javascript"></script -->
<script src="jquery.js" type="text/javascript"></script>
</div>
	<!--end menu-->
<div id="dialog-overlay"></div>
</div>
</div>
<!--left panel-->
<div id="leftPanel" style=" font-size:12px;border:1px solid #999; width:375px; height:80%;float:left; background-color:transparent; padding:0px 2px 2px 3px; margin-top:80px;"><!-- left panel data-->
	<div id="teks" style="border:1px solid #999; height:500px; overflow:auto; padding:3px 3px 3px 7px;
	font-family:Arial, Helvetica, sans-serif; font-size:14px; " ><br><center><img src="../images/logo-karo.png" width="30%"></center>
    <form enctype="multipart/form-data" name="formcari" method="get" action="admin.php"><table width="300" border="0">
  <tr>
    <td colspan="3" align="center" bgcolor="#3399FF"><strong>Lihat Data Gedung</strong></td>
    </tr>
  <tr>
    <td width="86">Tahun</td>
    <td width="6">:</td>
    <td width="186"><select name="tahun" id="tahun" style="border-radius:5px 5px 5px 5px; border:3px solid #06F; background:linear-gradient( #CCC, #FFF); padding:3px 3px 3px 3px;">
      <option selected="selected">Tahun Bangunan</option>
      <?php
for($i=date('Y'); $i>=date('Y')-3; $i-=1){
echo"<option value='$i'> $i </option>";
}
?>
    </select></td>
  </tr>
  <tr>
    <td>Jenis Izin</td>
    <td>:</td>
    <td><select name="id_jenis" id="id_jenis" style="border-radius:5px 5px 5px 5px; border:3px solid #06F; background:linear-gradient( #CCC, #FFF); padding:3px 3px 3px 3px;">
      <option value="0" selected="selected" >Jenis Izin </option>
      <?php
	   include "../koneksi.php";
	   $id_jenis=$_GET['id_jenis'];
       $sql_j="SELECT * FROM tb_jenis  ORDER BY id_jenis ASC";
	   $result_j=mysql_query($sql_j);
	   while ($data_j=mysql_fetch_array($result_j)){
	   ?>
      <option value="<?php echo $data_j['id_jenis'];?>">
        <?php echo $data_j['jenis'];?>
        </option>
      <?php } ?>
    </select></td>
  </tr>
  <tr>
    <td>Kecamatan</td>
    <td>:</td>
    <td><select name="id_kecamatan3" id="id_kecamatan3" style="border-radius:5px 5px 5px 5px; border:3px solid #06F; background:linear-gradient( #CCC, #FFF); padding:3px 3px 3px 3px;">
      <option value="0" selected="selected">Kecamatan--</option>
      <?php
	   include "../koneksi.php";
	   $id_kecamatan=$_GET['id_kecamatan'];
	   //$bulan=$_GET['bulan2'];
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
    <td colspan="3" align="center" bgcolor="#33CCCC"><strong>Informasi Jumlah Bangunan</strong></td>
    </tr>
  <tr>
    <td colspan="3" align="center" bgcolor="#33CCCC">Tahun : <?php echo $_GET['tahun'];?></td>
  </tr>
  <tr>
    <td colspan="3" align="center" bgcolor="#33CCCC">Jenis Bangunan : <?php echo $_GET['id_jenis'];?></td>
  </tr>
  <tr>
    <td colspan="3"><img src="icon/biru.png">Sudah Berizin : 
      <?php
	$id_kecamatan=$_GET['id_kecamatan3']; $tahun=$_GET['tahun']; $id_jenis=$_GET['id_jenis']; 
	$qryS=mysql_query("SELECT * FROM tb_bangunan WHERE id_kecamatan='$id_kecamatan' AND tahun='$tahun' AND id_jenis='$id_jenis' AND status_izin='sudah' GROUP BY id_desa");
	$dataS=mysql_num_rows($qryS); echo $dataS;
	?></td>
    </tr>
  <tr>
    <td colspan="3"><img src="icon/merah.png" alt="">Belum Berizin : 
      <?php
	$id_kecamatan=$_GET['id_kecamatan3']; $tahun=$_GET['tahun']; $id_jenis=$_GET['id_jenis']; 
	$qryB=mysql_query("SELECT * FROM tb_bangunan WHERE id_kecamatan='$id_kecamatan' AND tahun='$tahun' AND id_jenis='$id_jenis' AND status_izin='belum' GROUP BY id_desa");
	$dataB=mysql_num_rows($qryB); echo $dataB;
	?></td>
    </tr>
    </table></form>

	</div>
	<!-- end left panel data-->
</div>
<div id="petaku" class="frmPeta" ></div>

<!--form input-->
<div id="frmInput" class="formInput">
	<div id="tbarInput" class="titleBar"><strong>Input Lokasi Bangunan</strong><span class="btnClose" id="btnCloseInput"><a>X</a></span></div>
  <div style="overflow:auto; height:100%; background-color:transparent;padding-left:5px; ">
		<!--<input type=radio checked name=jenis value="restoran" onclick="setjenis(this.value)"><img src="icon/restaurant.png"> Restoran<br>
		<input type=radio name=jenis value="airport" onclick="setjenis(this.value)"><img src="icon/airport.png"> Air Port<br>
		<input type=radio name=jenis value="masjid" onclick="setjenis(this.value)"><img src="icon/mosque.png"> Masjid<br>-->
		<label>&nbsp;</label>
		<div style="display:none;">
			<input type=radio name=jenis value="sosiala" onClick="setjenis(this.value)"><img src="icon/merah.png">Fakultas Teknik<br>
		</div>
		Nama Bangunan :<br>
		<select id="cbo_idbangunan">
        <option value="0" selected>-Pilih Nama Bangunan</option>
         <?php
	   include "../koneksi.php";
       $sql_d="SELECT * FROM  tb_bangunan ORDER BY id_bangunan ASC";
	   $result_d=mysql_query($sql_d);
	   while ($data_d=mysql_fetch_array($result_d)){
	   ?>
        <option value="<?php echo $data_d['id_bangunan'];?>"><?php echo $data_d['nama_pemilik'];?></option>
        <?php } ?>
        </select><br>
		Latitude : <br><input type="text" id="x"><br>
		longitude : <br><input type="text" id="y"><br><br>
		<button id="tombol_simpan">Simpan Data Bangunan</button>
		<button id="tombol_batal">Batal</button><br><label id="lblStatus" style="color:#FFCC00; font-weight:bold; "></label>
		<img src="ajax-loader.gif" style="display:none" id="loading">
	</div>
</div>
<div id="jendelacommand" align="left"><span><strong>Event Marker</strong><br>Untuk edit lokasi marker :
<ul style="list-style:decimal">
	<li>Klik menu Data Lokasi</li>
    <li>Klik pada marker yang akan di ubah</li>
    <li>Drag/Geser marker ke lokasi yang ingin di pindah</li>
    <li>Klik Tombol Update pada Edit Data Lokasi</li>
</ul></span></div>
<div id="jendelainfo" align="center">
	<table  border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#FFCC00" width="100%">
	  <tr><td width="248" bgcolor="#000000" style="color:#FFF;" height="12"><strong>Informasi Bangunan Berizin</strong></td>
		<td width="30" bgcolor="#000000" >
		<p align="center"><font color="#FFFFFF" ><a style="cursor:pointer" id="tutup"><b>X</b></a></font></td>
	  </tr>
	  <tr>
		<td width="290" bgcolor="" height="100" valign="top" colspan="2">
        <font>Nama Bangunan: <strong><span id="teksnama"></span></strong></font>
		<p align="left">Jumlah Bangunan : <strong><span id="teksjumlahrts"></span></strong></p>
        <font>Jumlah Bangunan Tidak Berizin : </font><strong><span id="teksjumlahpagu"></span></strong><br>
        <font>Status Izin Bangunan : </font><strong><span id="textstatusdistribusi"></span></strong>
        <div id="data_anggota"></div>
        </td>
	  </tr>
	</table></div>
<!-- panel bawah-->
<div class="bottomPanel">
<ul> 
<li style="background-image:url(../icon/home.png);"><a  style="background-image:url(../icon/home.png); background:linear-gradient(  #36C, #6CF); opacity:0.5;" onClick="load_home();" >Home</a></li>
    <li><a  id="form_mahasiswa" style="background-image:url(../icon/kecamatan.png); background:linear-gradient(  #36C, #6CF); opacity:0.5;" onClick="load_kecamatan();">Kecamatan</a></li>
    <li><a id="form_desa" style="background-image:url(../icon/desa.png); background:linear-gradient( #63C, #66F); opacity:0.5;" onClick="load_desa();">Desa</a>
    <li><a id="form_lokasi" style="background-image:url(../icon/jenis.png); background:linear-gradient( #66C, #69C); opacity:0.5;" onClick="load_distribusi();">Data Bangunan</a></li>
    <li><a id="form_lokasi" style="background-image:url(../icon/jenis.png); background:linear-gradient( #66C, #69C); opacity:0.5;" onClick="load_jenis();">Jenis IMB</a></li>
    <li><a id="form_lokasi" style="background-image:url(../icon/lokasi.png); background:linear-gradient( #096, #9F3); opacity:0.5;" onClick="load_lokasi();">Data Lokasi</a></li>
    <li><a id="form_laporan" style="background-image:url(../icon/laporan.png); background:linear-gradient( #F90, #FF6); opacity:0.5;" onClick="load_laporan();">Data Laporan</a></li>
    <li><a href="logout.php" id="logout" style="background-image:url(../icon/logout.png); background:linear-gradient( #0FF, #9FF); opacity:0.5;" onClick="logout();">Logout</a></li>
</ul>
<div style="position:relative; float:left; margin-left:50px;"><h3 style="font-family:'Palatino Linotype', 'Book Antiqua', Palatino, serif; font-size:16pt; color:#036; ">SISTEM INFORMASI GEOGRAFIS (SIG)</h3>
<h3 style="color:#606;">PEMETAAN GEDUNG BERIZIN</h3>
<form name="frmtop" style="color:#C00; font-family:Verdana, Geneva, sans-serif;">
<label for="textlat2">Latitude : <input type="text" id="textlat2" style="border-radius:5px 5px 5px 5px; border:3px solid #06F; background:linear-gradient(#9C0, #9F9);"></label>
<label for="textlng2">Longitudinal : <input type="text" id="textlng2" style="border-radius:5px 5px 5px 5px; border:3px solid #06F; background:linear-gradient(#9C0, #9F9);"></label>
</form>
</div>
</div>

<!-- daftar Lokasi-->
<div id="formLookLoc" class="frmLookLoc">
	<div>
		<label>Pilih Lokasi</label>
		<ul>
			<li><a id="optBandasakti" >Banda Sakti</a></li>
			<li><a>Muara Dua</a></li>
			<li><a>Muara Satu</a></li>
			<li><a>Blang Mangat</a></li>
		</ul>
	</div>
</div>

<!-- end daftar lokasi-->
<div id="frmKatagori" class="formKatagori">
	<div id="tbarKatagori" class="titleBar"><strong>Jenis</strong></div>
	<div style="overflow:auto; height:90%; font-size:10px; font-weight:bold;">
	</div>
</div>
<!--Wilayah-->
<div class="ui" id="ui"><div class="ui_title_bar"><span class="close_bar" id="close_bar2"><strong>X</strong></span></div></div>
<div id="frm_wilayah" class="form_wilayah"></div>
<div id="frm_golongan" class="form_golongan"></div>
<div id="frm_pelanggan" class="form_pelanggan"></div>
<script type="text/javascript" src="event.js"></script>
<script type="text/javascript" src="setlocation.js"></script>
<script type="text/javascript" src="blok.js"></script>
<script type="text/javascript" src="blokdesa.js"></script>
</body>
</html>
