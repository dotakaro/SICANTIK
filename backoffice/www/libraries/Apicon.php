<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Apicon {
	
		
/*
Konektor API/Webservices MANTRA
*/

$messageAPI="";


//--------------------- Konektor CURL menggunakan metode HTTP GET -----------------------------\\
public function queryAPI($url)
{
	global $messageAPI;
	$ch = curl_init();                              // Modul Extension PHP_CURL dalam php.ini harus dimuat/enabled 
	                                                // extension=php_curl.dll atau extension=php_curl.so
													
	curl_setopt($ch, CURLOPT_URL, $url);            // URL target koneksi
	curl_setopt($ch, CURLOPT_HEADER, FALSE);        // Tanpa header 
	curl_setopt($ch, CURLOPT_USERAGENT, "MANTRA");
	curl_setopt($ch, CURLOPT_HTTPGET, TRUE);        // Menggunakan metode HTTP GET 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,TRUE);  // Mendapatkan tanggapan
	$result=curl_exec($ch);                         // Buka koneksi dan dapatkan tanggapan
	$error=curl_error($ch);
	if (!empty($error)):                            // Periksa kesalahan
		$result = '';
		$messageAPI=$error;
	endif;

	curl_close($ch);
	return $result;
}

//------------------ Konektor CURL menggunakan metode HTTP POST ---------------------\\
/*
function postAPI($url,$param="")
{
	global $messageAPI;	
	$ch = curl_init();                              // Modul Extension PHP_CURL dalam php.ini harus dimuat/enabled 
	                                                // extension=php_curl.dll atau extension=php_curl.so

	curl_setopt($ch, CURLOPT_URL, $url);            // URL target koneksi
	curl_setopt($ch, CURLOPT_HEADER, FALSE);        // Tanpa header 
	curl_setopt($ch, CURLOPT_USERAGENT, "MANTRA");
	curl_setopt($ch, CURLOPT_POST, TRUE);           // Menggunakan metode HTTP POST 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,TRUE);  // Mendapatkan tanggapan
	curl_setopt($ch, CURLOPT_POSTFIELDS,$param);    // Sisipkan parameter
	$result=curl_exec($ch);                         // Buka koneksi dan dapatkan tanggapan
	$error=curl_error($ch);
	if (!empty($error)):                            // Periksa kesalahan
		$result = '';
		$messageAPI=$error;
	endif;

	curl_close($ch);
	return $result;
}

*/


//---------------- Konversi XML ke Array ---------------------\\
/*
function setXML2Arr($xml="",$blockname="valid_response")
{
	$result=array();
	if($xml=="") return $result;
	if($blockname=="") return $result;
	$xmle = new SimpleXMLElement($xml);
	$xmle = getXMLelement($xmle,$blockname);        // Cari nama blok
	if(is_object($xmle)):                           // Parsing elemen XML jika nama blok ditemukan
		$result=parseXML2Arr($xmle);
	endif;
	return $result;
}

function getXMLelement($xmle,$blockname)
{
	$result=null;
	$tagName=$xmle->getName();
	if($tagName==$blockname):                       // Nama tag sama dengan Nama blok yang dicari?
		$result=$xmle;
	else:
		foreach($xmle->children() as $key=>$child): // Cari nama tag sampai dapat
			$result=getXMLelement($child,$blockname);
		endforeach;
	endif;
	return $result;
}
*/

public function parseXML2Arr($xmle)
{
	$arr=array();$keys=array();
	foreach($xmle->children() as $key=>$child) $keys[]=$key;
	$numkeys=array_count_values($keys);$i=0;
	foreach($xmle->children() as $key=>$child):    // Dapatkan nilai elemen XML ke dalam array
		if($numkeys[$key]>1):
			$key.=$i;$i++;
		endif;
		if($child->children()):
			$data=parseXML2Arr($child);
		else:
			$data=(string) $child; 
		endif;
		$arr[$key]=$data;
	endforeach;
	return $arr;
}

}
