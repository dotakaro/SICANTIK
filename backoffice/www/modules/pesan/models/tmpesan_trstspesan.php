<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tmpermohonan_trperizinan
 *
 * @author Yogi Cahyana
 */
class tmpesan_trstspesan extends DataMapper {

    var $table = 'tmpesan_trstspesan';

    var $has_one = array('tmpesan','trstspesan');

    public function __construct() {
        parent::__construct();
    }

}
?>
