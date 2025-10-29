<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class untuk menyimpan data tabel-tabel yang digunakan pada mapping untuk suatu Web Service
 *
 * @author  Indra Halim
 * @since
 *
 */

class mapping extends DataMapper {

    var $table = 'mapping';
    var $has_one = array('trapi');
    var $has_many = array('mapping_detail');

    public function __construct() {
        parent::__construct();
    }
}

// This is the end of massage_mdl class
