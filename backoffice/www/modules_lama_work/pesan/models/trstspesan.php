<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of massage_mdl class
 *
 * @author  Yogi Cahyana
 * @since   
 *
 */

class trstspesan extends DataMapper {

    var $table = 'trstspesan';
    var $has_many = array('tmpesan');
    
    public function __construct() {
        parent::__construct();
    }

}

// This is the end of massage_mdl class
