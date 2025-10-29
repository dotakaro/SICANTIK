<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Pemohon class
 *
 * @author agusnur
 * Created : 05 Aug 2010
 */

class tmpemohon extends DataMapper {

    var $table = 'tmpemohon';
    var $has_many = array('tmpermohonan');
    
    var $has_one = array('trkelurahan', 'tmarchive');
    //penambahan var $has_one
    //perlu me-modifikasi class pemohon/delete jg !!

    public function __construct() {
        parent::__construct();
    }

}
