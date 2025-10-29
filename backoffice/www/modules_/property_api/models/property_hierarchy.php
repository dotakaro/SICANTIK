<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class untuk menyimpan struktur data hierarchy dari suatu web service
 *
 * @author  Indra Halim
 * @since
 *
 */

class property_hierarchy extends DataMapper {

    var $table = 'property_hierarchy';
    var $has_many = array('mapping');
    var $has_one = array('trapi');

    public function __construct() {
        parent::__construct();
    }

    public function getWebServiceData($url, $type='xml'){
        $CI = &get_instance();
        $CI->load->library('curl');
//        $url = "http://localhost/backoffice_new/api/report/type/BAP/tmpermohonan_id/1/trperizinan_id/1.xml";
        //$url = "http://sicantik.alp.layanan.go.id/agam/api/jenisperizinanlist/format/xml";
//        $url = "http://sicantik.alp.layanan.go.id/agam/api/itemretribusi/trperizinan_id/98/format/xml";
        $data_string = $CI->curl->simple_get($url);
        switch($type){
            case 'json';
                $web_service_data = $this->objectToArray(json_decode($data_string));
                break;
            default:
//                $web_service_data = json_decode(json_encode((array)simplexml_load_string($data_string)),1);
                $xml = simplexml_load_string($data_string);
                $json = json_encode($xml);
                $web_service_data = json_decode($json,TRUE);
                break;
        }

        return $web_service_data;
    }

    public function objectToArray($d) {
        $ret = array();
        if(!empty($d) && is_array($d) || is_object($d)){
            foreach($d as $key=>$value){
                if (!empty($value)) {
                    $childParse = self::objectToArray($value);
                    if(is_array($childParse) && !empty($childParse)){
                        $value = $childParse;
                    }
                }else{
                    if(is_object($value)){
                        $value = (array) $value;
                    }
                }
                $ret[$key] = $value;
            }
        }
        return $ret;
    }


}

// This is the end of massage_mdl class
