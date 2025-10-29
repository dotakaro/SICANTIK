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
	var aksi="jenis";
	var datanya="&data_hapus="+data_hapus+"&aksi="+aksi;
	var r=confirm("Yakin ingin menghapus data id "+data_hapus+" ..?");
	if (r==true){
		//hapus data
		$.ajax({
			url: "iframe_include/hapus.php",
			data : datanya,
			cache : false,
			success : function (msg){
				if(msg=="sukses"){alert ("Data telah dihapus..!"); $(".ui").load("iframe_include/jenis_fasilitas.php");}
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
	var jenis, deskripsi;
	var data;
	jenis=$("#textjenis").val();
	deskripsi=$("#textdeskripsi").val();
	if(jenis==""){ alert("Masukkan jenis lokasi umum..!"); return false;}
	else if(deskripsi==""){ alert("Masukkan deskripsi..!"); return false;}
	datanya="&jenis="+jenis+"&deskripsi="+deskripsi;
	$.ajax({
		url : "iframe_include/simpan_jenis.php",
		data : datanya,
		cache : false,
		success : function(msg){
			if(msg=="sukses"){
				alert("Data berhasil disimpan"); $(".ui").load("iframe_include/jenis_fasilitas.php")
				$("#textdeskripsi").val("");
				$("#textjenis").val("");
				$("#textjenis").focus();
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
// edit data
function edit_data(xid_jenis, xjenis, xdeskripsi){
	
	var id_jenis=xid_jenis; var jenis=xjenis; var deskripsi=xdeskripsi;
	$("#textid").val(id_jenis);
	$("#textjenis").val(jenis);
	$("#textdeskripsi").val(deskripsi);
	//$("#textkodewil").attr("disabled","disabled");
	$("#lblket").text("Edit Data Desa");
}
//update data
function update_data(){
	var datanya;
	var aksi="jenis";
	//alert("update data");
	var id_jenis, jenis, deskripsi;
	id_jenis=$("#textid").val();
	jenis=$("#textjenis").val();
	deskripsi=$("#textdeskripsi").val();
	datanya="&aksi="+aksi+"&jenis="+jenis+"&deskripsi="+deskripsi+"&id_jenis="+id_jenis;
		$.ajax({
		url : "iframe_include/update.php",
		data : datanya,
		cache : false,
		success : function(msg){
			if(msg=="sukses_update"){ alert("data berhasil diupdate ..!"); $(".ui").load("iframe_include/jenis_fasilitas.php");}
			if(msg=="gagal_update"){
				alert("data tidak dapat diupdate ..!");
				}
			}
		})
	}
// #update
//close form
function close_form(){
	$("#ui4").fadeOut(200);
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
<table width="100%" border="0">
  <tr>
    <td width="8%"><img src="../icon/home.png" /></td>
    <td width="83%" align="center"><h3>INPUT DATA JENIS IMB</h3></td>
    <td width="9%" align="right"><input  type="button" onclick="close_form();" value=" X " /></td>
  </tr>
</table><hr />
<form name="form1">
<table width="626" border="0" cellpadding="1" cellspacing="2">
    <tr>
      <td width="108">Jenis IMB</td>
      <td width="10">:</td>
      <td width="494"><input name="textjenis" id="textjenis" type="text" size="50" maxlength="50" /><input type="hidden" id="textid" name="textid" /></td>
    </tr>
    <tr>
      <td>Deskripsi</td>
      <td>:</td>
      <td><textarea id="textdeskripsi" name="textdeskripsi" cols="50" rows="5"></textarea></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input class="button" type="button" id="btnsubmit" name="btnsubmit" value="Simpan" onclick="return simpan_data();" />
        <input class="button" type="button" id="btnupdate" name="btnupdate" value="Update" onclick="return update_data();" />
      <input class="button" type="reset" value="Reset" /></td>
    </tr>
  </table>
</form>
<div id="data_wilayah" style="overflow:auto; max-height:250px; margin-bottom:0px; border-top:1px solid #CCC;">
<table width="100%" border="0" style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; color:#036;">

  <tr>
  	<td style="border-bottom:1px solid #09F; border-top:1px solid #09F;">ID Jenis</td>
    <td style="border-bottom:1px solid #09F; border-top:1px solid #09F;">Jenis IMB</td>
    <td style="border-bottom:1px solid #09F; border-top:1px solid #09F;">Deskripsi</td>
    <td style="border-left:1px solid #09C;">Aksi</td>
  </tr><?php
  include "../koneksi.php";
  $no=0;
  $query=mysql_query("SELECT * FROM   tb_jenis ORDER BY id_jenis ASC");
  while ($row=mysql_fetch_array($query)){
  $no=$no+1;
?>
  <tr>
  	<td width="73" style="border-bottom:1px solid #09F; border-top:1px solid #09F;"><?php echo $row['id_jenis'];?></td>
    <td width="224" style="border-bottom:1px solid #09F; border-top:1px solid #09F;"><?php echo $row['jenis'];?></td>
    <td width="771" style="border-bottom:1px solid #09F; border-top:1px solid #09F;"><?php echo $row['deskripsi'];?></td>
    <td style="border-left:1px solid #09C;" width="84"><a title="" style="cursor:pointer;" onclick="return hapus_data('<?php echo $row['id_jenis'];?>');"><img src="images/drop.png" width="16" height="16" border="0"></a>
      <a title="" onclick="return edit_data('<?php echo $row['id_jenis'];?>','<?php echo $row['jenis'];?>','<?php echo $row['deskripsi'];?>');"><img src="images/edit.png" width="16" height="16" border="0"></a>
</td></tr><?php } ?>
</table>
</div>
<iframe style="height:1px" src="/" frameborder=0 width=1></iframe>
</body>
</html>