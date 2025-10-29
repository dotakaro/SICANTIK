<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of tmpegawai class
 *
 * @author  Dichi Al Faridi
 * @since   1.0
 *
 */

class tmpegawai extends DataMapper {

    var $table = 'tmpegawai';
    var $has_many = array('tmsk','trtanggal_survey','tmsurat_keputusan');
    var $has_one = array('trunitkerja', 'user');

    public function __construct() {
        parent::__construct();
    }

}

// This is the end of tmpegawai class
