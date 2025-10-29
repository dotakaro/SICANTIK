<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Kabupaten class
 *
 * @author agusnur
 * Created : 10 Aug 2010
 *
 */

class trkabupaten extends DataMapper {

    var $table = 'trkabupaten';
    var $has_one = array('trpropinsi');
    var $has_many = array('trkecamatan', 'trperizinan', 'tmpemohon', 'tmperusahaan');


    public function __construct() {
        parent::__construct();
    }
    
   
}

// This is the end of user class
