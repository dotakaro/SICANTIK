<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of tmperusahaan class
 *
 * @author  Muhammad rizky
 * @since   1.0
 *
 */

class tmperusahaan_sementara extends DataMapper {

    var $table = 'tmperusahaan_sementara';
    var $has_many = array('tmpermohonan');
    
    var $has_one = array('trkelurahan', 'trkegiatan', 'trinvestasi');
    //penambahan var $has_one
    //perlu me-modifikasi class perusahaan/delete jg !!

    public function __construct() {
        parent::__construct();
    }

}

// This is the end of tmperusahaan class
