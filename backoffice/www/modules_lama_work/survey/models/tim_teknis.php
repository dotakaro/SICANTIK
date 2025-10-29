<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of tmpegawai_trtanggal_survey class
 *
 * @author  Dichi Al Faridi
 * @since   1.0
 *
 */

class tim_teknis extends DataMapper {

    var $table = 'tim_teknis';
    
    var $has_many = array('tmbap', 'tmproperty_jenisperizinan');
    var $has_one = array('trunitkerja', 'trtanggal_survey');

    public function __construct() {
        parent::__construct();
    }

}

// This is the end of tmpegawai_trtanggal_survey class
