<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of ci_sessions class
 *
 * @author  Dichi Al Faridi
 * @since   1.0
 *
 */

class sess extends DataMapper {

    var $table = 'ci_sessions';

    public function __construct() {
        parent::__construct();
    }

}

// This is the end of ci_sessions class
