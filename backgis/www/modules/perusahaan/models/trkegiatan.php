<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Jenis Kegiatan class
 *
 * @author agusnur
 * Created : 08 Okt 2010
 *
 */

class trkegiatan extends DataMapper {

    var $table = 'trkegiatan';
    var $has_many = array('tmperusahaan','tmperusahaan_sementara');

    public function __construct() {
        parent::__construct();
    }

}
