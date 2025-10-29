<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Query {
	
		$messageAPI="";
     
    public function queryapi($url)
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
}
