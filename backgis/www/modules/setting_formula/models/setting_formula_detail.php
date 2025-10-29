<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
* Model class for report component module
* @author Indra Halim
* @version 1.0
*/
class setting_formula_detail extends DataMapper {
    var $table = 'setting_formula_detail';
    var $has_one = array('setting_formula_retribusi', 'trunitkerja');
    public function __construct() {
        parent::__construct();
    }
}