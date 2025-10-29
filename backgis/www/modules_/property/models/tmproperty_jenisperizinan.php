<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tmproperty_jenisperizinan
 *
 * @author Eva
 */

class tmproperty_jenisperizinan extends DataMapper {

    var $table = 'tmproperty_jenisperizinan';

    var $has_many = array('tmpermohonan', 'trproperty');

      public function __construct() {
        parent::__construct();
    }

}
?>
