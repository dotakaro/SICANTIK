<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of retribusi_mdl
 *
 * @author Eva
 */
class trperizinan_trretribusi extends DataMapper {

    var $table = 'trperizinan_trretribusi';
    var $has_many = array('trproperty');
    var $has_one = array('trperizinan','trretribusi');

    public function __construct() {
        parent::__construct();
    }
}
?>
