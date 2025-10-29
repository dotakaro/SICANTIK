<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tr_instansi
 *
 * @author Yobi Bina Setiawan
 */
class tmlogactivity extends DataMapper {

    //put your code here
    var $table = 'tmlogactivity';
    var $has_one = array();
    var $has_many = array();

    public function __construct() {
        parent::__construct();
    }


}

?>
