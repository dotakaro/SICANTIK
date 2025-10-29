<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of settings class
 *
 * @author  Dichi Al Faridi
 * @since   1.0
 *
 */

class settings extends DataMapper {

    var $table = 'settings';

    public function __construct() {
        parent::__construct();
    }
}

// This is the end of settings class
