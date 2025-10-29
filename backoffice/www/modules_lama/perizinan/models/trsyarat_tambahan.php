<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Syarat Tambahan class
 *
 * @author Indra Halim
 * Created : 14 Mei 2015
 *
 */

class trsyarat_tambahan extends DataMapper {

    var $table = 'trsyarat_tambahan';
    public $has_one = array('tmpermohonan');

    public function __construct() {
        parent::__construct();
    }

}

// This is the end of user class
