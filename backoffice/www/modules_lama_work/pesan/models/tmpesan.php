<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of massage_mdl class
 *
 * @author Yogi Cahyana
 * @since   
 *
 */

class tmpesan extends DataMapper {

    var $table = 'tmpesan';
    var $has_one = array('trstspesan', 'mobile_user');
    var $has_many = array('trsumber_pesan');

    
    public function __construct() {
        parent::__construct();
    }

}

// This is the end of massage_mdl class
