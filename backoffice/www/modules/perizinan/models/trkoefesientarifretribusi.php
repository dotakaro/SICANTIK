<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Pendaftaran class
 *
 * @author Yana Supriatna
 * Created : 09 Aug 2010
 *
 */

class trkoefesientarifretribusi extends DataMapper {

    var $table = 'trkoefesientarifretribusi';
    var $has_many = array('trproperty', 'trkoefisienretribusilev1',
        'tmproperty_klasifikasi', 'tmproperty_prasarana');
     
    public function __construct() {
        parent::__construct();
    }

}

// This is the end of user class
