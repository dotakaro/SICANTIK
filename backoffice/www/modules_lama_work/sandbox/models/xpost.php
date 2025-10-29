<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of xpost class
 *
 * @author  Dichi Al Faridi
 * @since   1.0
 *
 */

class xpost extends DataMapper {

    var $table = 'xpost';

    var $has_one = array(
        'induk' => array(
            'class' => 'xpost',
            'other_field' => 'xpost'
        )
    );

    var $has_many = array(
        'xpost' => array(
            'other_field' => 'induk'
        )
    );


    public function __construct() {
        parent::__construct();
    }

}

// This is the end of xpost class
