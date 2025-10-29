<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of trstspermohonan
 *
 * @author Eva
 * Updated : 23 Aug 2010 (agusnur)
 * 
 */
class trstspermohonan extends DataMapper {

    var $table = 'trstspermohonan';

    var $has_many = array('tmpermohonan', 'tmtrackingperizinan','tmpermohonan_trstspermohonan','trlangkah_perizinan','setting_notifikasi_detail');

    public function __construct() {
        parent::__construct();
    }

}
?>
