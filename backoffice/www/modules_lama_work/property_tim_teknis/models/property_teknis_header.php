<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
* Model class for report component module
* @author Indra Halim
* @version 1.0
*/
class property_teknis_header extends DataMapper {
    var $table = 'property_teknis_header';
    var $has_one = array('trperizinan','trunitkerja');
    var $has_many = array('property_teknis_detail');
    public function __construct() {
        parent::__construct();
    }
}