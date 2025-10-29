<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class untuk menyimpan Mapping Field di tabel dengan Field API
 *
 * @author  Indra Halim
 * @since
 *
 */

class mapping_detail extends DataMapper {

    var $table = 'mapping_detail';
    var $has_one = array('mapping');

    public function __construct() {
        parent::__construct();
    }
}

// This is the end of massage_mdl class
