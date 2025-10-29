<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of trmenimbang class
 *
 * @author  agusnur
 * Created : 19 dec 2010
 *
 */

class trmenimbang extends DataMapper {

    var $table = "trmenimbang";
    var $has_many = array('trperizinan');

    public function __construct() {
        parent::__construct();
    }

}