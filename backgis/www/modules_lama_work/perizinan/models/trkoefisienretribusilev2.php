<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Pendaftaran class
 *
 * @author Yogi Cahyana
 * Created : 22 oct 2010
 *
 */

class trkoefisienretribusilev2 extends DataMapper {

    var $table = 'trkoefisienretribusilev2';
    var $has_one = array('trkoefisienretribusilev1');

    public function __construct() {
        parent::__construct();
    }

}

// This is the end of user class
