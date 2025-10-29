<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Kecamatan class
 *
 * @author Yogi
 * Created : 27 Aug 2010
 *
 */

class tmpemohon_trkecamatan extends DataMapper {

    var $table = 'tmpemohon_trkecamatan';
    var $has_one = array('tmpemohon, trkecamatan');


    public function __construct() {
        parent::__construct();
    }

}

// This is the end of user class
