<?php
include "db.php"; //untuk koneksi db
  if ($_SERVER['REQUEST_METHOD'] == 'POST') { //halaman ini harus dipanggil melalui post
	  $nip = $_POST["nip"];
	  $nama = $_POST["nama"];
	  $act = $_POST["act"];
	  
	  
	  switch($act){
		  //tambah data
		  case "insert": $sql="INSERT INTO karyawan(nip,nama)VALUES('".$nip."','".$nama."')";break;
		  //edit data
		  case "edit":$sql="UPDATE karyawan SET nama= '".$nama."' WHERE nip ='".$nip."'";break;
		  //hapus data
		  case "delete":
		    $sql="DELETE FROM karyawan WHERE nip = '".$nip."'";
		    break;
	  }
	  //eksekusi sql
	  $kueri = mysql_query($sql);
	  
	  //tampilkan hasil
	  if($kueri){
		  echo "success";
	  }else{
		  echo "gagal simpan data";
	  }
  }else{
	  echo "This link can't run directly.";
  }
?>