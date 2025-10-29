<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class tmbap extends DataMapper {

    var $table = 'tmbap';

    var $has_one = array( 'tmpemohon', 'trperizinan','tmbap_tmpermohonan',
    'tmperusahaan', 'trproperty',  'trstspermohonan','tmpermohonan');

    public function __construct() {
        parent::__construct();
    }

}

// This is the end of user class
