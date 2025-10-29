<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Pendaftaran class
 *
 * @author Yogi
 *
 */

class tmpermohonan_trperizinan extends DataMapper {

    var $table = 'tmpermohonan_trperizinan';
    var $has_many = array('trpemohon');
    var $has_one = array('trperizinan','tmpermohonan');

    public function __construct() {
        parent::__construct();
    }

}

// This is the end of user class
