<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of massage_mdl class
 *
 * @author  Yogi Cahyana
 * @since
 *
 */

class trunitkerja extends DataMapper {

    var $table = 'trunitkerja';
    var $has_many = array('trperizinan','tmpegawai','tim_teknis', 'setting_formula_detail', 'trunitkerja_user','property_teknis_header');

    public function __construct() {
        parent::__construct();
    }

}

// This is the end of massage_mdl class
