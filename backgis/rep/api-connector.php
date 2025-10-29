<?php

function queryAPI($url)
{
	$ch = curl_init();                              // PHP_CURL in php.ini must be enabled 
	                                                // (extension=[php_curl.dll|php_curl.so]) 
	curl_setopt($ch, CURLOPT_URL, $url);            // Set URL 
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,TRUE);  // Return result

	$result=curl_exec($ch);                         // Connect to URL and get result
	
	if ($error = curl_error($ch)):                  // Check error
		$result = false;
	endif;
	return $result;
}

function postAPI($url,$param="")
{
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);            // Set URL 
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_POST, TRUE);           // Use POST method
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,TRUE);  // Return result
	curl_setopt($ch, CURLOPT_POSTFIELDS,$param);    // Set query parameter
	$result=curl_exec($ch);                         // Connect to URL and get result
	if ($error = curl_error($ch)):                  // Check error
		$result = false;
	endif;
	return $result;
}

function parseRESTXML2Array($xmlREST)
{
	$result=$xmlREST;
	if($result):
		if(substr($result,0,5)=="REST:"):                  //Replace REST:
			$result=substr_replace($result,"",0,5);
		endif;
		if($result):
			if(strtolower(substr($result,0,5))=="<?xml"): //Detect XML format

				$xml = new SimpleXMLElement($result);     //Parsing XML into Array
				$rootName=strtolower($xml->getName());
				if($rootName=="invalid_response"):
					$result=(string) $xml;
				elseif ($rootName=="valid_response"):
					$result=array();
					foreach($xml->children() as $items):
						$result[]=(array) $items;	
					endforeach;
				else:
					$result=false;
					die("No result from API Webservice ".$url);
				endif;
			endif;
		endif;
	endif;
	return $result;
}

