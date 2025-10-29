<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of outbox class
 *
 * @author  Dichi Al Faridi
 * @since   1.0
 *
 */

class outbox extends DataMapper {

    var $table = 'outbox';

    public function __construct() {
        parent::__construct();
    }

}

// This is the end of outbox class
