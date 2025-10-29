<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of logs class
 *
 * @author  Dichi Al Faridi
 * @since   1.0
 *
 */

class logs extends DataMapper {

    var $table = 'logs';

    public function __construct() {
        parent::__construct();
    }

}

// This is the end of logs class
