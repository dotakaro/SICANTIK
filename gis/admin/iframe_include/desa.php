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
	$("#textid_desa").keypress(function(){
		var id_desa; id_desa=$("#textid_desa").val();
		//$("#statustext").html(kdpetugas);
		})
	$("#textid_desa").keyup(function(){
		var id_desa; id_desa=$("#textid_desa").val();
		//$("#statustext").html(idpetugas);
		kode=$("#textid_desa").val();
		if(kode==""){ document.getElementById("btnsubmit").disabled=true; }
		$("#status").show();
		var datanya;
		//datanya="&idpetugas"+idpetugas;
		$.ajax({
		url: "iframe_include/cari_desa.php",
		data: "&id_desa="+id_desa,
		cache: false,
		success: function(msg){
			data=msg.split("|");
			callback=data[0];
			//$("#textdata").html(callback);
			if(callback=="nodata"){
				$("#statustext").html("");
				$("#textnama2").val("");
				$("#cbokecamatan").val("");
				document.getElementById("btnsubmit").disabled=false;
				//disabled button
				kode2=$("#textid_desa").val();
				if(kode2==""){ document.getElementById("btnsubmit").disabled=true; }
				$("#status").hide();
				}
			//if(msg=="adadata"){
			if(callback=="adadata"){
				$("#statustext").html("Data Telah Ada..!");
				$("#textnama2").val(data[2]);
				$("#cbokecamatan").val(data[3]);
				//var idkec=data([3]);
				$("#cbokecamatan").attr("selected",kecamatan);
				document.getElementById("btnsubmit").disabled=true;
				$("#status").hide();
			}
			}
			})
		})
	}) 
function hapus_data(nidn){
	var data_hapus=nidn;
	var aksi="desa";
	var datanya="&data_hapus="+data_hapus+"&aksi="+aksi;
	var r=confirm("Yakin ingin menghapus data..?");
	if (r==true){
		//hapus data
		$.ajax({
			url: "iframe_include/hapus.php",
			data : datanya,
			cache : false,
			success : function (msg){
				if(msg=="sukses"){alert ("Data telah dihapus..!"); $(".ui").load("iframe_include/desa.php");}
				}
			})
		}else{
			//alert("no");
			}
	}
function clear_form(){
	$("#textnama2").val("");
	$("#cbokecamatan").val("");
	}
function simpan_data(){
	var id_desa, desa, id_kecamatan;    
	var data;
	id_desa=$("#textid_desa").val();
	desa=$("#textnama2").val();
	id_kecamatan=$("#cbokecamatan").val();
	//if(id_desa==""){ alert("Masukkan kode desa"); return false;}
	//else if(desa==""){ alert("Masukkan nama desa..!"); return false;}
	//else if(id_kecamatan==""){ alert("Masukkan tempat kecamatan!"); return false;}
	datanya="&id_desa="+id_desa+"&desa="+desa+"&id_kecamatan="+id_kecamatan;
	$.ajax({
		url : "iframe_include/simpan_desa.php",
		data : datanya,
		cache : false,
		success : function(msg){
			if(msg=="sukses"){
				alert("Data berhasil disimpan");
				$("#textid_desa").val("");
				$("#textnama2").val("");
				$("#cbokecamatan").val("");
				//$("#data_wilayah").fadeOut('slow').load("iframe_include\wilayah.php").fadeIn('slow');
				$(".ui").load("iframe_include/desa.php")
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
function edit_data(xid_desa, xdesa, xkecamatan){
	//$("#textnidn").val(data);
	//$("#textnidn").focus();
	//var id_desa=xid_desa, desa=xdesa, kecamatan=xkecamatan;
	var id_desa=xid_desa; var desa=xdesa; var kecamatan=xkecamatan;
	$("#textid_desa").val(id_desa);
	$("#textnama2").val(desa);
	$("#cbokecamatan").attr("value",kecamatan);
	$("#cbokecamatan").attr("selected",kecamatan);
	//$("#textkodewil").attr("disabled","disabled");
	$("#lblket").text("Edit Data Desa");
}
//update data
function update_data(){
	var datanya;
	var aksi="desa";
	//alert("update data");
	var id_desa,desa, id_kecamatan;
	id_desa=$("#textid_desa").val();
	desa=$("#textnama2").val();
	id_kecamatan=$("#cbokecamatan").val();
	datanya="&aksi="+aksi+"&id_desa="+id_desa+"&desa="+desa+"&id_kecamatan="+id_kecamatan;
		$.ajax({
		url : "iframe_include/update.php",
		data : datanya,
		cache : false,
		success : function(msg){
			if(msg=="sukses_update"){ alert("data berhasil diupdate ..!"); $(".ui").load("iframe_include/desa.php");}
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
    <td width="8%"><img width="" height="" src="../icon/home.png" /></td>
    <td width="83%" align="center"><h3>INPUT DATA DESA/KELURAHAN</h3></td>
    <td width="9%" align="right"><input  type="button" onclick="close_form();" value=" X " style="border-radius:50px;" /></td>
  </tr>
</table><HR />
<form name="form1">
<table width="403" border="0" cellpadding="1" cellspacing="2">
    <tr>
      <td width="80">KD Desa</td>
      <td width="10">:</td>
      <td width="299"><input type="text" name="textid_desa" id="textid_desa" class="text ui-widget-content ui-corner-all" size="10" maxlength="10" style="border-radius:5px 5px 5px 5px; border:3px solid #06F; background:linear-gradient( #CCC, #FFF); padding:3px 3px 3px 3px;"/><label style="color:red;" id="statustext" ></label></td>
    </tr>
    <tr>
      <td> Nama Desa</td>
      <td>:</td>
      <td><input type="text" name="textnama2" id="textnama2" class="text ui-widget-content ui-corner-all" size="30" maxlength="30" style="border-radius:5px 5px 5px 5px; border:3px solid #06F; background:linear-gradient( #CCC, #FFF); padding:3px 3px 3px 3px;"/></td>
    </tr>
    <tr>
      <td>Kecamatan</td>
      <td>:</td>
      <td><select name="cbokecamatan" id="cbokecamatan" style="border-radius:5px 5px 5px 5px; border:3px solid #06F; background:linear-gradient( #CCC, #FFF); padding:3px 3px 3px 3px;">
        <option value="0" selected="selected">- Pilih Kecamatan--</option>
        <?php
	   include "../koneksi.php";
       $sql_d="SELECT * FROM tb_kecamatan ORDER BY id_kecamatan ASC";
	   $result_d=mysql_query($sql_d);
	   while ($data_d=mysql_fetch_array($result_d)){
	   ?>
        <option value="<?php echo $data_d['id_kecamatan'];?>"><?php echo $data_d['kecamatan'];?></option>
        <?php } ?>
      </select></td>
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
  </table><br />
</form>
<div id="data_desa" style="overflow:auto; height:300px; margin-bottom:0px; border-top:1px solid #CCC;">
<table width="600" border="0" style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; color:#036;">
  <tr>
    <td >ID Desa</td>
    <td >Nama Desa</td>
    <td >Kecamatan</td>
    <td >&nbsp;</td>
  </tr><?php
  include "../koneksi.php";
  $query=mysql_query("SELECT tb_desa.id_desa, tb_desa.desa, tb_desa.id_kecamatan, tb_kecamatan.id_kecamatan, tb_kecamatan.kecamatan FROM tb_desa, tb_kecamatan WHERE tb_desa.id_kecamatan=tb_kecamatan.id_kecamatan ORDER BY tb_desa.id_desa ASC");
  while ($row=mysql_fetch_array($query)){
?>
  <tr>
    <td width="146" ><?php echo $row['id_desa'];?></td>
    <td width="208" ><?php echo $row['desa'];?></td>
    <td width="191" ><?php echo $row['kecamatan'];?></td>
    <td width="37"><a title="" style="cursor:pointer;" onclick="return hapus_data('<?php echo $row['id_desa'];?>');"><img src="images/drop.png" width="16" height="16" border="0"></a>
  <a title="" onclick="return edit_data('<?php echo $row['id_desa'];?>','<?php echo $row['desa'];?>','<?php echo $row['id_kecamatan'];?>');"><img src="images/edit.png" width="16" height="16" border="0"></a>
</td></tr><?php } ?>
</table>
</div>
<iframe style="height:1px" src="" frameborder=0 width=1></iframe>
</body>
</html>