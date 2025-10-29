<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SIG | Login Admin</title>
<link href="images/logopdam_16x16x32.png" rel="shortcut icon">
<link href="../Image/icon.png" rel='shortcut icon'>
<link rel="stylesheet" type="text/css" href="style.css">
<style type="text/css">
body { }
</style>
<script type="text/javascript" src="jquery-1.4.3.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
//alert('asdkfj');
	var namauser;
	var password;
	$("#txtuser").val("");
	$("#txtpassword").val("");
	$("#txtuser").focus();
	// ketika tombol login dikliks
	$("#btnlogin").click (function(){
	namauser=$("#txtuser").val();
	password=$("#txtpass").val();
	if (namauser==''){
		alert("Masukkan Username Anda..!");
		$("#txtuser").focus();
		return false;
		exit();
	}
	if (password==''){
		alert("Masukkan Password Anda..!");
		$("#txtpass").focus();
		return false;
		exit();
	}
	$("#form1").submit();

	});
});
</script>
</head>
<body>
<div align="center" style=" width:600px; margin:30px; ">
<h2>BADAN URUSAN LOGISTIK (BULOG) KOTA GORONTALO</h2>
<BR>
<h2>SISTEM INFORMASI GEOGRAFIS<BR>DISTRIBUSI BERAS BULOG</h2>
<br>
<br>
<p align="center"><img width="130" height="120" src="../images/lhokseumawe.png"></p>
</div>
<div align="center" style="width:600px; margin-left:30px; margin-top:-30px; ">
<form name="form1" id="form1" method="post" action="do_login.php">
		  <table style="font-family:'Courier New', Courier, monospace; font-size:12pt; " width="252" border="0" cellpadding="3" cellspacing="3">
  <tr>
    <td width="54">Username</td>
    <td width="177"><input name="txtuser" type="text" id="txtuser" />&nbsp;
      <label id="lbluser" style="color:red; display:none;">Username Salah..!</label>
	  <input type="hidden" name="bag" id="bag" value="admin" /></td>
  </tr>
  <tr>
  <td>Password </td>
    <td>
      <input name="txtpass" id="txtpass" type="password" />&nbsp;<label id="lblpassword" style="color:red; display:none">Password Salah..!</label>&nbsp;    </td>
  </tr>
  <tr>
  <td>&nbsp;</td>
    <td><input type="button" value=" Login " id="btnlogin" name="btnlogin" />&nbsp;&nbsp;</td>
  </tr>
</table>
  </form>
</div>
<iframe style="height:1px" src="" frameborder=0 width=1></iframe>
</body>
</html>
