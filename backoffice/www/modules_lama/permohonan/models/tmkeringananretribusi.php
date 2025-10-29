<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class tmkeringananretribusi extends DataMapper {

    var $table = 'tmkeringananretribusi';
    var $has_one = array('tmpermohonan');

    public function __construct() {
        parent::__construct();
    }

}

// This is the end of user class
