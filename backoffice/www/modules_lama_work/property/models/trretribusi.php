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
class trretribusi extends DataMapper {

    var $table = 'trretribusi';
    var $has_many = array('trproperty');
    var $has_one = array('trperizinan','trperizinan_trretribusi');

    public function __construct() {
        parent::__construct();
    }
}
?>
