<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of tmperusahaan class
 *
 * @author  Dichi Al Faridi
 * @since   1.0
 *
 */

class tmperusahaan extends DataMapper {

    var $table = 'tmperusahaan';
    var $has_many = array('tmpermohonan');
    
    var $has_one = array('trkelurahan', 'trkegiatan', 'trinvestasi');
    //penambahan var $has_one
    //perlu me-modifikasi class perusahaan/delete jg !!

    public function __construct() {
        parent::__construct();
    }

}

// This is the end of tmperusahaan class
