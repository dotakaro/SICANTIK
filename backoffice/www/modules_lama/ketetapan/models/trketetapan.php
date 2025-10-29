<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of trketetapan class
 *
 * @author  Dichi Al Faridi
 * @since   1.0
 *
 */

class trketetapan extends DataMapper {

    var $table = "trketetapan";
    var $has_many = array('trperizinan');

    public function __construct() {
        parent::__construct();
    }

}

// This is the end of trketetapan class
