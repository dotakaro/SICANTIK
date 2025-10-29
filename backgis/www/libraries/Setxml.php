<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Setxml {
        
    public function setXML2Arr($xml="",$blockname="valid_response")
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
}
