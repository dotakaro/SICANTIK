<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of user class
 *
 * @author  Dichi Al Faridi
 * @since   1.0
 *
 */

class user extends DataMapper {

    var $table = 'user';
    var $has_many = array('user_auth', 'trperizinan', 'trunitkerja_user');
    var $has_one = array('tmpegawai');

    public function __construct() {
        parent::__construct();
    }

}

// This is the end of user class
