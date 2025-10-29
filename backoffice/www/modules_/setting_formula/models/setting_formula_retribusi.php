<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
* Model class for report component module
* @author Indra Halim
* @version 1.0
*/
class setting_formula_retribusi extends DataMapper {
    var $table = 'setting_formula_retribusi';
    var $has_one = array('trperizinan');
    var $has_many = array('setting_formula_detail');

    public function __construct() {
        parent::__construct();
    }

    public function get_formula_javascript($trperizinan_id){
        $js ="";
        $setting_formula = $this->where('trperizinan_id',$trperizinan_id)->get();
        $m_setting_tarif_item = $this->load->model("setting_tarif/setting_tarif_item");
        $item_tarif = $m_setting_tarif_item->where('trperizinan_id',$trperizinan_id)->get();

        foreach($item_tarif as $item){
            $nama_item = strtolower($this->_remove_whitespace($item->nama_item));
            $js.="var \$".$nama_item." = parseFloat($('#subtotal_$nama_item').val());\n";
        }

        if($setting_formula->id){
            $js .= "formula_total = {$setting_formula->formula};\n";
        }else{
            $js .= "formula_total = 0;\n";
        }
        return $js;
    }

    private function _remove_whitespace($string){
        return preg_replace("/\s+/", "",$string );
    }
}