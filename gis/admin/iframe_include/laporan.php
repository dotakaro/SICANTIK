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
function hapus_data(nidn){
	var nidn=nidn;
	var datanya="&nidn="+nidn;
	var r=confirm("Yakin ingin menghapus data..?"+datanya);
	if (r==true){
		//hapus data
		$.ajax({
			url: "iframe_include/hapus_dosen.php",
			data : datanya,
			cache : false,
			success : function (msg){
				if(msg=="sukses"){alert ("Data telah dihapus..!");}
				}
			})
		}else{
			//alert("no");
			}
	}
function clear_form(){
	$("#textnama").val("");
	$("#texttmplahir").val("");
	$("#texttgllahir").val("");
	$("#textfakultas").val("");
	$("#textjabatan").val("");
	$("#textalamat").val("");
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
</script>
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
    <td width="83%" align="center"><h3>LAPORAN</h3></td>
    <td width="9%" align="right"><input  type="button" onclick="close_form();" value=" X " style="border-radius:50px;" /></td>
  </tr>
</table><hr />
<div style="padding:100px 50px 100px 150px;">
<ul>
	<li><a target="_blank" href="laporan/lap_distribusi.php"><strong>Laporan Data Bangunan</strong></a></li>
</ul>
</div>
</body>
</html>