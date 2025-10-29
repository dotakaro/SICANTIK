<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Pendaftaran class
 *
 * @author Yana Supriatna
 * Created : 05 Aug 2010
 * Updated : 14 Aug 2010 (agusnur)
 *
 */

class trsyarat_perizinan extends DataMapper {

    var $table = 'trsyarat_perizinan';
    public $has_one = array('trperizinan');
    public $has_many = array('tmpermohonan');

    public function __construct() {
        parent::__construct();
    }

}

// This is the end of user class
