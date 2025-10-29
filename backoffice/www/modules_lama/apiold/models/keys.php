<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of keys class
 *
 * @author  Dichi Al Faridi
 * @since   1.0
 *
 */

class keys extends DataMapper {

    var $table = 'keys';

    public function __construct() {
        parent::__construct();
    }
}

// This is the end of keys class
