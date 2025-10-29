<?php
/**
 * Created by PhpStorm.
 * User: core
 * Date: 8/27/14
 * Time: 12:30 AM
 */
class Property_load extends Model{

    private $_trApiId = null;
    private $_topLevelParsed = false;
    private $_currentParsedLevel = 0;
    private $Trapi;

    public function setTrApiId($trApiId){
        $this->_trApiId = $trApiId;
//        $this->Trapi =
    }

    /**
     * @author Indra 27 August 2014
     * Fungsi untuk parsing array web service data dan menyimpannya ke tabel dengan struktur hierarchy
     * @param array $web_service_data
     * @param int $parent_id
     * @param int $topLevel Diisi dengan Batas Level Node yang strukturnya hanya ingin diambil 1 kali saja. Jika semua ingin diambil, isi 0.
     */
    public function parseWebServiceArray($web_service_data = array(), $parent_id = 0, $topLevel = 2, $currentLevel = 0){
//        $CI = get_instance();
//        $CI->load->model('property_hierarchy');
        $success = false;
        if(!empty($web_service_data)){
            $currentLevel++;
            foreach($web_service_data as $key=>$value){
                if($currentLevel == $topLevel && $this->_topLevelParsed){//Jika Nodenya sama dengan Top Level yang ingin diparse, skip
                    break;
                }else{
                    if($currentLevel == $topLevel && !$this->_topLevelParsed){//Jika parsing untuk parent node pertama kali
                        $this->_topLevelParsed = true;
                    }
                    if(!empty($value) && is_array($value)){
                        $new_id = $this->saveNode($key, null, $parent_id, $currentLevel);
                        self::parseWebServiceArray($value, $new_id, $topLevel, $currentLevel);
                    }else{
                        $new_id = $this->saveNode($key, $value, $parent_id, $currentLevel);
                    }
                }
            }
            $success = true;
        }
        return $success;
    }

    public function emptyStructure($trApiId){
        $CI = get_instance();
        $CI->load->model('property_hierarchy');
        $property_hierarchy = new property_hierarchy();
        $getData = $property_hierarchy->get_by_trapi_id($trApiId);
        $getData->delete_all();
        return true;
    }

    /**
     * @author Indra 27 August 2014
     * Fungsi untuk menyimpan node ke tabel property hierarchy
     * @param $key
     * @param null $value
     * @param int $parent_id
     * @return bool|int|mixed
     */
    public function saveNode($key, $value=null, $parent_id = 0, $currentLevel = 0){
        $CI = get_instance();
        $CI->load->model('property_hierarchy');
        $new_id = 0;
        $property_hierarchy = new property_hierarchy();
        $property_hierarchy->data_key = $key;
        $property_hierarchy->trapi_id = $this->_trApiId;
        $property_hierarchy->level = $currentLevel;
        if(!is_null($value) && !empty($value)){
            $property_hierarchy->data_value = $value;
        }
        if($parent_id != '' && $parent_id!=0){
            $property_hierarchy->parent_id = $parent_id;
        }
        if($property_hierarchy->save()){
            $property_hierarchy = new property_hierarchy();
            $new_data = $property_hierarchy->select_max('id')->get();
            $new_id = $new_data->id;
            return $new_id;
        }else{
            return false;
        }
    }
}