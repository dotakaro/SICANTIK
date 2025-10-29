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
	//document.getElementById("btnsubmit").disabled=true;
	//$("#btnsubmit").disabled=true;
	$("#textkdkelompok").keypress(function(){
		var id_cari; id_cari=$("#textkdkelompok").val();
		//$("#statustext").html(kdpetugas);
		})
	$("#textkdkelompok").keyup(function(){
		var id_cari; id_cari=$("#textkdkelompok").val();
		//$("#statustext").html(idpetugas);
		kode=$("#textkdkelompok").val();
		if(kode==""){ document.getElementById("btnsubmit").disabled=true; }
		$("#status").show();
		var datanya;
		//datanya="&idpetugas"+idpetugas;
		$.ajax({
		url: "iframe_include/cari_jenis.php",
		data: "&id_cari="+id_cari,
		cache: false,
		success: function(msg){
			data=msg.split("|");
			callback=data[0];
			//$("#textdata").html(callback);
			if(callback=="nodata"){
				$("#statustext").html("");
				$("#textjenis").val("");
				$("#textdeskripsi").val("");
				document.getElementById("btnsubmit").disabled=false;
				//disabled button
				kode2=$("#textkdkelompok").val();
				if(kode2==""){ document.getElementById("btnsubmit").disabled=true; }
				$("#status").hide();
				}
			//if(msg=="adadata"){
			if(callback=="adadata"){
				$("#statustext").html("Data Telah Ada..!");
				$("#textjenis").val(data[1]);
				$("#textdeskripsi").val(data[2]);
				document.getElementById("btnsubmit").disabled=true;
				$("#status").hide();
			}
			}
			})
		})
	}) 
function hapus_data(xdata){
	var data_hapus=xdata;
	var aksi="bangunan";
	var datanya="&data_hapus="+data_hapus+"&aksi="+aksi;
	var r=confirm("Yakin ingin menghapus data id "+data_hapus+" ..?");
	if (r==true){
		//hapus data
		$.ajax({
			url: "iframe_include/hapus.php",
			data : datanya,
			cache : false,
			success : function (msg){
				if(msg=="sukses"){alert ("Data telah dihapus..!"); $(".ui").load("iframe_include/bangunan.php");}
				}
			})
		}else{
			//alert("no");
			}
	}
function clear_form(){
	$("#textnama").val("");
	$("#texttmplahir").val("");
	}
function simpan_data(){
	var id_bangunan,nama_pemilik,alamat_pemilik,telp,nomor_bukti_diri,fungsi_gedung,luas_tanah,jalan,id_desa,id_kecamatan,status_izin,tahun,id_jenis;
	var data;
	id_bangunan=$("#id_bangunan").val();
	nama_pemilik=$("#nama_pemilik").val();
	alamat_pemilik=$("#alamat_pemilik").val();
	telp=$("#telp").val();
	nomor_bukti_diri=$("#nomor_bukti_diri").val();
	fungsi_gedung=$("#fungsi_gedung").val();
	luas_tanah=$("#luas_tanah").val();
	jalan=$("#jalan").val();
	id_desa=$("#id_desa").val();
	id_kecamatan=$("#id_kecamatan2").val();
	status_izin=$("#status_izin").val();
	tahun=$("#tahun2").val();
	id_jenis=$("#id_jenis2").val();
	if(id_bangunan==""){ alert("Masukkan data..!"); return false;}
	datanya="&id_bangunan="+id_bangunan+"&nama_pemilik="+nama_pemilik+"&alamat_pemilik="+alamat_pemilik+"&telp="+telp+"&nomor_bukti_diri="+nomor_bukti_diri+"&fungsi_gedung="+fungsi_gedung+"&luas_tanah="+luas_tanah+"&jalan="+jalan+"&id_desa="+id_desa+"&id_kecamatan="+id_kecamatan+"&status_izin="+status_izin+"&tahun="+tahun+"&id_jenis="+id_jenis;
	$.ajax({
		url : "iframe_include/simpan_bangunan.php",
		data : datanya,
		cache : false,
		success : function(msg){
			if(msg=="sukses"){
				alert("Data berhasil disimpan"); $(".ui").load("iframe_include/bangunan.php")
				$("#id_bangunan").val("");
				$("#id_desa").val("");
				$("#id_kecamatan2").focus();
				$("#telp").val("");
				$("#nomor_bukti_diri").focus();
				var validator = $("#data").validate();
				validator.refresh();
				}
			if(msg=="gagal"){
				alert("data tidak dapat disimpan");
				}
			}
		})
	}
// edit data
function edit_data(xid_bangunan,xnama_pemilik,xalamat_pemilik,xtelp,xnomor_bukti_diri,xfungsi_gedung,xluas_tanah,xjalan,xid_desa,xid_kecamatan,xstatus_izin,xtahun,xid_jenis){
	
	var id_bangunan=xid_bangunan;
	var nama_pemilik=xnama_pemilik;
	var alamat_pemilik=xalamat_pemilik;
	var telp=xtelp;
	var nomor_bukti_diri=xnomor_bukti_diri;
	var fungsi_gedung=xfungsi_gedung;
	var luas_tanah=xluas_tanah;
	var jalan=xjalan;  var id_desa=xid_desa;
	var id_kecamatan=xid_kecamatan;  var status_izin=xstatus_izin;
	var tahun=xtahun;  
	var id_jenis2=xid_jenis;
	$("#textid").val(id_bangunan);
	$("#id_bangunan").val(id_bangunan);	
	$("#nama_pemilik").val(nama_pemilik);
	$("#alamat_pemilik").val(alamat_pemilik);
	$("#telp").val(telp);
	$("#nomor_bukti_diri").val(nomor_bukti_diri);
	$("#fungsi_gedung").val(fungsi_gedung);
	$("#luas_tanah").val(luas_tanah);
	$("#jalan").val(jalan);
	$("#id_desa").val(id_desa); $("#id_desa").attr("value",id_desa);
	$("#id_kecamatan2").val(id_kecamatan); $("#id_kecamatan2").attr("value",id_kecamatan);
	$("#status_izin").val(status_izin); $("#status_izin").attr("value",status_izin);
	$("#tahun2").val(tahun); 
	$("#id_jenis2").val(id_jenis2); $("#id_jenis2").attr("value",id_jenis2);
}
//update data
function update_data(){
	var datanya;
	var aksi="bangunan";
	//alert("update data");
	var id_bangunan,nama_pemilik,alamat_pemilik,telp,nomor_bukti_diri,fungsi_gedung,luas_tanah,jalan,id_desa,id_kecamatan,status_izin,tahun,id_jenis;
	id_bangunan=$("#id_bangunan").val();
	nama_pemilik=$("#nama_pemilik").val();
	alamat_pemilik=$("#alamat_pemilik").val();
	telp=$("#telp").val();
	nomor_bukti_diri=$("#nomor_bukti_diri").val();
	fungsi_gedung=$("#fungsi_gedung").val();
	luas_tanah=$("#luas_tanah").val();
	jalan=$("#jalan").val();
	id_desa=$("#id_desa").val();
	id_kecamatan=$("#id_kecamatan2").val();
	status_izin=$("#status_izin").val();
	tahun=$("#tahun2").val();
	id_jenis=$("#id_jenis2").val();
	datanya="&aksi="+aksi+"&id_bangunan="+id_bangunan+"&nama_pemilik="+nama_pemilik+"&alamat_pemilik="+alamat_pemilik+"&telp="+telp+"&nomor_bukti_diri="+nomor_bukti_diri+"&fungsi_gedung="+fungsi_gedung+"&luas_tanah="+luas_tanah+"&jalan="+jalan+"&id_desa="+id_desa+"&id_kecamatan="+id_kecamatan+"&status_izin="+status_izin+"&tahun="+tahun+"&id_jenis="+id_jenis;
		$.ajax({
		url : "iframe_include/update.php",
		data : datanya,
		cache : false,
		success : function(msg){
			if(msg=="sukses_update"){ alert("data berhasil diupdate ..!"); $(".ui").load("iframe_include/bangunan.php");}
			if(msg=="gagal_update"){
				alert("data tidak dapat diupdate ..!");
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
<table style="background:linear-gradient( #09F, #6CF);" width="100%" border="0">
  <tr>
    <td width="8%"><img src="../icon/home.png" /></td>
    <td width="83%" align="center"><h3>INPUT DATA BANGUNAN</h3></td>
    <td width="9%" align="right"><input  type="button" onclick="close_form();" value=" X " style="border-radius:50px;" /></td>
  </tr>
</table><hr />
<form name="form1">
<table width="900" border="0" cellpadding="1" cellspacing="2">
    <tr>
      <td width="198">ID Bangunan</td>
      <td width="3">:</td>
      <td width="340"><input style="border-radius:5px 5px 5px 5px; border:3px solid #06F; background:linear-gradient( #CCC, #FFF); padding:3px 3px 3px 3px;" name="id_bangunan" id="id_bangunan" type="text" size="30" maxlength="50" /><input type="hidden" id="textid" name="textid" /></td>
      <td width="103">Jenis IMB</td>
      <td width="8">:</td>
      <td width="222"><select name="id_jenis2" id="id_jenis2" style="border-radius:5px 5px 5px 5px; border:3px solid #06F; background:linear-gradient( #CCC, #FFF); padding:3px 3px 3px 3px;">
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
      <td>Nama Pemilik Bangunan</td>
      <td>:</td>
      <td><input style="border-radius:5px 5px 5px 5px; border:3px solid #06F; background:linear-gradient( #CCC, #FFF); padding:3px 3px 3px 3px;" name="nama_pemilik" id="nama_pemilik" type="text" size="30" maxlength="50" /></td>
      <td colspan="3" bgcolor="#CCCCCC">Lokasi Bangunan Gedung :</td>
    </tr>
    <tr>
      <td>Alamat</td>
      <td>:</td>
      <td><input style="border-radius:5px 5px 5px 5px; border:3px solid #06F; background:linear-gradient( #CCC, #FFF); padding:3px 3px 3px 3px;" name="alamat_pemilik" id="alamat_pemilik" type="text" size="30" maxlength="50" /></td>
      <td>1. Jalan</td>
      <td>:</td>
      <td><input style="border-radius:5px 5px 5px 5px; border:3px solid #06F; background:linear-gradient( #CCC, #FFF); padding:3px 3px 3px 3px;" name="jalan" id="jalan" type="text" size="30" maxlength="50" /></td>
    </tr>
    <tr>
      <td>Telp/HP</td>
      <td>:</td>
      <td><input style="border-radius:5px 5px 5px 5px; border:3px solid #06F; background:linear-gradient( #CCC, #FFF); padding:3px 3px 3px 3px;" name="telp" id="telp" type="text" size="30" maxlength="50" /></td>
      <td>2. Desa</td>
      <td>:</td>
      <td><select  id="id_desa" name="id_desa" style="border-radius:5px 5px 5px 5px; border:3px solid #06F; background:linear-gradient( #CCC, #FFF); padding:3px 3px 3px 3px;">
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
      <td>Nomor Bukti Identitas Diri</td>
      <td>:</td>
      <td><input style="border-radius:5px 5px 5px 5px; border:3px solid #06F; background:linear-gradient( #CCC, #FFF); padding:3px 3px 3px 3px;" name="nomor_bukti_diri" id="nomor_bukti_diri" type="text" size="30" maxlength="50" /></td>
      <td>3. Kecamatan</td>
      <td>:</td>
      <td><select name="id_kecamatan2" id="id_kecamatan2" style="border-radius:5px 5px 5px 5px; border:3px solid #06F; background:linear-gradient( #CCC, #FFF); padding:3px 3px 3px 3px;">
        <option value="0" selected="selected">- Pilih Kecamatan--</option>
        <?php
	   include "../koneksi.php";
       $sql_d="SELECT * FROM tb_kecamatan ORDER BY id_kecamatan ASC";
	   $result_d=mysql_query($sql_d);
	   while ($data_d=mysql_fetch_array($result_d)){
	   ?>
        <option value="<?php echo $data_d['id_kecamatan'];?>"> <?php echo $data_d['kecamatan'];?> </option>
        <?php } ?>
      </select></td>
    </tr>
    <tr>
      <td>Fungsi utama bangunan  gedung</td>
      <td>:</td>
      <td><input style="border-radius:5px 5px 5px 5px; border:3px solid #06F; background:linear-gradient( #CCC, #FFF); padding:3px 3px 3px 3px;" name="fungsi_gedung" id="fungsi_gedung" type="text" size="30" maxlength="50" /></td>
      <td>Status Izin IMB</td>
      <td>:</td>
      <td><select name="status_izin" id="status_izin" style="border-radius:5px 5px 5px 5px; border:3px solid #06F; background:linear-gradient( #CCC, #FFF); padding:3px 3px 3px 3px;">
        <option value="0" selected="selected">- status izin--</option>
        <option value="sudah">Sudah</option>
        <option value="belum">Belum</option>
      </select></td>
    </tr>
    <tr>
      <td>Luas tanah keseluruhan</td>
      <td>:</td>
      <td><input style="border-radius:5px 5px 5px 5px; border:3px solid #06F; background:linear-gradient( #CCC, #FFF); padding:3px 3px 3px 3px;" name="luas_tanah" id="luas_tanah" type="text" size="30" maxlength="50" /></td>
      <td>Tahun</td>
      <td>:</td>
      <td><input style="border-radius:5px 5px 5px 5px; border:3px solid #06F; background:linear-gradient( #CCC, #FFF); padding:3px 3px 3px 3px;" name="tahun2" id="tahun2" type="text" size="4" maxlength="4" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="button" id="btnsubmit" name="btnsubmit" value="Simpan" onclick="return simpan_data();" />
        <input type="button" id="btnupdate" name="btnupdate" value="Update" onclick="return update_data();" />
      <input type="reset" value="Reset" /></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>
<div id="data_wilayah" style="overflow:auto; max-height:250px; margin-bottom:0px; border-top:1px solid #CCC;">
<table width="100%" border="0" style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; color:#036;">

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
    <td >Aksi</td>
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
    <td width="100"><a title="" style="cursor:pointer;" onclick="return hapus_data('<?php echo $row['id_bangunan'];?>');"><img src="images/drop.png" width="16" height="16" border="0"></a>
      <a title="" onclick="return edit_data('<?php echo $row['id_bangunan'];?>','<?php echo $row['nama_pemilik'];?>','<?php echo $row['alamat_pemilik'];?>','<?php echo $row['telp'];?>','<?php echo $row['nomor_bukti_diri'];?>','<?php echo $row['fungsi_gedung'];?>','<?php echo $row['luas_tanah'];?>','<?php echo $row['jalan'];?>','<?php echo $row['id_desa'];?>','<?php echo $row['id_kecamatan'];?>','<?php echo $row['status_izin'];?>','<?php echo $row['tahun'];?>','<?php echo $row['id_jenis'];?>');"><img src="images/edit.png" width="16" height="16" border="0"></a>
</td></tr><?php } ?>
</table>
</div>
</body>
</html>