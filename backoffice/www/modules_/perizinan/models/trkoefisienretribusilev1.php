<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Pendaftaran class
 *
 * @author Yogi Cahyana
 * Created : 22 oct 2010
 *
 */

class trkoefisienretribusilev1 extends DataMapper {

    var $table = 'trkoefisienretribusilev1';
    var $has_one = array('trkoefesientarifretribusi');
    var $has_many =array('trkoefisienretribusilev2');

    public function __construct() {
        parent::__construct();
    }

}

// This is the end of user class
