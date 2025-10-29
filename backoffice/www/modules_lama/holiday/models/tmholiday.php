<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of holiday_mdl class
 *
 * @author  Dichi Al Faridi
 * @since   1.0
 *
 */

class tmholiday extends DataMapper {

    var $table = 'tmholiday';

    public function __construct() {
        parent::__construct();
    }

}

// This is the end of holiday_mdl class
