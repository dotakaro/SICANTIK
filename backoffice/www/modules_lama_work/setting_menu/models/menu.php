<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
* Model class for Setting Menu
* @author Indra Halim
* @version 1.0
*/
class menu extends DataMapper {
    var $table = 'menus';
    public function __construct() {
        parent::__construct();
    }

    public function getLastInsertId(){

    }

    public function getHtmlMenu($username, $permissions){
        $html = '';
        $CI = &get_instance();
        $CI->load->helper('tree');
        $structureData = $this->order_by('menu_order', 'ASC')->get();
//        echo "<pre>";print_r($permissions);exit();
        if($structureData->id){//Jika data ditemukan
            foreach($structureData as $key=>$value){
//                echo $value->link;
                $display = true;
                if(array_key_exists($value->link, $permissions)){//Jika ada di daftar permission
                    if($permissions[$value->link]['value']=='' || $permissions[$value->link]['value']==0){//Jika tidak diallow
                        $display = false;
                    }
                }

                $data[$key]['id'] = $value->id;
                $data[$key]['title'] = $value->title;
                $data[$key]['link'] = $value->link;
                $data[$key]['parent'] = $value->parent;
                $data[$key]['display'] = $display;
            }
            $data = buildMenuTree($data);
            $html = olLiMenuTree($data, true, $username);
        }
        echo $html;
    }
}