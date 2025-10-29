<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Tracking Progress class
 *
 * @author  agusnur
 * Created : 23 Aug 2010
 *
 */

class tmtrackingperizinan extends DataMapper {

    var $table = 'tmtrackingperizinan';
    var $has_one = array('trstspermohonan', 'tmpermohonan');

      public function __construct() {
        parent::__construct();
    }

}

// This is the end of massage_mdl class
