<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class ini digunakan untuk listing unit kerja mana saja yang boleh diakses oleh seorang user
 *
 * @author  Dichi Al Faridi
 * @since   1.0
 *
 */

class trunitkerja_user extends DataMapper {

    var $table = 'trunitkerja_user';
    var $has_one = array('user', 'trunitkerja');

    public function __construct() {
        parent::__construct();
    }

}

// This is the end of user class
