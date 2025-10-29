<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Do login </title>
</head>
<body>
<?php
include "koneksi.php";
//--- deklarasi variabel ---

$username=$_POST['txtuser'];
$password=$_POST['txtpass'];

$sql="SELECT * FROM tb_admin WHERE username='$username'";
$result=mysql_query($sql);
$baris=mysql_fetch_array($result);
	//-- cek keadaan namauser
	if(!$baris){
	echo"<center><font color='#ff0000'>User Salah..!</font></center>";
	echo"<center><a href='admin.php?id=$username'>Coba Lagi</a></center>";
	}else{
	//-- cek password
	if ($password!=$baris['password']){
		echo"<center><font color='#ff0000'>Password Salah..!</font></center>";
		echo"<center><a href='index.php'>Coba Lagi</a></center>";
		//header("location: .php?usr=0&pass=error&bag=pencari");
		}else{ 
		session_start();
		//$_SESSION['user_forum']=$username;
		//$_SESSION['user_password']=$password;
		//$_SESSION['agent_forum']=md5($_SERVER['HTTP_USER_AGENT']);
		//header("location: admin.php?id=$username");tahun=Tahun+distribusi&bulan=0&id_kecamatan3=0&btn1=Cari+Data
		echo "<meta http-equiv='refresh' content='0; url=admin.php?id=$username&tahun=0&id_jenis=0&id_kecamatan3=0'>";
		}
	//-------
	}
?>
<style type="text/css">
	body {margin:50px; font-family:Arial, Helvetica, sans-serif;}
</style>
</body>
</html>
