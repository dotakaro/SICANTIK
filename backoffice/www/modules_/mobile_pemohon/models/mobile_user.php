<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Pemohon class
 *
 * @author agusnur
 * Created : 05 Aug 2010
 */

class mobile_user extends DataMapper {

    var $table = 'mobile_user';
    var $has_many = array('tmpesan');

    public function __construct() {
        parent::__construct();
    }

}
