<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of trmengingat class
 *
 * @author  agusnur
 * Created : 19 Dec 2010
 *
 */

class trmengingat extends DataMapper {

    var $table = "trmengingat";
    var $has_many = array('trperizinan');

    public function __construct() {
        parent::__construct();
    }

}
