<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Model class for report component module
 * @author Indra Halim
 * @version 1.0
 */
class property_teknis_detail extends DataMapper {
    var $table = 'property_teknis_detail';
    var $has_one = array('trproperty','trunitkerja','property_teknis_header');

    public function __construct() {
        parent::__construct();
    }
}