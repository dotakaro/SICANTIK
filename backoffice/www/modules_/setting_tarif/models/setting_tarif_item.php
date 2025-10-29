<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
* Model class for report component module
* @author Indra Halim
* @version 1.0
*/
class setting_tarif_item extends DataMapper {
    var $table = 'setting_tarif_item';
    var $has_many = array('setting_tarif_harga','retribusi_detail');
    var $has_one = array('trperizinan');
    
    public function __construct() {
        parent::__construct();
    }
}