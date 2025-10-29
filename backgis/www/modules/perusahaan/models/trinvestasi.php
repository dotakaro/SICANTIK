<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Investasi class
 *
 * @author agusnur
 * Created : 08 Okt 2010
 *
 */

class trinvestasi extends DataMapper {

    var $table = 'trinvestasi';
    var $has_many = array('tmperusahaan','tmperusahaan_sementara');

    public function __construct() {
        parent::__construct();
    }

}
