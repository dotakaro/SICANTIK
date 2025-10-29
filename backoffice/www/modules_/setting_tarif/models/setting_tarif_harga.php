<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
* Model class for report component module
* @author Indra Halim
* @version 1.0
*/
class setting_tarif_harga extends DataMapper {
    var $table = 'setting_tarif_harga';
    //var $belongs_to = array('setting_tarif_item');
    var $has_one = array('setting_tarif_item');
    public function __construct() {
        parent::__construct();
    }
}