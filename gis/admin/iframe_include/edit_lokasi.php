<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="../jquery.js"></script>
<script type="text/javascript">
$(document).ready(function(){
//$("#txtidpetugas").keydown(function(){
		//alert("key down");
		//})
	document.getElementById("btnsubmit").disabled=true;
	//$("#btnsubmit").disabled=true;
	$("#textnidn").keypress(function(){
		var nidn; nidn=$("#textnidn").val();
		//$("#statustext").html(kdpetugas);
		})
	$("#textnidn").keyup(function(){
		var nidn; nidn=$("#textnidn").val();
		//$("#statustext").html(idpetugas);
		kode=$("#textnidn").val();
		if(kode==""){ document.getElementById("btnsubmit").disabled=true; }
		$("#status").show();
		var datanya;
		//datanya="&idpetugas"+idpetugas;
		$.ajax({
		url: "iframe_include/cari_dosen.php",
		data: "&nidn="+nidn,
		cache: false,
		success: function(msg){
			data=msg.split("|");
			callback=data[0];
			//$("#textdata").html(callback);
			if(callback=="nodata"){
				$("#statustext").html("");
				$("#textnama").val("");
				$("#texttmplahir").val("");
				$("#texttgllahir").val("");
				$("#textfakultas").val("");
				$("#textjabatan").val("");
				$("#textalamat").val("");
				document.getElementById("btnsubmit").disabled=false;
				//disabled button
				kode2=$("#textnidn").val();
				if(kode2==""){ document.getElementById("btnsubmit").disabled=true; }
				$("#status").hide();
				}
			//if(msg=="adadata"){
			if(callback=="adadata"){
				$("#statustext").html("Data Telah Ada..!");
				$("#textnama").val(data[1]);
				$("#texttmplahir").val(data[2]);
				$("#texttgllahir").val(data[3]);
				$("#textfakultas").val(data[4]);
				$("#textjabatan").val(data[5]);
				$("#textalamat").val(data[6]);
				document.getElementById("btnsubmit").disabled=true;
				$("#status").hide();
			}
			}
			})
		})
	})
// hapus lokasi
function hapus_data(xdata){
	var data_hapus=xdata;
	var aksi="lokasi";
	var datanya="&data_hapus="+data_hapus+"&aksi="+aksi;
	var r=confirm("Yakin ingin menghapus data..?");
	if (r==true){
		//hapus data
		$.ajax({
			url: "iframe_include/hapus.php",
			data : datanya,
			cache : false,
			success : function (msg){
				if(msg=="sukses"){alert ("Data telah dihapus..!"); $(".ui").load("iframe_include/lokasi.php");}
				}
			})
		}else{
			//alert("no");
			}
	}
function simpan_data(){
	var nidn,nama,jk,tmplahir,tgllahir,fakultas,jabatan,alamat;
	var data;
	nidn=$("#textnidn").val();
	nama=$("#textnama").val();
	jk=$("#cbojk").val();
	tmplahir=$("#texttmplahir").val();
	tgllahir=$("#texttgllahir").val();
	fakultas=$("#textfakultas").val();
	jabatan=$("#textjabatan").val();
	alamat=$("#textalamat").val();
	if(nidn==""){ alert("Masukkan kode dosen"); return false;}
	else if(nama==""){ alert("Masukkan nama..!"); return false;}
	else if(tmplahir==""){ alert("Masukkan tempat lahir!"); return false;}
	else if(tgllahir==""){ alert("Masukkan tanggal lahir..!"); return false;}
	datanya="&nidn="+nidn+"&nama="+nama+"&jk="+jk+"&tmplahir="+tmplahir+"&tgllahir="+tgllahir; //+"&fakultas="+fakultas+"&jabatan="+jbatan+"&alamat="+alamat;
	$.ajax({
		url : "iframe_include/simpan_dosen.php",
		data : datanya,
		cache : false,
		success : function(msg){
			if(msg=="sukses"){
				alert("Data berhasil disimpan");
				$("#textnidn").val("");
				$("#textnama").val("");
				$("#cbojk").val("");
				$("#texttmplahir").val("");
				$("#texttgllahir").focus();
				//$("#data_wilayah").fadeOut('slow').load("iframe_include\wilayah.php").fadeIn('slow');
				//$("#data_wilayah").load("iframe_include\wilayah.php");
				var validator = $("#data").validate();
				validator.refresh();
				}
			if(msg=="gagal"){
				alert("data tidak dapat disimpan");
				}
			}
		})
	}
function konfirmasi(kd_gejala){
	var kd_hapus=kd_gejala;
	var url_str;
	url_str="hpsgejala.php?kdhapus="+kd_hapus;
	var r=confirm("Yakin ingin menghapus data..?"+kd_hapus);
	if (r==true){   
		window.location=url_str;
		}else{
			//alert("no");
			}
	}
// edit data
function edit_data(xid_lokasi, xkd_desa, xalamat, xkd_kelompok, xlat, xlng){
	var id_lokasi=xid_lokasi, kd_desa=xkd_desa, alamat=xalamat, kd_kelompok=xkd_kelompok, lat=xlat, lng=xlng;
	//alert(alamat);
	$("#textid").val(id_lokasi);
	$("#textdesa2").val(kd_desa);
	$("#textkelompok2").val(kd_kelompok);
	$("#textalamat2").val(alamat);
	$("#textlat_e").val(lat);
	$("#textlng_e").val(lng);
	//$("#textkodewil").attr("disabled","disabled");
	$("#lblket").text("Edit Data Anggota");
}
//update data
function update_data(){
	var datanya;
	var id_lokasi, id_desa, id_kecamatan, lat, lng;
	id_lokasi=$("#textidlokasi2").val();
	id_desa=$("#cbodesa").val();
	id_kecamatan=$("#cbokecamatan").val();
	//nama_tempat=$("#textalamat2").val();
	//informasi_umum=$("#textinformasi").val();
	//jalan=$("#textjalan2").val();
	lat=$("#textlat_e").val();
	lng=$("#textlng_e").val();
	//alert(lng);
	datanya="&id_lokasi="+id_lokasi+"&lat="+lat+"&lng="+lng+"&id_kecamatan="+id_kecamatan+"&id_desa="+id_desa;
		$.ajax({
		url : "iframe_include/update_marker.php",
		data : datanya,
		cache : false,
		success : function(msg){
			//alert(msg);
			//$("#petaku").load();
			if(msg=="sukses_update"){ alert("data berhasil diupdate ..!"); $("#petaku").load();}
			if(msg=="gagal_update"){ alert("data tidak dapat diupdate ..!");
				}
			}
		})
	}
// #update
//close form
function close_form(){
	$("#ui").fadeOut(200);
	//document.getElementById("ui").hide();
	}
</script>
<style type="text/css">
form { font-family:Arial, Helvetica, sans-serif; font-size:12px;}
form input, select { font-weight:bold;}
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
	width:328px; 
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
</head>
<body>
<br /><br />
<table width="100%" border="0">
  <tr bgcolor="#FFCC00">
    <td width="8%"><img  src="../icon/lokasi.png" /></td>
    <td width="83%" align="center"><h3>Edit Data Lokasi</h3></td>
    <td width="9%" align="right"><input  type="button" onclick="close_form();" value=" X " /></td>
  </tr>
</table><hr />
<form name="form1">
<table width="369" border="0" cellpadding="3" cellspacing="3">
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="63">ID Lokasi</td>
      <td width="3">:</td>
      <td width="244"><input type="text" name="textid" id="textid" class="text ui-widget-content ui-corner-all" size="5" maxlength="5" /><label style="color:red;" id="statustext"><input type="hidden" id="textidlokasi2" /></label></td>
    </tr>
    <tr>
      <td> Desa</td>
      <td>:</td>
      <td><select  id="cbodesa">
        <option value="0">-Pilih Desa-</option>
        <?php
        include "../koneksi.php";
       $sql_ds="SELECT * FROM tb_desa ORDER BY id_desa ASC";
	   $result_ds=mysql_query($sql_ds);
	   while ($data_ds=mysql_fetch_array($result_ds)){
	   ?>
        <option value="<?php echo $data_ds['id_desa'];?>"><?php echo $data_ds['desa'];?></option>
        <?php } ?>
        </select></td>
    </tr>
    <tr>
      <td>Kecamatan</td>
      <td>:</td>
      <td><select id="cbokecamatan">
        <option value="0" selected>-Pilih Kecamatan-</option>
         <?php
	   include "../koneksi.php";
       $sql_d="SELECT * FROM  tb_kecamatan ORDER BY id_kecamatan ASC";
	   $result_d=mysql_query($sql_d);
	   while ($data_d=mysql_fetch_array($result_d)){
	   ?>
        <option value="<?php echo $data_d['id_kecamatan'];?>"><?php echo $data_d['kecamatan'];?></option>
        <?php } ?>
        </select></td>
    </tr>
    <tr>
      <td>Lat</td>
      <td>:</td>
      <td><input type="text" name="textlat_e" id="textlat_e" class="text ui-widget-content ui-corner-all" size="30" maxlength="30" /></td>
    </tr>
    <tr>
      <td>Lng</td>
      <td>:</td>
      <td><input type="text" name="textlng_e" id="textlng_e" class="text ui-widget-content ui-corner-all" size="30" maxlength="30" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="button" id="" name="" value="Update" onclick="return update_data();" />
      <input type="reset" value="Reset" /></td>
    </tr>
  </table><br />
</form>
<iframe style="height:1px" src="" frameborder=0 width=1></iframe>
</body>
</html>