<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of massage_mdl class
 *
 * @author  Yogi Cahyana
 * created : 31 Aug 2010
 *
 */

class trsumber_pesan extends DataMapper {

    var $table = 'trsumber_pesan';
    var $has_many = array('tmpesan');

    
    public function __construct() {
        parent::__construct();
    }

}

// This is the end of massage_mdl class
