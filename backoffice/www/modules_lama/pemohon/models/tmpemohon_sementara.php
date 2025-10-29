<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Pemohon class
 *
 * @author agusnur
 * Created : 05 Aug 2010
 */

class tmpemohon_sementara extends DataMapper {

    var $table = 'tmpemohon_sementara';
    var $has_many = array('tmpermohonan');
    
    var $has_one = array('trkelurahan');
    //penambahan var $has_one
    //perlu me-modifikasi class pemohon/delete jg !!

    public function __construct() {
        parent::__construct();
    }

}
