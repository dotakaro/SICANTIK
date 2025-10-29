<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of survey_mdl class
 *
 * @author  Dichi Al Faridi
 * @since   1.0
 *
 */

class trtanggal_survey extends DataMapper {

    var $table = 'trtanggal_survey';

    var $has_one = array('tmpermohonan');
    var $has_many = array('tmpegawai', 'tim_teknis');

    public function __construct() {
        parent::__construct();
    }
}

// This is the end of survey_mdl class
