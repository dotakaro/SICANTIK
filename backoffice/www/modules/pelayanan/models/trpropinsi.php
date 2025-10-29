<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Propinsi class
 *
 * @author agusnur
 * Created : 10 Aug 2010
 *
 */

class trpropinsi extends DataMapper {

    var $table = 'trpropinsi';
    var $has_many = array('trkabupaten', 'tmpemohon', 'tmperusahaan');

    public function __construct() {
        parent::__construct();
    }

}

// This is the end of user class
