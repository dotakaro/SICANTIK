<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of trdasar_hukum class
 *
 * @author  Dichi Al Faridi
 * @since   1.0
 *
 */

class trdasar_hukum extends DataMapper {

    var $table = "trdasar_hukum";
    var $has_many = array('trperizinan');

    public function __construct() {
        parent::__construct();
    }

}

// This is the end of trdasar_hukum class
