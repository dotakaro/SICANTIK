<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Pendaftaran class
 *
 * @author agusnur
 * Created : 06 Aug 2010
 *
 */

class trjenis_permohonan extends DataMapper {

    var $table = 'trjenis_permohonan';

    var $has_one = array('tmpermohonan','trproperty');

    public function __construct() {
        parent::__construct();
    }

}

// This is the end of user class
