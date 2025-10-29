<?php
require_once "api-connector.php";

function getMantraServices()
{
	global $messageAPI;
	$result=false;              

	$uri='%URI%';
	$result=queryAPI($uri);
	return $result;
}

// konversi/parsing format data XML dari fungsi getMantraServices ke data Array melalui fungsi parseRESTXML2Array
var_dump(parseRESTXML2Array(getMantraServices())); 
