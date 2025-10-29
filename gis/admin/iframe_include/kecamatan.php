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
	$("#textid").keypress(function(){
		var id_kecamatan; id_kecamatan=$("#textid").val();
		//$("#statustext").html(kdpetugas);
		})
	$("#textid").keyup(function(){
		var id_kecamatan; id_kecamatan=$("#textid").val();
		//$("#statustext").html(idpetugas);
		kode=$("#textid").val();
		if(kode==""){ document.getElementById("btnsubmit").disabled=true; }
		$("#status").show();
		var datanya;
		//datanya="&idpetugas"+idpetugas;
		$.ajax({
		url: "iframe_include/cari_kecamatan.php",
		data: "&id_kecamatan="+id_kecamatan,
		cache: false,
		success: function(msg){
			data=msg.split("|");
			callback=data[0];
			//$("#textdata").html(callback);
			if(callback=="nodata"){
				$("#statustext").html("");
				$("#nama").val("");
				document.getElementById("btnsubmit").disabled=false;
				//disabled button
				kode2=$("#textid").val();
				if(kode2==""){ document.getElementById("btnsubmit").disabled=true; }
				$("#status").hide();
				}
			//if(msg=="adadata"){
			if(callback=="adadata"){
				$("#statustext").html("Data Telah Ada..!");
				$("#nama").val(data[2]);
				document.getElementById("btnsubmit").disabled=true;
				$("#status").hide();
			}
			}
			})
		})
	}) 
function hapus_data(id_kecamatan){
	var data_hapus=id_kecamatan;
	var aksi="kecamatan";
	var datanya="&data_hapus="+data_hapus+"&aksi="+aksi;
	var r=confirm("Yakin ingin menghapus data..?");
	if (r==true){
		//hapus data
		$.ajax({
			url: "iframe_include/hapus.php",
			data : datanya,
			cache : false,
			success : function (msg){
				if(msg=="sukses"){alert ("Data telah dihapus..!"); $(".ui").load("iframe_include/kecamatan.php");}
				}
			})
		}else{
			//alert("no");
			}
	}
function clear_form(){
	$("#kecamatan").val("");
	}
function simpan_data(){
	var id_kecamatan;
	var namakecamatan;
	//var data;
	id_kecamatan=$("#textid").val();
	namakecamatan=$("#nama").val();
	if(id_kecamatan==""){ alert("Masukkan kode kecamatan"); return false;}
	datanya="&id_kecamatan="+id_kecamatan+"&namakecamatan="+namakecamatan;
	$.ajax({
		url : "iframe_include/simpan_kecamatan.php",
		data : datanya,
		cache : false,
		success : function(msg){
			if(msg=="sukses"){alert("Data berhasil disimpan"); $(".ui").load("iframe_include/kecamatan.php");}
			if(msg=="gagal"){alert("data tidak dapat disimpan");}
			}
		})
	}
function edit_data(xid_kecamatan, xkecamatan){
	//$("#textid").val(data);
	//$("#textid").focus();
	var id_kecamatan=xid_kecamatan; kecamatan=xkecamatan;
	$("#textid").val(id_kecamatan);
	$("#nama").val(kecamatan);
	//$("#textkodewil").attr("disabled","disabled");
	$("#lblket").text("Edit Data Kecamatan");
}
//update data
function update_data(){
	var datanya;
	var aksi="kecamatan";
	//alert("update data");
	var id_kecamatan, kecamatan;
	id_kecamatan=$("#textid").val();
	kecamatan=$("#nama").val();
	datanya="&aksi="+aksi+"&id_kecamatan="+id_kecamatan+"&kecamatan="+kecamatan;
		$.ajax({
		url : "iframe_include/update.php",
		data : datanya,
		cache : false,
		success : function(msg){
			if(msg=="sukses_update"){ alert("data berhasil diupdate ..!"); $(".ui").load("iframe_include/kecamatan.php");}
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
function tes(){
	//alert("tes");
	}
</script>
<style type="text/css">
form { font-family:Arial, Helvetica, sans-serif; font-size:12px;}
form input, select { font-weight:bold;}
h3 { font-family:"Palatino Linotype", "Book Antiqua", Palatino, serif; border-bottom:1px solid #CCC; margin-bottom:4px;}
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
    <td width="8%"><img src="../icon/kecamatan.png" /></td>
    <td width="83%" align="center"><h2>Input Data Kecamatan</h2></center></td>
    <td width="9%" align="right"><input type="button" onclick="close_form();" value="&nbsp;X&nbsp;" style="border-radius:50px;" /></td>
  </tr>
</table><hr />
<form name="form1">
<table align="center" width="407" border="0" cellpadding="1" cellspacing="2">
    <tr>
      <td width="98">ID Kecamatan</td>
      <td width="10">:</td>
      <td width="292"><input type="text" name="textid" id="textid" class="text ui-widget-content ui-corner-all" size="10" maxlength="10" onfocus="tes();" style="border-radius:5px 5px 5px 5px; border:3px solid #06F; background:linear-gradient( #CCC, #FFF); padding:3px 3px 3px 3px;"/><label style="color:red;" id="statustext"></label></td>
    </tr>
    <tr>
      <td>Kecamatan</td>
      <td>:</td>
      <td><input type="text" name="nama" id="nama" size="30" class="text ui-widget-content ui-corner-all" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="button" id="btnsubmit" name="btnsubmit" value="Simpan" onclick="return simpan_data();" />
        <input type="button" id="btnupdate" name="btnupdate" value="Update" onclick="return update_data();" />
      <input type="reset" value="Reset" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table><br />
</form>
<div  style="max-height:250px; height:200px; overflow:scroll; margin-bottom:0px; border-top:1px solid #CCC;">
<table width="58%" border="0" style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; color:#036;">
  <tr align="center">
  	<td >ID Kecamatan</td>
    <td >Kecamatan</td>
    <td width="95" >Aksi</td>
  </tr><?php
  include "../koneksi.php";
  $query=mysql_query("SELECT * FROM  tb_kecamatan ORDER BY id_kecamatan ASC");
  $no=0;
  while ($row=mysql_fetch_array($query)){
  $no=$no+1;
?>
  <tr>
  <td width="140" ><?php echo $row['id_kecamatan'];?></td>
  <td width="420" ><?php echo $row['kecamatan'];?></td>
    <td width="95"><a title="" style="cursor:pointer;" onclick="return hapus_data('<?php echo $row['id_kecamatan'];?>');"><img src="images/drop.png" width="16" height="16" border="0"></a>
  <a title="" onclick="return edit_data('<?php echo $row['id_kecamatan'];?>','<?php echo $row['kecamatan'];?>');"><img src="images/edit.png" width="16" height="16" border="0"></a>
</td></tr><?php } ?>
</table>
</div>
<iframe style="height:1px" src="" frameborder=0 width=1></iframe>
</body>
</html>